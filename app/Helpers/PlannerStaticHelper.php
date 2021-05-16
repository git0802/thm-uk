<?php


namespace App\Helpers;


use App\Planner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PlannerStaticHelper
{
    /**
     * Planner validation
     * @param Planner $planner
     * @return bool[]
     */

    public static function nestedValidation(Planner $planner)
    {
        $goalCalories = $planner->calories_goal;
        $meals = $planner->meals()->get();
        $offset = 60;
        $week = [];
        $validator = ['is_valid' => true];
        $user = $planner->user()->first();
        // checking if user registered on planner start-end range
        $isRookie = $user->created_at->between($planner->starts->startOfDay(), $planner->ends->endOfDay());

        // populate week
        for ($i = 0; $i < 7; $i++) {
            $date = $planner->starts->addDays($i)->format('Y-m-d');
            $validator['days'][$date]['is_valid'] = false;
        }
        // iteration though all days and validate each day which contains meals
        foreach ($meals as $key => $meal) {
            $week[$meal->date->format('Y-m-d')]['meals'][] = $meal;
            $storesMealCalories[$meal->date->format('Y-m-d')] = 0;
            foreach ($week[$meal->date->format('Y-m-d')]['meals'] as $nested) {
                $storesMealCalories[$meal->date->format('Y-m-d')] += ($nested->product()['calories'] * $nested->servings);
                $tempCal = $storesMealCalories[$meal->date->format('Y-m-d')] ;

                if((($goalCalories - $offset) <= $tempCal) && ($tempCal <= ($goalCalories + $offset))) {
                    $validator['days'][$meal->date->format('Y-m-d')]['is_valid'] = true;
                } else {
                    $validator['days'][$meal->date->format('Y-m-d')]['is_valid'] = false;
                }
                $validator['days'][$meal->date->format('Y-m-d')]['added_calories'] = $tempCal;
                $validator['days'][$meal->date->format('Y-m-d')]['goal'] = $goalCalories;
            }
        }
        // validate full week and calculate offset if validation fails;
        foreach ($validator['days'] as $key => $day) {
            $validator['days'][$key]['offset'] = $offset;

           // update: i really tired to commenting and uncommenting this line...
            // we skipping day validation if planner goals was recently updated
//            if((Carbon::parse($key) < $planner->updated_at->startOfDay())) {
//                $validator['days'][$key]['is_valid'] = true;
//                $validator['is_valid'] = true;
//                continue;
//            }

            $validator['days'][$key]['offset'] = $offset;
            $validator['is_valid'] = $validator['days'][$key]['is_valid'] && $validator['is_valid'];

            // if user isRookie and iterated day is less than day of registration - automatically VALIDATE day

            if(!$day['is_valid'] && isset($validator['days'][$key]['added_calories'])) {
                $validator['days'][$key]['diff']
                    = $validator['days'][$key]['added_calories'] -
                    ($validator['days'][$key]['goal'] +
                        (($validator['days'][$key]['goal'] < $validator['days'][$key]['added_calories']) ? $validator['days'][$key]['offset'] : -$validator['days'][$key]['offset'])
                    );
            }
        }


        if($user->finished_setup) {
            $planner->finished_setup = true;
            $planner->save();
        }

        return $validator;
    }

    public static function calculateStats(Planner $planner)
    {
        dd($planner);
    }

    /**
     * Store list calculation
     *
     * @param Planner $planner
     * @return array
     */

    public static function calculateStoreList(Planner $planner)
    {
        $meals = $planner->meals()->get();
        $list = $planner->shoppingList();



        $distinctProductList = [];

        foreach($meals as $meal) {
            if($meal['product_data']['is_dish']) {
                foreach($meal['product_data']['dish'] as $m) {
//                    dd($list[$m['id']]['quantity']);
                    $distinctProductList[$m['id']]['product'] = $m;
                    isset($distinctProductList[$m['id']]['count']) ?
                        $distinctProductList[$m['id']]['count']  += 1:
                        $distinctProductList[$m['id']]['count']  = 1;

                    isset($list[$m['id']]['quantity']) ?
                        $distinctProductList[$m['id']]['quantity'] = $list[$m['id']]['quantity']:
                        $distinctProductList[$m['id']]['quantity'] = 1;

                    $distinctProductList[$m['id']]['cost'] = round( ($m['cost']  * $distinctProductList[$m['id']]['quantity']), 2);
                }
            } else {
                $distinctProductList[$meal['product_data']['id']]['product']  = $meal['product_data']->toArray();
                isset($distinctProductList[$meal['product_data']['id']]['count']) ?
                    $distinctProductList[$meal['product_data']['id']]['count'] += (integer) $meal['servings']:
                    $distinctProductList[$meal['product_data']['id']]['count'] = (integer) $meal['servings'];



                isset($list[$meal['product_data']['id']]['quantity']) ?
                    $distinctProductList[$meal['product_data']['id']]['quantity'] = $list[$meal['product_data']['id']]['quantity']:
                    $distinctProductList[$meal['product_data']['id']]['quantity'] = 1;

//                dd((integer) ($meal['product_data']['cost'] * $distinctProductList[$meal['product_data']['id']]['quantity']));

                $distinctProductList[$meal['product_data']['id']]['cost'] = round( ($meal['product_data']['cost'] * $distinctProductList[$meal['product_data']['id']]['quantity']), 2 );
            }
        };

        $planner->shopping_list_data = $distinctProductList;
        $planner->save();

        return $distinctProductList;
    }


    /**
     * Two actions combined
     * PlannerStaticHelper::nestedValidation
     * PlannerStaticHelper::calculateStoreList
     *
     * @param Planner $planner
     */

    public static function combinedCalculation(Planner $planner)
    {
        self::nestedValidation($planner);
        self::calculateStoreList($planner);
    }
}
