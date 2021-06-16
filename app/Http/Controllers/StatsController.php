<?php

namespace App\Http\Controllers;

use App\Extra;
use App\Helpers\PlannerHelper;
use App\Helpers\StatsStaticHelper;
use App\Helpers\UserHelper;
use App\Planner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Stats Controller
 *
 * @package App\Http\Controllers
 */
class StatsController extends Controller
{
    use PlannerHelper, UserHelper;

    /**
     * Stats controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get week stats for current user.
     *
     * @return Response
     */
    public function week()
    {
        $planner = $this->getUser()->planners()->where('starts', now()->startOfWeek())->first();

        $totalExtra = 0;
        $dayExtra = 0;
        $result = [
            'dailyCalories' => [
                'dayTitle' => now()->format("D. d Y"),
                'caloriesTotal' => 0,
                'caloriesEaten' => $dayExtra,
                'costTotal' => 0,
                'costSpent' => 0,
            ],
            'weeklyCalories' => [
                'caloriesTotal' => 0,
                'caloriesEaten' => $totalExtra,
                'costTotal' => 0,
                'costSpent' => 0,
            ],
        ];
        if(!$planner or !$planner['validation_data']['is_valid']) {
            return response([
                'success' => true,
                'stats' => $result
            ]);
        }


        foreach (Extra::where('planner_id', $planner->id)->get() as $extra) {
            $totalExtra += $extra->value;
            if ($extra->date->day === intval(date("j"))) {
                $dayExtra += $extra->value;
            }
        }


        foreach ($planner->meals as $meal) {
            // Totals
            $result['weeklyCalories']['caloriesTotal'] += ($meal->product()['calories'] * $meal->servings);
//            $result['weeklyCalories']['costTotal'] += $meal->product()['cost'];
            if ($meal->date->day === intval(date("j"))) {
                $result['dailyCalories']['caloriesTotal'] += ($meal->product()['calories'] * $meal->servings);
//                $result['dailyCalories']['costTotal'] += $meal->product()['cost'];
            }
            // Eaten
            if ($meal->is_eaten) {
                $result['weeklyCalories']['caloriesEaten'] += ($meal->product()['calories'] * $meal->servings);
//                $result['weeklyCalories']['costSpent'] += $meal->product()['cost'];
                if ($meal->date->day === intval(date("j"))) {
                    $result['dailyCalories']['caloriesEaten'] += ($meal->product()['calories'] * $meal->servings);
//                    $result['dailyCalories']['costSpent'] += $meal->product()['cost'];
                }
            }
        }

        $shoppingList = $planner->calcShoppingList();

        if(isset($shoppingList)) {
            foreach ($shoppingList as $item) {
                $result['weeklyCalories']['costTotal'] += $item['cost'];
            }
        }


        $result['weeklyCalories']['caloriesEaten'] += $totalExtra;
        $result['dailyCalories']['caloriesEaten'] += $dayExtra;


        return response([
            'success' => true,
            'stats' => $result
        ]);
    }

    /**
     * Get total stats for current user.
     *
     * @param Request $request
     * @return Response
     */
    public function total(Request $request)
    {
        $planners = $request->user()->planners;
        $result = [
            'currentWeek' => $this->currentWeek($planners->where('starts', Carbon::now()->startOfWeek())->first()),
            'allWeeks' => [],
        ];

        if ($request->has('start')) {
            $start = $request->get('start');
            $planners = $planners->filter(function ($value, $key) use ($start){
                return new Carbon($value->ends) >= new Carbon($start);
            });
        }

        if ($request->has('end')) {
            $end = $request->get('end');
            $planners = $planners->filter(function ($value, $key) use ($end){
                return new Carbon($value->starts) <= new Carbon($end);
            });
        }

        $offset = $request->user()->planners->where('starts', '<', $planners->min('starts'))->count();
        $i = 0;
        foreach ($planners->sortBy('starts') as $planner) {
            $result['allWeeks'][] = StatsStaticHelper::extractPlannerData($planner);
            $result['allWeeks'][$i]['linkText'] = "View Week " . ($offset + $i + 1);
            $i++;
        }

        return response([
            'success' => true,
            'stats' => $result
        ]);
    }

    /**
     * Calculate current week stats for charts in progress page.
     *
     * @param Planner $planner
     * @return array
     */
    private function currentWeek($planner)
    {
        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $planner->starts->addDays($i)->format('d-m-Y');
            $result[$date] = [
                'plan' => 0,
                'fact' => 0,
            ];
        }


        foreach (Extra::where('planner_id', $planner->id)->get() as $extra) {
            $result[$extra->date->format('d-m-Y')]['fact'] += $extra->value;
        }

        foreach ($planner->meals as $meal) {
            $date = $meal->date->format('d-m-Y');
            $result[$date]['plan'] += ($meal->product()['calories'] * $meal->servings);
            if ($meal->is_eaten) {
                $result[$date]['fact'] += ($meal->product()['calories'] * $meal->servings);
            }
        }

        return $result;
    }
}
