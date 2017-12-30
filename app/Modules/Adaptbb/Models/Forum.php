<?php

namespace App\Modules\Adaptbb\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Modules\Adaptbb\Models\ForumCategory;
use App\Modules\Adaptbb\Models\Topic;
use App\Modules\Adaptbb\Models\Reply;

class Forum extends Model
{
	use Sluggable;

    protected $table = 'plugin_adaptbb_forums';

    protected $fillable = [
        'name',
        'notice',
        'locked',
        'ord',
        'description',
        'backgroundColor',
        'icon',
        'borderColor',
        'borderWidth',
        'category_id',
        'meta_keywords',
        'meta_description'
    ];

    public function category()
    {
        return $this->belongsTo('App\Modules\Adaptbb\Models\ForumCategory', 'category_id');
    }

    public function topics()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Topic', 'forum_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Modules\Adaptbb\Models\Reply', 'forum_id');
    }

    public function getIndex()
    {
	    $forums = [];

	    $categories = ForumCategory::orderBy('ord', 'ASC')->get();
	    foreach($categories as $category) {
		    $row = [
				'category' => $category,
				'forums' => []
		    ];

		    foreach($category->forums as $forum) {
			    $row['forums'][] = $forum;
		    }

		    $forums[] = $row;
	    }

	    return $forums;
    }

    public function getLatestPost()
    {
	    // get the last topic and reply
	    $last_topic = Topic::where('forum_id', '=', $this->id)->where('active', '=', 1)->orderBy('created_at', 'DESC')->first();
	    $last_reply = Reply::where('forum_id', '=', $this->id)->where('active', '=', 1)->orderBy('created_at', 'DESC')->first();

	    // if no posts, return empty array
	    if (empty($last_topic) && empty($last_reply)) {
		    return [];
	    }

	    // set a default date for topic and reply
	    if (empty($last_topic)) {
		    $last_topic_date = 0;
	    } else {
		    $last_topic_date = strtotime($last_topic->created_at);
	    }

	    if (empty($last_reply)) {
		    $last_reply_date = 0;
	    } else {
		    $last_reply_date = strtotime($last_reply->created_at);
	    }

	    // get the absolute newest
	    if ($last_topic_date > $last_reply_date) {
		    $post = $last_topic;
	    } else {
		    $post = $last_reply;
	    }

	    return $post;
    }

    /**
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
