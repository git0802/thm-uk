<?php


namespace App\Helpers;


use App\Product;

trait MealHelper
{
    public function cloneToEating($planner, $request)
    {
        /**
        * this is for copying in eating
        */
        $requestedMeals = $planner->meals()->whereIn('id', $request->get('meal_ids'))->get();

        $plannerMeals = $planner->meals()->where('date', $request->get('date'))->where('eating', $request->get('eating'))->get();

        $plannerMealsIds = $plannerMeals->pluck('product_id');

        // check if product_id of requested meals are not in target eating
        $distinctMeals = $requestedMeals->filter(function($item) use ($plannerMealsIds) {
            return !$plannerMealsIds->contains($item->product_id);
        });

        // replicate meals to target
        $distinctMeals->each(function($item, $key) use ($request) {
            $replica         = $item->replicate();
            $replica->eating = $request->get('eating');
            $replica->date   = $request->get('date');
            $replica->push();
        });

        $message = collect([
            'created' => $distinctMeals->count(),
            'dupes'   => $requestedMeals->count() - $distinctMeals->count(),
        ]);

        return [
            'success' => true,
            'data' => $message->toArray()
        ];
    }

    public function cloneToDay($planner, $request)
    {
        $requestedMeals = $planner->meals()->whereIn('id', $request->get('meal_ids'))->get()->groupBy('eating');

        $plannerMeals = $planner->meals()->where('date', $request->get('date'))->get()->groupBy('eating');

        $plannerMealsIds = $plannerMeals->map(function ($i, $k) {
            return $i->pluck('product_id');
        });

        $distinctMeals = $requestedMeals->map(function($item, $key) use ($plannerMealsIds) {
            return $item->filter(function($i, $k) use ($plannerMealsIds, $key) {
                if(isset($plannerMealsIds[$key])) {
                    return !$plannerMealsIds[$key]->contains($i->product_id);
                } else {
                    return true;
                }
            });
        })->reject(function($value) {
            return $value === false;
        });

        $message = [
            'created' => $distinctMeals->flatten()->count(),
            'dupes'   => $requestedMeals->flatten()->count() - $distinctMeals->flatten()->count(),
        ];

        $distinctMeals->each(function($item, $key) use ($request) {
            $item->each(function($i, $k) use ($request){
                $replica         = $i->replicate();
                $replica->date   = $request->get('date');
                $replica->push();
            });
        });

        $message = collect([
            'created' => $distinctMeals->flatten()->count(),
            'dupes' => $requestedMeals->flatten()->count() - $distinctMeals->flatten()->count(),
        ]);

        return [
            'success' => true,
            'data' => $message->toArray()
        ];
    }

    /**
     * @param Product $product
     * @return \Illuminate\Support\Collection
     */

    public function productDataArray(Product $product)
    {
        $data = collect($product->load('image'));
        $data['store'] = $product->store()->with('image')->first()->toArray();
        if($product->dish()) {
            $data['dish'] = $product->dish()->with(['image', 'store.image'])->get()->toArray();
        }

        return $data;
    }

}
