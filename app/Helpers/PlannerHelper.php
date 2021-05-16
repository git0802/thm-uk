<?php

namespace App\Helpers;

use App\Planner;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Planner helpers.
 *
 * @package App\Helpers
 */
trait PlannerHelper
{
    /**
     * Check is current user can modify week planner.
     *
     * @param Planner $planner
     * @return bool
     */
    public function userCanModifyPlanner($planner)
    {
        return $planner && $planner->user_id === Auth::id();
    }

    /**
     * Check is current user can`t modify week planner.
     *
     * @param Planner $planner
     * @return bool
     */
    public function userCanNotModifyPlanner($planner)
    {
        return !$this->userCanModifyPlanner($planner);
    }

    /**
     * Check is user can use a products from request.
     *
     * @param array $meals
     * @param Planner $planner
     * @return Response|array
     */
    public function checkMeals($meals, $planner)
    {
        $checked = [];
        foreach($meals as $meal) {
            $product = Product::find($meal['product_id']);
            // Check user rights
            if (
                !$product ||
                ($product->owner_id !== null && $product->owner_id !== $planner->user_id)
            ) {
                return response([
                    'success' => false,
                    'message' => __('meal.product_prohibited')
                ], 403);
            }
            // Check dates
            $diff_in_days = $planner->starts->diffInDays(Carbon::parse($meal['date']), false);
            if ($diff_in_days > 6 || $diff_in_days < 0) {
                return response([
                    'success' => false,
                    'message' => __('meal.wrong_date')
                ], 422);
            }
            $checked[] = $meal;
        }

        return $checked;
    }


    /**
     * Create planner if not planners not exists before; Clone last planner if latest exsits
     *
     * @param $date
     * @return array
     */

    public function findOrCreatePlanner($date)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $parsedDate = $date ? Carbon::parse($date) : $now;

        $planner = Planner::where('user_id', $user->id)
            ->when($parsedDate,
                function($query) use ($user, $parsedDate) {
                    $query->whereDate('starts', $parsedDate->copy()->startOfWeek()->format('Y-m-d H:i:s'));
                },
                function ($query) use ($user, $now) {
                    $query->whereDate('starts', $now->copy()->startOfWeek()->format('Y-m-d H:i:s'));
                })
            ->first();


        // create new planner if one doesn't exists at current week
        if(!$planner && ($parsedDate->copy()->startOfWeek() == $now->copy()->startOfWeek())) {
            $latest = $user->planners()->latest()->orderBy('starts')->with('meals')->first();

            if($latest) {
                // we creating array in which key represents relation name and value represents callback
                $relations = [
                    'meals' => function($meal) {
                        $meal->is_eaten = false;
                        $day = $meal->date->format('l');
                        $parseString = now()->isoWeekDay() > $meal->date->isoWeekDay() ? 'last' : '';
                        $date = Carbon::parse($parseString . ' ' . $day);
                        $meal->date = $date->startOfDay();
                        $meal->save();
                    }];
                $planner = $this->deepPlannerReplicate($latest, $now, $relations);
            } else {
                $planner = Planner::make([
                    'starts'        => $now->startOfWeek()->format('Y-m-d H:i:s'),
                    'ends'          => $now->endOfWeek()->format('Y-m-d H:i:s'),
                    'goal'          => $user->initial_goal,
                    'calories_goal' => $user->initial_calories_goal,
                    'weight'        => $user->weight,
                ]);
            }
            $planner->user_id = $user->id;
            $planner->save();

        }

        if($planner) {
            $planner->validation_data  =  PlannerStaticHelper::nestedValidation($planner);
            $planner->save();
        }


        // return redirect code if user not finished registration
        return ['planner' => $planner, 'code' => $user->finished_setup ? 200 : 200];
    }

    /**
     * @param Planner $planner
     * @return bool[]
     */

    public function validatePlanner(Planner $planner)
    {
        $goalCalories = $planner->calories_goal;
        $meals = $planner->meals()->get();
        $offset = 60;
        $week = [];
        $validator = ['is_valid' => true];
        $user = Auth::user();
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
                if(!$nested->product()->exists()) {
                    $nested->delete();
                    continue;
                }
                $storesMealCalories[$meal->date->format('Y-m-d')] += ($nested->product->calories * $nested->servings);
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
//            if((Carbon::parse($key) < $user->created_at->startOfDay()) && $isRookie  ) {
//                $validator['days'][$key]['is_valid'] = true;
//                $validator['is_valid'] = true;
//                continue;
//            }
            // we skipping day validation if planner goals was recently updated
            if((Carbon::parse($key) < $planner->updated_at->startOfDay())) {
                $validator['days'][$key]['is_valid'] = true;
                $validator['is_valid'] = true;
                continue;
            }

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
        $planner->finished_setup = $validator['is_valid'];
        // finish user setup
        if(!$user->finished_setup && $planner->finished_setup) {
            $user->finished_setup = true;
            $user->save();
        }
        return $validator;
    }


    public function nestedValidation(Planner $planner)
    {
        $goalCalories = $planner->calories_goal;
        $meals = $planner->meals()->get();
        $offset = 60;
        $week = [];
        $validator = ['is_valid' => true];
        $user = Auth::user();
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
                $storesMealCalories[$meal->date->format('Y-m-d')] += ($nested->product->calories * $nested->servings);
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
            // we skipping day validation if planner goals was recently updated
            if((Carbon::parse($key) < $planner->updated_at->startOfDay())) {
                $validator['days'][$key]['is_valid'] = true;
                $validator['is_valid'] = true;
                continue;
            }

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
        $planner->finished_setup = $validator['is_valid'];
        // finish user setup
        if(!$user->finished_setup && $planner->finished_setup) {
            $user->finished_setup = true;
            $user->save();
        }
        return $validator;


    }

    /**
     * Model deep replication with selected relations
     *
     * @param Planner $planner
     * @param array $relation
     * @return Planner
     */
    public function deepPlannerReplicate(Planner $planner, $now, $relation = [])
    {
        $replica = $planner->replicate();
        $replica->starts         = $now->startOfWeek()->format('Y-m-d H:i:s');
        $replica->ends           = $now->endOfWeek()->format('Y-m-d H:i:s');
        // force user to update goals (or skip) after old planner was replicated
        $replica->need_to_update = true;

        $checkPlanner = Planner::where('user_id', Auth::guard('sanctum')->id())->where('starts', $replica->starts)->first();


        if( $checkPlanner ) {
            return $checkPlanner;
        } else {
            $replica->push();
        }

        foreach ($relation as $relation => $callback) {
            foreach( $planner->{$relation} as $key => $entry ) {
                $e = $entry->replicate();
                call_user_func($callback, $e);
                if($e->push()) {
                    $replica->{$relation}()->save($e);
                }
            }
        }

        return $replica;
    }
}
