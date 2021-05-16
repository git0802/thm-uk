<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'youtube_id' => $this->youtube_id,
            // i know. but it is how this ->when works ;(
            'image' => $this->when($this->image, $this->image ? url('storage/' . $this->image->path) : ''),
        ];
    }
}
