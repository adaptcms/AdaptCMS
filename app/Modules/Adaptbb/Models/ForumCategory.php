<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ForumCategory extends Model
{
    use HasSlug;

    protected $table = 'plugin_adaptbb_forum_categories';

    protected $fillable = [
        'name',
        'ord'
    ];

    public function forums()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Forum', 'category_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}