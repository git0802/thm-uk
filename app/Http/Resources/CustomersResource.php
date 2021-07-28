<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $toArray = parent::toArray($request);
        $toArray['activated'] = $this->activated_at !== null;
        $toArray['created_at'] = $this->created_at;
        $start = $this->subscription()->first()->start;
        if ($start) {
            $toArray['billed_at'] = $start->format('Y-m-d H:i:s');
        } else {
            $toArray['billed_at'] = null;
        }
        $toArray['paid'] = $this->getPaidStrings();
        $toArray['id'] = $this->id;

        return $toArray;
    }
}
