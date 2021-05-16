<?php

namespace App\Http\Controllers;

use App\Exceptions\Errors;
use App\Helpers\MealHelper;
use App\Http\Requests\CloneMeals;
use App\Http\Requests\Meal\AddToPlanner;
use App\Http\Requests\Meal\IsMealEaten;
use App\Http\Requests\UpdateMeal;
use App\Http\Resources\Store\StoreResource;
use App\Meal;
use App\Helpers\UserHelper;
use App\Helpers\PlannerHelper;
use App\Planner;
use App\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Meal controller.
 *
 * @package App\Http\Controllers
 */
class MealController extends Controller
{
    use UserHelper, PlannerHelper, MealHelper;

    /**
     * Meal controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Mark meal as eaten/don`t eaten.
     *
     * @param Meal $meal
     * @param IsMealEaten $request
     * @return Response
     */
    public function eaten(Meal $meal, IsMealEaten $request)
    {
        // Check is meal belongs to the current user
        if ($this->userCanNotModifyPlanner($meal->planner)) {
            return response([
                'success' => false,
                'message' => __('meal.prohibited'),
            ], 403);
        }

        $meal->update($request->only('is_eaten'));

        return response([
            'success' => true,
            'message' => __('meal.eaten', [
                'meal' => $meal->product()['name'],
                'marked' => $request->get('is_eaten') ? 'eaten' : 'not eaten'
            ])
        ]);
    }

    /**
     * Add meal to planner.
     *
     * @param Planner $planner
     * @param Product $product
     * @param AddToPlanner $request
     * @return Response
     */
    public function add(Planner $planner, Product $product, AddToPlanner $request)
    {
        if (
            $this->userCanNotModifyPlanner($planner)
            || ($product->owner_id !== null && $product->owner_id !== Auth::id())
        ) {
            return response([
                'success' => false,
                'message' => __('meal.planner_prohibited')
            ], 403);
        }

        $date = Carbon::parse($request->get('date'))->startOfDay();

        if(!$date->between($planner->starts, $planner->ends)) {
            return response([
                'success' => false,
                'message' => __('meal.not_between')
            ], 422);
        };

        $json = $this->productDataArray($product);

        $meal = $planner->meals()->firstOrCreate(
            [
                'product_id' => $product->id,
                'eating' => $request->get('eating'),
                'date' => $date
            ],
            [
                'servings' => $request->get('servings'),
                'product_data' => $json->toArray(),
            ]
        );

        if(!$meal->wasRecentlyCreated) {
            return response([
                'success' => false,
                'message' => __('meal.in_db'),
            ], 422);
        }

        return response([
            'success' => true,
            'meal' => $meal,
        ]);
    }


    public function update(Planner $planner, Meal $meal, UpdateMeal $request)
    {
        if ($this->userCanNotModifyPlanner($planner) || $meal->planner_id !== $planner->id) {
            return response([
                'success' => false,
                'message' => 'You can`t modify this planner.'
            ], 403);
        }

        $meal->update($request->only('servings'));

        return response([
            'success' => true,
            'meal'    => $meal,
        ]);
    }

    /**
     * Clone meals to another day/eating
     * cases: 1 goes for copy eating 2 for whole day
     *
     * @param Planner $planner
     * @param CloneMeals $request
     * @return Response
     */

    public function cloneMeals(Planner $planner, CloneMeals $request)
    {
        switch ($request->get('case')) {
            case 1:
                $state = $this->cloneToEating($planner, $request);
                break;
            case 2:
                $state = $this->cloneToDay($planner, $request);
                break;
            default:
                return response([
                    'success' => false,
                ]);
                break;
        }


        return response($state);

    }

    /**
     * Delete meal from planner.
     *
     * @param Planner $planner
     * @param Meal $meal
     * @return Response
     * @throws Exception
     */
    public function remove(Planner $planner, Meal $meal)
    {
        if ($this->userCanNotModifyPlanner($planner) || $meal->planner_id !== $planner->id) {
            return response([
                'success' => false,
                'message' => 'You can`t modify this planner.'
            ], 403);
        }

        $meal->delete();

        return response([
            'success' => true,
        ]);
    }
}
