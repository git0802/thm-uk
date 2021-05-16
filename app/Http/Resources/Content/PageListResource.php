<?php

namespace App\Http\Resources\Content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->when($request->get('mode') === 'full', $this->id),
            'title' => $this->title,
            'body' => $this->when($request->get('mode') === 'full', $this->body),
            'description' =>  $this->when($request->get('mode') === 'full', $this->description),
            'og_title' => $this->when($request->get('mode') === 'full', $this->og_title),
            'og_description' =>  $this->when($request->get('mode') === 'full', $this->og_description),
            'order' =>  $this->when($request->get('mode') === 'full', $this->order),
            'slug' => $this->slug,
            'order' => $this->order,
        ];
    }
}
