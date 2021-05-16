<?php

namespace App\Http\Resources;

use App\PlannerPreset;
use App\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class PresetJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = PlannerPreset::buildEmptyArray();

        foreach ($this->resource as $day => $d) {
            foreach($d as $eating => $p) {
                foreach($p as $productId => $value){
                    $array[$day][$eating][] =
                        [
                            'product'  => PlannerPresetProductResource::make(Product::find($productId)),
                            'servings' => $value['servings']
                        ];
                }
            }
        }

        return $array;
    }
}
