<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'post_tags';
    
    /**
    * Post
    *
    * @return Post
    */
    public function post()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Post');
    }
    
    /**
    * Tag
    *
    * @return Tag
    */
    public function tag()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Tag');
    }
}