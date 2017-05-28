<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'plugin_adaptbb_replies';

    protected $fillable = [
        'message',
        'active',
        'forum_id',
        'topic_id'
    ];

    public function topic()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\Topic', 'topic_id');
    }

    public function forum()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\Forum', 'forum_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User', 'user_id');
    }
    
    public function getUrl()
    {
	    $forum_slug = Forum::where('id', '=', $this->forum_id)->pluck('slug')->first();
	    $topic_slug = Topic::where('id', '=', $this->topic_id)->pluck('slug')->first();
	    
	    return route('plugin.adaptbb.topics.view', [ 'forum_slug' => $forum_slug, 'topic_slug' => $topic_slug ]) . '#reply_id_' . $this->id;
    }
}