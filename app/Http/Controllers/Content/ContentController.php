<?php

namespace App\Http\Controllers\Content;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\PostResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ImageHelper;
use App\Helpers\UserHelper;

/**
 * Content Controller.
 *
 * @package App\Http\Controllers\Content
 */
class ContentController extends Controller
{
    use ImageHelper;
    use UserHelper;

    /**
     * ContentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only([
                'create', 'update', 'delete'
            ]);
    }

    /*
     * Content type.
     */
    /**
     * @var string
     */
    public $contentable = 'post';

    /**
     * Show page.
     *
     * @param string $slug
     * @return Response
     */
    public function show($slug): Response
    {
        $content = Content::type($this->contentable)->slug($slug)->firstOrFail();
        ++$content->views;
        $content->save();

        return response([
            'success' => true,
            'post' => PostResource::make($content)
        ]);
    }

    /**
     * Create content.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $this->abortIfNotAdmin();
        $validated = $request->validate($this->getRules());
        $content = Content::create($validated);

        if ($this->contentable === 'post') {
            if ($request->has("is_featured") && $request->get('is_featured')) {
                $this->toggleFeatured($content);
            }
            $content->taxonomy = $request->get('taxonomy');
            $this->saveImageFromRequest($content, $request);
        } else {
            $content->type = 'page';
            if ($request->has("order") && $request->get('order')) {
                $content->order = $request->get("order");
            }
        }
        $content->save();

        return response([
            'success' => true,
            'message' => ucfirst($this->contentable) . ' created successfully!',
        ]);
    }

    /**
     * Update content.
     *
     * @param Content $content
     * @param Request $request
     * @return Response
     */
    public function update(Content $content, Request $request): Response
    {
        $this->abortIfNotAdmin();
        $validated = $request->validate($this->getRules(false));
        if (($content->slug !== $request->get('slug')) && count(Content::select('slug')->slug($request->slug)->get())) {
            return response([
                'success' => false,
                'errors' => [
                    'slug' => [ 'Slug already taken!' ]
                ]
            ], 422);
        }
        $content->fill($validated);
        if ($this->contentable === 'post') {
            if ($request->has("is_featured") && $request->get('is_featured')) {
                $this->toggleFeatured($content);
            }
            $content->taxonomy = $request->get('taxonomy');
            $request->file('image') ? $this->updateImageFromRequest($content, $request) : null;
        } else if ($request->has("order") && $request->get('order')) {
            $content->order = $request->get("order");
        }
        $content->save();

        return response([
            'success' => true,
            'message' => ucfirst($this->contentable) . ' updated successfully!',
        ]);
    }

    /**
     * Delete content.
     *
     * @param Content $content
     * @return Response
     * @throws Exception
     */
    public function delete(Content $content): Response
    {
        $this->abortIfNotAdmin();
        if ($content->image) {
            $content->image->delete();
        }
        $content->delete();

        return response([
            'success' => true,
            'message' => $content->title . ' deleted successfully!',
        ]);
    }

    /**
     * Is admin?
     */
    private function abortIfNotAdmin(): void
    {
        if (!$this->isAdmin()) {
            abort(404);
        }
    }

    /**
     * Get rules array.
     *
     * @param bool $create
     * @return array
     */
    private function getRules($create = true): array
    {
        $rules = [
            "title" => 'required|string|min:3|max:191',
            "body" => 'required|string',
            "description" => 'nullable|string|max:191',
            "og_title" => 'nullable|string|max:191',
            "og_description" => 'nullable|string|max:191',
            'slug' => [
                'required',
                'string',
                'max:188',
                'regex:/^[a-z0-9_]+(-[a-z0-9_]+)*$/',
                'not_in:' . implode(",", config('app.blacklist_urls')),
            ],
        ];
        if ($this->contentable === 'post') {
            $create
                ? $rules['image'] = 'required|image'
                : $rules['image'] = 'nullable|image';
            $rules['taxonomy'] = 'required|string|in:Healthy lifestyle,Training,Healthy meal';
            $rules['description'] = 'required|string|max:191';
            $rules['og_title'] = 'required|string|max:191';
            $rules['og_description'] = 'required|string|max:191';
            $rules['is_featured'] = 'nullable|boolean';
        }
        if ($create) {
            $rules['slug'] = 'unique:contents';
            $rules['order'] = 'nullable|numeric|min:0|max:10000000';
        }

        return $rules;
    }

    /**
     * @param $content
     */
    private function toggleFeatured($content): void
    {
        $featured = Content::posts()->where('is_featured', true)->get();
        foreach ($featured as $post) {
            $post->is_featured = false;
            $post->save();
        }
        $content->is_featured = true;
        $content->save();
    }
}
