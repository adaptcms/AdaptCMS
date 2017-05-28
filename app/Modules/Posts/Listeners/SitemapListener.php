<?php

namespace App\Modules\Posts\Listeners;

use App\Modules\Sitemap\Events\SitemapEvent;

use App\Modules\Posts\Models\Post;

use Cache;

class SitemapListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SitemapEvent $event)
    {

    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(SitemapEvent $event)
    {
        $sitemap_data = json_decode(Cache::get('sitemap_data'), true);

        $posts = Post::where('status', '=', 1)->get();

        foreach($posts as $post) {
            $sitemap_data[] = [
                'url' => route('posts.view', [ 'slug' => $post->slug ]),
                'date' => $post->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        Cache::put('sitemap_data', json_encode($sitemap_data), 5);
    }
}
