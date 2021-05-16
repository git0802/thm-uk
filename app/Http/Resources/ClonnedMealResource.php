<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClonnedMealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        dd($this['product_data']);
        return [
            'id' => $this->id,
            'product' => ClonnedProductResource::make($this['product_data']),
            'eating' => $this->eating,
            'is_eaten' => $this->is_eaten,
            'servings' => $this->servings,
            'date' => $this->date->format("Y-m-d"),
        ];
    }
}
