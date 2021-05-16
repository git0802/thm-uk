<?php

namespace App\Http\Resources;

use App\PlannerPreset;
use App\Http\Resources\Store\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlannerPresetResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->when(isset($this->name), $this->name),
            'description'  => $this->when(isset($this->description), $this->description),
            'store'        => $this->when(isset($this->store_id), StoreResource::make($this->store()->first())),
            'preset_case'  => PlannerPreset::getCaseByValue($this->preset_case),
            'is_published' => $this->is_published,
            'preset'       => PresetJsonResource::make($this->preset),
        ];
    }
}
