<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Helpers\VideoHelper;
use App\Http\Requests\Video\{CreateVideo, UpdateVideo};
use App\Http\Resources\Video\VideoResource;
use App\Video;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Exception;

class VideosController extends Controller
{
    use VideoHelper;
    use ImageHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Return all videos
     *
     * @return AnonymousResourceCollection
     */
    public function all(): AnonymousResourceCollection
    {
        return VideoResource::collection(Video::all());
    }

    /**
     * Store new youtube video to database
     *
     * @param CreateVideo $request
     * @return Response
     */
    public function create(CreateVideo $request): Response
    {
        if(!$this->isValidYoutubeHost($request->url)) {
            return  response([
                'success' => false,
                'message' => __('video.bad_host')
            ], 422);
        }
        $url = $this->parseUrl($request->url);
        $video = Video::create(
            collect(
                [$request->only('title', 'description', 'url'),
                ['youtube_id' => $url[1]]]
            )->collapse()->all()
        );
        if($request->hasFile('image')) {
            $this->saveImageFromRequestFile($video, $request->file('image'), 'videos');
        }
        return response([
            'success' => true,
            'message' => __('video.created')
        ]);
    }

    /**
     * Update video
     *
     * @param Video $video
     * @param UpdateVideo $request
     * @return Response
     */
    public function update(Video $video, UpdateVideo $request): Response
    {
        if(!$this->isValidYoutubeHost($request->url)) {
            return response([
                'success' => false,
                'message' => __('video.bad_host')
            ], 422);
        }
        $url = $this->parseUrl($request->url);
        $video->update(collect([
            $request->only('title', 'description', 'url'), ['youtube_id' => $url[1]]])->collapse()->all());
        if(filter_var($request->get('del_image'), FILTER_VALIDATE_BOOLEAN) && $video->image) {
            $video->image->delete();
        }
        if($request->hasFile('image')) {
            if($video->image)  {
                $this->updateImageFromRequestFile($video, $request->file('image'), 'videos');

            } else {
                $this->saveImageFromRequestFile($video, $request->file('image'), 'videos');
            }
        }

        return response([
            'success' => true,
            'message' => __('video.updated')
        ]);
    }

    /**
     * @param Video $video
     * @return Response
     * @throws Exception
     */
    public function remove(Video $video)
    {
        $video->delete();

        return response([
            'success' => true,
            'message' => __('video.deleted')
        ]);
    }
}
