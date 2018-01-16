<?php

namespace App\Modules\Adaptbb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\Reply;
use App\Modules\Adaptbb\Models\Topic;

use Auth;
use Cache;
use Core;
use Theme;

class TopicsController extends Controller
{
    /**
    * View
    *
    * @param string $forum_slug
    * @param string $topic_slug
    *
    * @return View
    */
    public function view($forum_slug, $topic_slug)
    {
        $forum = Forum::where('slug', '=', $forum_slug)->first();

        if (empty($forum)) {
            abort(404, 'Cannot find topic.');
        }

        $topic = Topic::where('forum_id', '=', $forum->id)->where('slug', '=', $topic_slug)->first();

        if (empty($topic)) {
            abort(404, 'Cannot find topic.');
        }

        $replies = Reply::where('topic_id', '=', $topic->id)->orderBy('created_at', 'ASC')->paginate(15);

        // increment views count
        $topic->views++;

        $topic->save();

        // set meta
        $this->theme->set('meta_keywords', $forum->meta_keywords);
        $this->theme->set('meta_description', $forum->meta_description);
        $this->theme->setTitle($topic->name . ' | ' . $forum->name);

        return $this->theme->scope('adaptbb.topics.view', compact('forum', 'topic', 'replies'))->render();
    }

    /**
    * Add
    *
    * @param Request $request
    * @param string $forum_slug
    *
    * @return mixed
    */
    public function add(Request $request, $forum_slug)
    {
        $forum = Forum::where('slug', '=', $forum_slug)->first();

        if (empty($forum)) {
            abort(404, 'Cannot find forum.');
        }

        $model = new Topic;

        if ($request->getMethod() == 'POST') {
            $model->fill($request->except('_token'));

            $model->slug = str_slug($model->name, '-');

            $this->validate($request, [
                'name' => 'required|unique:plugin_adaptbb_topics|max:255',
                'message' => 'required'
            ]);

            $model->forum_id = $forum->id;
            $model->user_id = Auth::user()->id;
            $model->active = 1;
            $model->topic_type = 'normal';

            $model->save();

            // update the forum reply count
            $topic_count = Topic::where('forum_id', '=', $forum->id)->count();

            $forum->topics_count = $topic_count;

            $forum->save();

            return redirect()
                ->route('plugin.adaptbb.topics.view', [ 'forum_slug' => $forum->slug, 'topic_slug' => $model->slug ])
                ->with('success', 'Sweet! Your topic is all done.');
        }

        $this->theme->setTitle('Community Forums - Post a Reply | ' . $forum->name);

        return $this->theme->scope('adaptbb.topics.add', compact('forum', 'model'))->render();
    }

    /**
    * Reply
    *
    * @param Request $request
    * @param integer $id
    *
    * @return string
    */
    public function reply(Request $request, $id)
    {
        // grab the topic
        $topic = Topic::find($id);

        // create the reply
        $model = new Reply;

        $model->fill($request->except('_token'));

        $model->name = 'Re: ' . $topic->name;
        $model->forum_id = $topic->forum_id;
        $model->topic_id = $topic->id;
        $model->user_id = Auth::user()->id;
        $model->active = 1;

        $model->save();

        // update the topic reply count
        $reply_count = Reply::where('topic_id', '=', $topic->id)->count();

        $topic->replies_count = $reply_count;

        $topic->save();

        // update the forum reply count
        $reply_count = Reply::where('forum_id', '=', $topic->forum_id)->count();

        $topic->forum->replies_count = $reply_count;

        $topic->forum->save();

        return response()->json([
            'status' => true,
            'reply' => [
                'id' => $model->id,
                'name' => $model->name,
                'message' => nl2br($model->message),
                'date' => Core::getDateShort($model->created_at),
                'profile_image' => $model->user->getProfileImage('small'),
                'profile_url' => route('users.profile.view', [ 'username' => $model->user->username ]),
                'profile_username' => $model->user->username
            ]
        ]);
    }
}
