<?php

namespace App\Http\Resources\Planner;

use App\Http\Resources\Meal\MealResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $meals = [];
        $days = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
        ];

        for ($i = 0; $i < 7; $i++) {
            $date = $this->starts->addDays($i)->format('Y-m-d');
            $meals_by_eating = [];
            foreach (['breakfast', 'lunch', 'dinner', 'snacks'] as $eating) {
                $meals_by_eating[$eating] = MealResource::collection(
                    $this->meals()
                        ->whereDate('date', $date)
                        ->where('eating', $eating)
                        ->get()
                );
            }
            $meals[$date] = [
                'day' => $days[$i],
                'meals' => $meals_by_eating,
            ];
        }

        return [
            'id' => $this->id,
            'starts' => $this->starts->format('Y-m-d'),
            'ends' => $this->ends->format('Y-m-d'),
            'goal' => $this->goal,
            'calories_goal' => $this->calories_goal,
            'weight' => $this->weight,
            'extra_calories' => $this->extras,
            'list' => $meals,
            'finished_setup' => $this->finished_setup,
            'validation' => $this->when(isset($this->validation), $this->validation)
        ];
    }
}
