<?php

namespace App\Modules\Adaptbb\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\Modules\Adaptbb\Models\Reply;

class Topic extends Model
{
    use Searchable,
        Sluggable;

    protected $table = 'plugin_adaptbb_topics';

    protected $fillable = [
        'name',
        'message',
        'icon',
        'topic_type',
        'active',
        'locked',
        'forum_id'
    ];

    /**
    * Forum
    *
    * @return Forum
    */
    public function forum()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\Forum', 'forum_id');
    }

    /**
    * User
    *
    * @return User
    */
    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User', 'user_id');
    }

    /**
    * Replies
    *
    * @return Collection
    */
    public function replies()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Reply', 'topic_id');
    }
    
    /**
    * Get Url
    *
    * @return string
    */
    public function getUrl()
    {
        $forum_slug = Forum::where('id', '=', $this->forum_id)->pluck('slug')->first();
        $topic_slug = $this->slug;
        
        return route('plugin.adaptbb.topics.view', compact('forum_slug', 'topic_slug'));
    }
    
    /**
    * Get Latest Reply
    *
    * @return Reply|null
    */
    public function getLatestReply()
    {
        // get the last reply
        $last_reply = Reply::where('forum_id', '=', $this->forum_id)
            ->where('topic_id', '=', $this->id)
            ->where('active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return $last_reply;
    }

    /**
    * To Searchable Array
    *
    * @return array
    */
    public function toSearchableArray()
    {
        $entity = $this->toArray();

        $entity['topic_slug'] = $this->slug;
        $entity['forum_slug'] = $this->forum->slug;

        return $entity;
    }

    /**
    * Search Logic
    *
    * @return array
    */
    public function searchLogic($searchData = [], $admin = false)
    {   
        if (!empty($searchData['keyword'])) {
            $results = Topic::search($searchData['keyword'])->get();
        } elseif(!empty($searchData['id'])) {
            $results = [ Topic::find($searchData['id']) ];
        } else {
            $results = [];
        }

        foreach ($results as $key => $row) {
            $results[$key]->url = route('plugin.adaptbb.topics.view', [ 
                'forum_slug' => $row->forum_slug,
                'topic_slug' => $row->topic_slug
            ]);
        }

        return $results;
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