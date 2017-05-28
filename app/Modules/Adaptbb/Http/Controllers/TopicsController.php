<?php

namespace App\Modules\Adaptbb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\Topic;
use App\Modules\Adaptbb\Models\Reply;
use App\Modules\Core\Services\Core;

use Auth;
use Validator;
use Theme;
use Cache;

class TopicsController extends Controller
{
    public function view($forum_slug, $topic_slug)
    {
        $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        $forum = Forum::where('slug', '=', $forum_slug)->first();
        $topic = Topic::where('forum_id', '=', $forum->id)->where('slug', '=', $topic_slug)->first();
        $replies = Reply::where('topic_id', '=', $topic->id)->orderBy('created_at', 'ASC')->paginate(15);

        // increment views count
        $topic->views++;

        $topic->save();

        // set meta
        $theme->set('meta_keywords', $forum->meta_keywords);
        $theme->set('meta_description', $forum->meta_description);
        $theme->setTitle($topic->name . ' | ' . $forum->name);

        return $theme->scope('adaptbb.topics.view', compact('forum', 'topic', 'replies'))->render();
    }

    public function add(Request $request, $forum_slug)
    {
        $theme = Theme::uses(Cache::get('theme', 'default'))->layout('front');

        $forum = Forum::where('slug', '=', $forum_slug)->first();

        $model = new Topic;

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $model->fill($request->except('_token'));

            $model->slug = str_slug($model->name, '-');

            $validator = Validator::make($model->toArray(), [
                'name' => 'required',
                'message' => 'required',
                'slug' => [
                      'required',
                      Rule::unique('plugin_adaptbb_topics')->ignore($model->id)
                ]
            ]);

            if (!$validator->fails()) {
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
            } else {
                $errors = $validator->errors()->getMessages();
            }
        }

        $theme->setTitle('Community Forums - Post a Reply | ' . $forum->name);

        return $theme->scope('adaptbb.topics.add', compact('forum', 'model', 'errors'))->render();
    }

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

        $core = new Core;

        return response()->json([
            'status' => true,
            'reply' => [
                'id' => $model->id,
                'name' => $model->name,
                'message' => nl2br($model->message),
                'date' => $core->getDateShort($model->created_at),
                'profile_image' => $model->user->getProfileImage('small'),
                'profile_url' => route('users.profile.view', [ 'username' => $model->user->username ]),
                'profile_username' => $model->user->username
            ]
        ]);
    }
}
