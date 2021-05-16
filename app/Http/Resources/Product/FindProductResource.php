<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FindProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $products = parent::toArray($request);
        // i know
        unset($products['original_url']);
        $products['image'] = $this->image
            ? url('storage/' . $this->image->path)
            : url('images/no-product-image.jpg');
        $products['url'] = $this->original_url;
        $products['is_custom'] = $this->owner_id !== null;
        $products['dish'] = $this->when($this->is_dish, ProductResource::collection($this->dish));
        return $products;
    }
}
