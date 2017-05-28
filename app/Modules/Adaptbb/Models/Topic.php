<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;

use App\Modules\Adaptbb\Models\Reply;

class Topic extends Model
{
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

    public function forum()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\Forum', 'forum_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Reply', 'topic_id');
    }
    
    public function getUrl()
    {
	    $forum_slug = Forum::where('id', '=', $this->forum_id)->pluck('slug')->first();
	    
	    return route('plugin.adaptbb.topics.view', [ 'forum_slug' => $forum_slug, 'topic_slug' => $this->slug ]);
    }
    
    public function getLatestReply()
    {
	    // get the last reply
	    $last_reply = Reply::where('forum_id', '=', $this->forum_id)->where('topic_id', '=', $this->id)->where('active', '=', 1)->orderBy('created_at', 'DESC')->first();
	    
	    return $last_reply;
    }
}