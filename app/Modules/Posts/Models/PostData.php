<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class PostData extends Model
{
    /**                                                                                                                                                                                                                                             
     * The table associated with the model.                                                                                                                                                                                                         
     *                                                                                                                                                                                                                                              
     * @var string                                                                                                                                                                                                                                  
     */
    protected $table = 'post_data';
    
    protected $fillable = [
		'user_id',
		'field_id',
		'body'    
    ];
    
    public function post()
    {
        return $this->belongsTo('App\Modules\Posts\Models\Post');
    }
}