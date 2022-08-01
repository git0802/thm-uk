<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\ImageHelper;

class UserResource extends JsonResource
{
    use ImageHelper;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'email'          => $this->email,
            'name'           => $this->name,
            'last_name'      => $this->last_name,
            'weight'         => $this->weight,
            'height'         => $this->height,
            'age'            => $this->age,
            'gender'         => $this->gender,
            'phone'          => $this->phone,
            'created'        => $this->created_at->diffForHumans(),
            'avatar'         => $this->userAvatar($this),
            'is_activated'   => $this->activated_at !== null,
            'finished_setup' => $this->finished_setup,
            'is_spam_wanted' => $this->is_spam_wanted,
            'my_dishes_id'   => $this->stores->first()->id,
            'goal'           => $this->goal,
            'height_in_feet' => $this->height_in_feet,
        ];
        if ($this->is_admin) {
            $result['is_admin'] = true;
        }

        return $result;
    }
}
