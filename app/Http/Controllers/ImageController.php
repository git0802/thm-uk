<?php

namespace App\Http\Controllers;

use App\Content;
use App\Http\Requests\Image\UploadImage;
use App\Http\Requests\Image\ChangeAvatar;
use App\Http\Resources\ImageListResource;
use App\Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Image controller.
 *
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    /**
     * Change current user avatar.
     *
     * @param ChangeAvatar $request
     * @return Response
     */
    public function changeAvatar(ChangeAvatar $request)
    {
        $user = Auth::guard('sanctum')->user();

        // Remove old avatar
        if ($user->image) {
            Storage::delete($user->image->path);
            $user->image()->delete();
        }

        // Store new
        $path = Storage::putFile('images/avatars', $request->file('image'));
        $user->image()->create([
            'path' => $path
        ]);

        return response([
            'success' => true,
            'path' => url('storage/' . $path)
        ]);
    }

    /**
     * Upload images from CKEditor 5.
     *
     * @param UploadImage $request
     * @return Response
     */
    public function upload(UploadImage $request)
    {
        // TODO: validate here and return correct error response
        // https://www.npmjs.com/package/ckeditor5-simple-upload#backend

        $md5 = md5_file($request->file("upload")->getPathname());
        $sameImage = Image::where("md5", $md5)->first();
        if ($sameImage) {
            return response([
                "uploaded" => true,
                "url" => url("storage/" . $sameImage->path)
            ]);
        }
        $path = Storage::putFile("images/blog/", $request->file("upload"));
        Image::create([
            'path'           => $path,
            'md5'            => $md5,
            'imageable_type' => Content::class
        ]);

        return response([
            "uploaded" => true,
            "url" => url("storage/" . $path)
        ]);
    }


    public function imageList()
    {
        $list = Image::where('imageable_type', Content::class)->get();

        return ImageListResource::collection($list);

    }
}
