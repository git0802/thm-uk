<?php

namespace App\Http\Resources;

use App\Helpers\ProductHelper;
use App\Http\Resources\Store\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClonnedProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this['id'],
            'name' => $this['name'],
            'image' => $this['image'] ?
                url('storage/' . $this['image']['path']) :
                url('images/no-product-image.jpg') . '?t=0',
            'store' => ClonnedStoreResource::make($this['store']),
            'serving_size' => $this['serving_size'],
            'calories' => $this['calories'],
            'cost' => $this['cost'],
            'is_custom' => $this['owner_id'] !== null,
            'is_dish' => $this['is_dish'] ? true : false,
        ];
        if($this['is_dish']) {
            $array['dish'] = $this->when(isset($this['dish']) , ClonnedProductResource::collection($this['dish']));
        }
        if(isset($this['package_size'])) {
            $array['package_size'] = $this['package_size'];
        }
        if(isset($this['original_url'])) {
            $array['url'] = ProductHelper::fullLink($this['original_url']);
        }
        return $array;
    }
}
