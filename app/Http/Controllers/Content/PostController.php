<?php

namespace App\Http\Controllers\Content;

use App\Content;
use App\Exceptions\Error;
use App\Http\Requests\Content\AllPosts;
use App\Http\Resources\Blog\PostResource;
use Illuminate\Http\Response;

/**
 * Post controller.
 *
 * @package App\Http\Controllers\Content
 */
class PostController extends ContentController
{
    /**
     * Get all posts with use offset.
     *
     * @param AllPosts $request
     * @param string|null $taxonomy
     * @return Response
     */
    public function all(AllPosts $request, $taxonomy = null): Response
    {
        $offset = $request->has('offset') ? (int)$request->get('offset') : 0;
        if ($taxonomy) {
            switch ($taxonomy) {
                case "popular":
                    $posts = Content::posts()->popular()->offset($offset)->orderByDesc('created_at')->take(10)->get();
                    $total = Content::posts()->popular()->count();
                    break;
                case "recent":
                    $posts = Content::posts()->recent()->offset($offset)->orderByDesc('created_at')->take(10)->get();
                    $total = Content::posts()->recent()->count();
                    break;
                default:
                    $posts = Content::posts()->taxonomy($taxonomy)->offset($offset)->orderByDesc('created_at')->take(10)->get();
                    $total = Content::posts()->taxonomy($taxonomy)->count();
                    break;
            }
        } else {
            $posts = Content::posts()->offset($offset)->orderByDesc('created_at')->take(10)->get();
            $total = Content::posts()->count();
        }

        return response([
            'success' => true,
            'post' => PostResource::collection($posts),
            'total' => $total
        ]);
    }

    /**
     * Return featured blog post
     *
     * @return Response
     */

    public function featured(): Response
    {
        $post = Content::posts()->where('is_featured', true)->first();

        return response([
            'success' => true,
            'featured_post' => $post ? PostResource::make($post) : null,
        ]);
    }

    /**
     * Get all posts without offset.
     *
     * @return Response
     * @throws Error
     */
    public function allPosts(): Response
    {
        if (!$this->isAdmin()) {
            throw new Error("Prohibited", 403);
        }

        return response([
            'success' => true,
            'posts' => PostResource::collection(Content::posts()->get())
        ]);
    }
}
