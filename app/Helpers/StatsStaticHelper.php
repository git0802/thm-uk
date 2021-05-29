<?php


namespace App\Helpers;


use App\Extra;
use App\Planner;

class StatsStaticHelper
{

    /**
     * Extract planner data for statistics
     *
     * @param Planner $planner
     * @return array
     */
    public static function extractPlannerData(Planner $planner)
    {
        $dateFormat = config('thehotmeal.date_format_full');
        $result = [
            'id' => $planner->id,
            'plan' => 0,
            'fact' => 0,
            'spent' => 0,
            'goal' => $planner->goal,
            'netCal' => number_format($planner->calories_goal),
            'weight' => $planner->weight,
            'date' => $planner->ends->format($dateFormat),
            'start' => $planner->starts->format($dateFormat),
            'end' => $planner->ends->format($dateFormat),
        ];


        foreach ($planner->meals as $meal) {
            $result['plan'] += ($meal->product()['calories'] * $meal->servings);
            if ($meal->is_eaten) {
                $result['fact'] += ($meal->product()['calories'] * $meal->servings);
            }
        }

        $shoppingList = $planner->calcShoppingList();

        if(isset($shoppingList)) {
            foreach ($shoppingList as $item) {
                $result['spent'] += $item['cost'];
            }
        }

        $extraCal = 0;
        foreach (Extra::where('planner_id', $planner->id)->get() as $extra) {
            $extraCal += $extra->value;
        }
        $result['fact'] += $extraCal;
        $fact = $result['fact'] - $result['plan'];
        $text = 'over';
        if ($fact < 0) {
            $fact *= -1;
            $text = "under";
        }

        $int = number_format($fact);
        $result['result'] = "You were {$int} cal {$text} limit";
        $result['spent'] = round($result['spent'], 2);

        return $result;
    }

}
