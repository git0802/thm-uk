<?php

namespace App\Http\Resources;

use App\Helpers\ProductHelper;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Store\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannerPresetProductResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image ?
                url('storage/' . $this->image->path) . "?t=" . $this->image->updated_at->timestamp :
                url('images/no-product-image.jpg') . '?t=0',
            'store_id' => $this->store_id,
            'serving_size' => $this->serving_size,
            'package_size' => $this->package_size,
            'url' => ProductHelper::fullLink($this->original_url),
            'calories' => $this->calories,
            'cost' => $this->cost,
            'is_custom' => $this->owner_id !== null,
            'is_dish' => $this->is_dish ? true : false,
            'dish' => $this->when($this->is_dish, PlannerPresetProductResource::collection($this->dish)),
        ];
    }
}
