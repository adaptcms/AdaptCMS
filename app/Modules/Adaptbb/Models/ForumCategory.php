<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ForumCategory extends Model
{
    use Sluggable;

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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}