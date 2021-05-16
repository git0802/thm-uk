<?php

namespace App\Http\Resources;

use App\Http\Resources\Meal\MealResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClonedPlannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
                $meals_by_eating[$eating] = ClonnedMealResource::collection(
                    $this->meals()
                        ->whereDate('date', $date)
                        ->where('eating', $eating)
                        ->orderBy('id')
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
            'need_to_update' => $this->need_to_update,
            'validation' => $this->when(isset($this->validation_data), $this->validation_data)
        ];
    }
}
