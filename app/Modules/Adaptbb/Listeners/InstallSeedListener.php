<?php

namespace App\Modules\Adaptbb\Listeners;

use App\Modules\Core\Events\InstallSeedEvent;

use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\ForumCategory;
use App\Modules\Adaptbb\Models\Topic;
use App\Modules\Adaptbb\Models\Reply;

class InstallSeedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(InstallSeedEvent $event)
    {

    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(InstallSeedEvent $event)
    {
        // create categories
        $categories = [
            [
                'name' => 'General'
            ],
            [
                'name' => 'Random'
            ]
        ];
        foreach($categories as $index => $category) {
            $model = new ForumCategory;

            $model->name = $category['name'];
            $model->slug = str_slug($model->name, '-');
            $model->ord = $index;

            $model->save();
        }

        // create forums
        $forums = [
            [
                'name' => 'Website',
                'description' => 'Talk about anything related to the site.',
                'category_id' => 1
            ],
            [
                'name' => 'Off-Topic',
                'description' => 'Politics, Video games, etc.',
                'category_id' => 2
            ]
        ];
        foreach($forums as $index => $forum) {
            $model = new Forum;

            $model->name = $forum['name'];
            $model->slug = str_slug($model->name, '-');
            $model->ord = $index;
            $model->description = $forum['description'];
            $model->meta_description = $forum['meta_description'];
            $model->category_id = $forum['category_id'];

            $model->save();
        }

        // create topic
        $topic = [
            'name' => 'Welcome to AdaptBB!',
            'message' => 'Hello and welcome to your new forums software, we hope you enjoy it and never hesitate to reach out for help.'
        ];

        $model = new Topic;

        $model->name = $topic['name'];
        $model->slug = str_slug($model->name, '-');
        $model->message = $topic['message'];
        $model->topic_type = 'normal';
        $model->active = 1;
        $model->forum_id = 1;
        $model->user_id = 1;

        $model->save();

        // create reply
        $model = new Reply;

        $model->name = 'Re: ' . $topic['name'];
        $model->message = 'This is a test reply.';
        $model->active = 1;
        $model->topic_id = 1;
        $model->forum_id = 1;
        $model->user_id = 1;

        $model->save();
    }
}
