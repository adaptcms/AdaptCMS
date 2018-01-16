<?php

namespace App\Modules\Adaptbb\Listeners;

use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\Topic;
use App\Modules\Sitemap\Events\SitemapEvent;

use Cache;

class SitemapListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(SitemapEvent $event)
    {
        $sitemap_data = json_decode(Cache::get('sitemap_data'), true);

        // forums
        $forums = Forum::all();

        foreach($forums as $forum) {
            $sitemap_data[] = [
                'url' => route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]),
                'date' => $forum->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        // topics
        $topics = Topic::all();

        foreach($topics as $topic) {
            $sitemap_data[] = [
                'url' => route('plugin.adaptbb.topics.view', [
                    'forum_slug' => $topic->forum->slug,
                    'topic_slug' => $topic->slug
                ]),
                'date' => $topic->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        Cache::put('sitemap_data', json_encode($sitemap_data), 5);
    }
}
