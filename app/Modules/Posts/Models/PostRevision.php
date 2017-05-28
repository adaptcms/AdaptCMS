<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class PostRevision extends Model
{
    /**                                                                                                                                                                                                                                             
     * The table associated with the model.                                                                                                                                                                                                         
     *                                                                                                                                                                                                                                              
     * @var string                                                                                                                                                                                                                                  
     */
    protected $table = 'post_revisions';
    
    public function post()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Post');
    }
}