<?php

namespace App\Http\Resources\Dish;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $array['image'] = $this->image
            ? url('storage/' . $this->image->path)
            : url('images/no-product-image.jpg');
        $array['dish'] = $this->when($this->is_dish, ProductResource::collection($this->dish));

        return $array;

    }
}
