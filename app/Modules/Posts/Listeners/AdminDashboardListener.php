<?php

namespace App\Modules\Posts\Listeners;

use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Events\AdminDashboardEvent;

use Cache;

class AdminDashboardListener
{
    /**
     * Handle the event.
     *
     * @param  AdminDashboardEvent  $event
     * @return void
     */
    public function handle(AdminDashboardEvent $event)
    {
        $admin_dashboard_data = json_decode(Cache::get('admin_dashboard_data'), true);

        // posts
        $posts = Post::with('user')->get();

        $admin_dashboard_data['pending_posts'] = [
            'collection' => [],
            'viewPath' => 'posts::Partials/admin_dashboard'
        ];

        foreach($posts as $post) {
            $post->url = $post->getUrlAttribute();
            $post->user->url = $post->user->getUrlAttribute();
        }

        $admin_dashboard_data['pending_posts']['collection'] = $posts;

        Cache::put('admin_dashboard_data', json_encode($admin_dashboard_data), 15);
    }
}
