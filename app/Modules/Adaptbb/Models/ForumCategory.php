<?php

namespace App\Modules\Adaptbb\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ForumCategory extends Model implements Sortable
{
    use Sluggable,
        SortableTrait;

    protected $table = 'plugin_adaptbb_forum_categories';

    protected $fillable = [
        'name',
        'ord'
    ];

    public $sortable = [
        'order_column_name' => 'ord',
        'sort_when_creating' => true,
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