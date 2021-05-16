<?php

namespace App\Http\Resources\Store;

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    use UserHelper;

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
            'name' => $this->name,
            'is_custom' => $this->owner_id !== null,
            'in_progress' => $this->when($this->isAdmin(), $this->in_progress),
            'product_count' => $this->products->count(),
            'image' => $this->image ?
                url('storage/' . $this->image->path) . '?t=' . $this->image->updated_at->timestamp :
                url('images/no-store-image.jpg') . '?t=0',
        ];
    }
}
