<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Helpers\MealHelper;
use App\Http\Requests\CreateDishByMeals;
use App\Http\Requests\Dish\CreateDish;
use App\Http\Resources\Dish\DishResource;
use App\Meal;
use App\Planner;
use App\Product;
use App\Helpers\UserHelper;
use Illuminate\Http\Response;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Dish Controller
 *
 * @package App\Http\Controllers
 */
class DishController extends Controller
{
    use UserHelper, ImageHelper, MealHelper;

    /**
     * Dish controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Create a dish.
     *
     * @param CreateDish $request
     * @return Response
     * @throws Error
     */

    /**
     * DEPRECATED
     */

//    public function create(CreateDish $request): Response
//    {
//        // Check is all products exist
//        foreach ($request->get('product_ids') as $id) {
//            // Fails if not found
//            $dish_product = Product::findOrFail($id);
//
//            // Fails if there is a custom product of the other customer
//            if ($dish_product->owner_id !== null && $dish_product->owner_id !== $this->id()) {
//                throw new Error(__('dish.creating_prohibited'), 403);
//            }
//
//            if($dish_product->is_dish) {
//                throw new Error(__('dish.group_prohibited'), 422);
//            }
//
//
//        }
//
//        $find = Product::where('owner_id', $this->id())->where('name', $request->name)->first();
//
//        if($find) {
//            throw new Error(__('dish.dupe_name'), 422);
//        }
//
//
//        // Store blank dish in DB
//        $dish = new Product;
//        $dish->name = $request->name;
//        $dish->store_id = Auth::user()->stores->first()->id;
//        $dish->owner_id = $this->id();
//        $dish->is_dish = true;
//        $dish->save();
//
//        // Add products to a dish
//        $dish->dish()->attach($request->get('product_ids'));
//
//        if ($request->file('image') !== null) {
//            $this->saveImageFromRequest($dish, $request, 'dishes');
//        }
//
//
//        // Calc summary params
//        $dish_params = [
//            'calories' => 0,
//            'serving_size' => 0,
//            'cost' => 0
//        ];
//        foreach ($dish->dish as $product) {
//            $dish_params['calories'] += $product->calories;
//            $dish_params['serving_size'] = 1;
//            $dish_params['cost'] += $product->cost;
//        }
//        $dish->update($dish_params);
//
//        return response([
//            'success' => true,
//            'message' => __('dish.created', ['dish' => $dish->name]),
//            'dish' => DishResource::make($dish),
//        ]);
//    }


    /**
     * Dish creation-adding and removing meals as result
     *
     * @param CreateDishByMeals $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws Error
     */

    public function createDishByMeals(CreateDishByMeals $request)
    {
        $mealArray = collect();
        $eating = null;
        $date = null;
        $plannerId = null;
        foreach ($request->get('meal_ids') as $id) {
            $mealProduct = Meal::findOrFail($id);
            $mealArray->push($mealProduct);
            $plannerId = $mealProduct->planner_id;

            // check if user can use this meals
            if ($mealProduct->owner_id !== null && $mealProduct->owner_id !== $this->id()) {
                throw new Error(__('dish.creating_prohibited'), 403);
            }

            if($mealProduct['product_data']['is_dish']) {
                throw new Error(__('dish.group_prohibited'), 422);
            }


            // checking if all meals comes from one eating
            if(!isset($eating))  {
                $eating = $mealProduct->eating;
            } else {
                if($eating === $mealProduct->eating) {
                    $eating = $mealProduct->eating;
                } else {
                    throw new Error(__('dish.eating_diff'), 422);
                }
            }

            //check if meals comes from same day
            if(!isset($date))  {
                $date = $mealProduct->date;
            } else {
                if($date->startOfDay()->equalTo($mealProduct->date->startOfDay())) {
                    $date = $mealProduct->date;
                } else {
                    throw new Error(__('dish.eating_date_diff'), 422);
                }
            }
        }


        // Store blank dish in DB
        $dish = new Product;
        $dish->name = $request->name;
        $dish->store_id = Auth::user()->stores->first()->id;
        $dish->owner_id = $this->id();
        $dish->is_dish = true;
        $dish->save();

        // Add products to a dish
        $dish->dish()->attach($mealArray->pluck('product_id'));

        if ($request->file('image') !== null) {
            $this->saveImageFromRequest($dish, $request, 'dishes');
        }

        //delete old meals
        $mealArray->each(function($item, $key) {
            $item->delete();
        });

        // Calc summary params
        $dish_params = [
            'calories' => 0,
            'serving_size' => 0,
            'cost' => 0
        ];
        foreach ($dish->dish as $product) {
            $dish_params['calories'] += $product->calories;
            $dish_params['serving_size'] = 1;
            $dish_params['cost'] += $product->cost;
        }
        $dish->update($dish_params);

        $json = $this->productDataArray($dish);

        $meal = Planner::find($plannerId)->meals()->firstOrCreate(
            [
                'product_id' => $dish->id,
                'eating' => $eating,
                'date' => $date
            ],
            [
                'servings' => 1,
                'product_data' => $json->toArray(),
            ]
        );

        return response([
            'success' => true,
                'message' => __('dish.created', ['dish' => $dish->name]),
                'dish' => DishResource::make($dish),
        ]);

    }

    /**
     * Remove dish.
     *
     * @param integer $id
     * @return Response
     */
    public function remove($id)
    {
        $dish = Product::findOrFail($id);

        if ($dish->owner_id !== $this->id()) {
            return response([
                'success' => false,
                'message' => __('dish.deleting_prohibited'),
            ], 403);
        }

        $dish->dish()->detach();
        $dish->delete();

        return response([
            'success' => true,
            'message' => __('dish.deleted'),
        ]);
    }
}
