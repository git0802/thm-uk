<?php

namespace App\Http\Controllers\Content;

use App\Content;
use App\Http\Resources\Content\PageListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Page controller.
 *
 * @package App\Http\Controllers\Content
 */
class PageController extends ContentController
{
    /**
     * @var string
     */
    public $contentable = 'page';

    /**
     * Get list of all pages.
     *
     * @return AnonymousResourceCollection
     */
    public function list(Request $request): AnonymousResourceCollection
    {
        $pages = Content::pages()->when($request->order, function ($query) {
            return $query->orderBy('order');
        })->get();
        return PageListResource::collection($pages);
    }


    /**
     * Get all pages.
     *
     * @return Response
     */
    public function all(): Response
    {
        return response([
            'success' => true,
            'pages' => Content::pages()->get()
        ]);
    }
}
