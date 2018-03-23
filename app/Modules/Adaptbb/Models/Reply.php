<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;
use RyanWeber\Mutators\Timezoned;

class Reply extends Model
{
    use Timezoned;

    protected $table = 'plugin_adaptbb_replies';

    protected $fillable = [
        'message',
        'active',
        'forum_id',
        'topic_id'
    ];

    /**
    * Topics
    *
    * @return Collection
    */
    public function topic()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\Topic', 'topic_id');
    }

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
    * Get Url
    *
    * @return string
    */
    public function getUrl()
    {
	    $forum_slug = Forum::where('id', '=', $this->forum_id)->pluck('slug')->first();
	    $topic_slug = Topic::where('id', '=', $this->topic_id)->pluck('slug')->first();
	    
	    return route(
            'plugin.adaptbb.topics.view', 
            compact('forum_slug', 'topic_slug')
        ) . '#reply_id_' . $this->id;
    }
}