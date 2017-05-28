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
    
    public function post()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Post');
    }
    
    public function tag()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Tag');
    }
}