<?php

namespace App\Modules\Adaptbb\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use Sluggable;

    protected $table = 'plugin_adaptbb_forum_categories';

    protected $fillable = [
        'name',
        'ord'
    ];

    /**
    * Forums
    *
    * @return Collection
    */
    public function forums()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Forum', 'category_id');
    }

    /**
    * Sluggable
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