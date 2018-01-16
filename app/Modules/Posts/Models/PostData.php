<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class PostData extends Model
{
    use Searchable;
    
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
    * Search Logic
    *
    * @param array $searchData
    * @param bool $admin
    *
    * @return array
    */
    public function searchLogic($searchData = [], $admin = false)
    {   
        if (!empty($searchData['keyword'])) {
            $results = PostData::search($searchData['keyword'])->get();
            
            foreach ($results as $key => $row) {
                if ($admin) {
                    $results[$key]->url = route('admin.posts.edit', [ 'id' => $row->id ]);
                } else {
                    $results[$key]->url = route('posts.view', [ 'slug' => $row->slug ]);
                }
            }
        } elseif(!empty($searchData['post_id']) && !empty($searchData['field_id'])) {
            $results = [
                PostData::where('post_id', '=', $searchData['post_id'])
                    ->where('field_id', '=', $searchData['field_id'])->first()
            ];
        } else {
            $results = [];
        }

        return $results;
    }
}