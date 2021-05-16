<?php

namespace App\Observers;

use App\Store;
use Illuminate\Support\Facades\Log;

class StoreObserver
{
    /**
     * Handle the store "created" event.
     *
     * @param Store $store
     * @return void
     */
    public function created(Store $store)
    {
        //
    }

    /**
     * Handle the store "updated" event.
     *
     * @param Store $store
     * @return void
     */
    public function updated(Store $store)
    {
        //
    }

    /**
     * Handle the store "deleted" event.
     *
     * @param Store $store
     * @return void
     */
    public function deleted(Store $store)
    {
        if ($store->image) {
            $store->image->delete();
        }

        $products = $store->products;

        if (count($products)) {
            foreach ($products as $product) {
                $product->delete();
            }
        }
    }

    /**
     * Handle the store "restored" event.
     *
     * @param Store $store
     * @return void
     */
    public function restored(Store $store)
    {
        //
    }

    /**
     * Handle the store "force deleted" event.
     *
     * @param Store $store
     * @return void
     */
    public function forceDeleted(Store $store)
    {
        //
    }
}
