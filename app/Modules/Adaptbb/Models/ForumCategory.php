<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    protected $table = 'plugin_adaptbb_forum_categories';

    protected $fillable = [
        'name',
        'slug',
        'ord'
    ];

    public function forums()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Forum', 'category_id');
    }
}