<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $result = parent::toArray($request);
        if ($this->type === 'post') {
            $result['image'] = $this->when($this->image()->exists(), url("storage/" . $this->image->path));
            $result['views'] =  $this->views;
            $result['created_at'] = $this->created_at->format('H:m m.d.y');
        }


        return $result;
    }
}
