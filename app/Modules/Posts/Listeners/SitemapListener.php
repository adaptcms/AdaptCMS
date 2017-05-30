<?php

namespace App\Modules\Posts\Listeners;

use App\Modules\Sitemap\Events\SitemapEvent;

use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\Page;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Tag;

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

        // posts
        $posts = Post::where('status', '=', 1)->get();

        foreach($posts as $post) {
            $sitemap_data[] = [
                'url' => route('posts.view', [ 'slug' => $post->slug ]),
                'date' => $post->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        // pages
        $pages = Page::where('status', '=', 1)->get();

        foreach($pages as $page) {
            $sitemap_data[] = [
                'url' => route('pages.view', [ 'slug' => $page->slug ]),
                'date' => $page->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        // categories
        $categories = Category::all();

        foreach($categories as $category) {
            $sitemap_data[] = [
                'url' => route('categories.view', [ 'slug' => $category->slug ]),
                'date' => $category->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        // tags
        $tags = Tag::all();

        foreach($tags as $tag) {
            $sitemap_data[] = [
                'url' => route('tags.view', [ 'slug' => $tag->slug ]),
                'date' => $tag->updated_at,
                'frequency' => 'daily',
                'importance' => '0.5'
            ];
        }

        Cache::put('sitemap_data', json_encode($sitemap_data), 5);
    }
}
