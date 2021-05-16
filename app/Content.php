<?php

namespace App;

use App\Relationships\MorphOne\Image;
use App\Relationships\MorphOne\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Content model.
 *
 * @package App
 */
class Content extends Model
{
    use Image;
    use Slug;

    /**
     * Mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        "title", "description", "og_title", "og_description", "body", "taxonomy", "slug", "order", "type"
    ];

    /**
     * Do not show.
     *
     * @var array
     */
    protected $hidden = [
        "created_at", "views", "sitemaps_weight", "updated_at"
    ];

    /**
     * Get by slug.
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType($query, $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Get by slug.
     *
     * @param Builder $query
     * @param string $slug
     * @return Builder
     */
    public function scopeSlug($query, $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get by popular.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePopular($query): Builder
    {
        return $query->orderBy('views', 'desc');
    }

    /**
     * Get by recent.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent($query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get by pages.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePages($query): Builder
    {
        return $query->where('type', 'page');
    }

    /**
     * Get by posts.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePosts($query): Builder
    {
        return $query->where('type', 'post');
    }

    /**
     * Get by taxonomy.
     *
     * @param Builder $query
     * @param string $taxonomy
     * @return Builder
     */
    public function scopeTaxonomy($query, $taxonomy): Builder
    {
        return $query->where('taxonomy', $taxonomy);
    }
}
