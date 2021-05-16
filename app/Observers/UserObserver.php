<?php

namespace App\Observers;

use App\Store;
use App\User;

class UserObserver
{
    /**
     * Handle the store "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        $store = new Store;
        $store->owner_id = $user->id;
        $store->name = 'Grouped Food';
        $store->save();
        $store->image()->create([
            'path' => 'defaults/my-dish.jpg',
        ]);
    }
}
