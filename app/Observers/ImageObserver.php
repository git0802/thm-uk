<?php

namespace App\Observers;

use App\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Handle the image "created" event.
     *
     * @param Image $image
     * @return void
     */
    public function created(Image $image)
    {
        //;
    }

    /**
     * Handle the image "updated" event.
     *
     * @param Image $image
     * @return void
     */
    public function updated(Image $image)
    {
        //
    }

    /**
     * Handle the image "deleted" event.
     *
     * @param Image $image
     * @return void
     */
    public function deleted(Image $image)
    {
        Storage::delete($image->path);
    }

    /**
     * Handle the image "restored" event.
     *
     * @param Image $image
     * @return void
     */
    public function restored(Image $image)
    {
        //
    }

    /**
     * Handle the image "force deleted" event.
     *
     * @param Image $image
     * @return void
     */
    public function forceDeleted(Image $image)
    {
        //
    }
}
