<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlannerPresetStoreResource extends JsonResource
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
                url('storage/' . $this->image->path) . '?t=' . $this->image->updated_at->timestamp :
                url('images/no-store-image.jpg') . '?t=0',
        ];
    }
}
