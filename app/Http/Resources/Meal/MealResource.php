<?php

namespace App\Http\Resources\Meal;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => ProductResource::make($this->product),
            'eating' => $this->eating,
            'is_eaten' => $this->is_eaten,
            'servings' => $this->servings,
            'date' => $this->date->format("Y-m-d"),
        ];
    }
}
