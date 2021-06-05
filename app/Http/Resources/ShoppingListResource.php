<?php

namespace App\Http\Resources;

use App\Helpers\ProductHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingListResource extends JsonResource
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
             'id'           => $this['product']['id'],
             'name'         => $this['product']['name'],
             'store'        => $this['product']['store']['name'],
             'calories'     => $this['product']['calories'],
             'serving_size' => $this['product']['serving_size'],
             'url'          => ProductHelper::fullLink($this['product']['original_url']),
             'servings'     => $this['count'],
             'cost'         => $this['cost'],
        ];

         if(isset($this['quantity'])) {
             $array['quantity'] = $this['quantity'];
         }

         return $array;
    }
}
