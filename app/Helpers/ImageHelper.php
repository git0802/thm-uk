<?php

namespace App\Helpers;

use App\Http\Resources\User\UserResource;
use App\Image;
use App\User;
use Illuminate\Support\Facades\Storage;

/**
 * Image helpers.
 *
 * @package App\Helpers
 */
trait ImageHelper
{
    /**
     * Get full path of avatar or default if none.
     *
     * @param User|UserResource $user
     * @return string|null
     */
    public function userAvatar($user): ?string
    {
        return $user->image
            ? $this->getUrl($user->image)
            : null;
    }

    /**
     * Save image from request.
     *
     * @param mixed $imageable
     * @param mixed $request
     * @param string $folder
     * @return void
     */
    public function saveImageFromRequest($imageable, $request, $folder = 'posts'): void
    {
        $this->saveImageFromRequestFile($imageable, $request->file('image'), $folder);
    }

    /**
     * Update image from request.
     *
     * @param mixed $imageable
     * @param mixed $request
     * @param string $folder
     * @return void
     */
    public function updateImageFromRequest($imageable, $request, $folder = 'posts'): void
    {
        $this->updateImageFromRequestFile($imageable, $request->file('image'), $folder);
    }

    /**
     * Save image from request file.
     *
     * @param mixed $imageable
     * @param mixed $image
     * @param string $folder
     * @return void
     */
    public function saveImageFromRequestFile($imageable, $image, $folder = 'posts'): void
    {
        $path = Storage::putFile('images/' . $folder, $image);
        $imageable->image()->create([
            'path' => $path
        ]);
    }

    /**
     * Update image from request file.
     *
     * @param mixed $imageable
     * @param mixed $image
     * @param string $folder
     * @return void
     */
    public function updateImageFromRequestFile($imageable, $image, $folder = 'posts'): void
    {
        Storage::delete($imageable->image->path);
        $path = Storage::putFile('images/' . $folder, $image);
        $imageable->image->update([
            'path' => $path
        ]);
    }

    /**
     * Delete image by passing model
     *
     * @param mixed $imageable
     * @return void
     */

    public function deleteImage($imageable): void
    {
        Storage::delete($imageable->image->path);
        $imageable->image->delete();
    }

    /**
     * Get url with timestamp
     *
     * @param Image $image
     * @return string
     */
    private function getUrl($image): string
    {
        return url('storage/' . $image->path);
    }


    /**
     * @param $hexdata
     * @return string
     */

    public function getBytesFromHexString($hexdata)
    {
        for($count = 0; $count < strlen($hexdata); $count+=2)
            $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

        return implode($bytes);
    }

    /**
     * @param $imagedata
     * @return int|string|null
     */

    public function getImageMimeType($imagedata)
    {
        $imagemimetypes = array(
            "jpeg" => "FFD8",
            "png" => "89504E470D0A1A0A",
            "gif" => "474946",
            "bmp" => "424D",
            "tiff" => "4949",
            "tiff" => "4D4D"
        );

        foreach ($imagemimetypes as $mime => $hexbytes)
        {
            $bytes = $this->getBytesFromHexString($hexbytes);
            if (substr($imagedata, 0, strlen($bytes)) == $bytes)
                return $mime;
        }

        return NULL;
    }

}
