<?php

namespace App\Modules\Adaptbb\Listeners;

use App\Modules\Adaptbb\Models\Topic;
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

        // most recent forum topics
        $topics = Topic::with('replies')->where('active', '=', 1)->orderBy('created_at', 'DESC')->take(5)->get();

        $admin_dashboard_data['most_recent_forum_topics'] = [
            'collection' => collect([]),
            'viewPath' => 'adaptbb::Partials/admin_dashboard'   
        ];
        foreach($topics as $topic) {
            $topic->url = $topic->getUrl();

            $admin_dashboard_data['most_recent_forum_topics']['collection']->push($topic);
        }

        Cache::put('admin_dashboard_data', json_encode($admin_dashboard_data), 15);
    }
}
