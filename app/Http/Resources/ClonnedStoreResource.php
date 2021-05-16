<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClonnedStoreResource extends JsonResource
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
            'id' => $this['id'],
            'name' => $this['name'],
            'is_custom' => $this['owner_id'] !== null,
            'image' => $this['image'] ?
                url('storage/' . $this['image']['path']):
                url('images/no-store-image.jpg') . '?t=0',
        ];
    }
}
