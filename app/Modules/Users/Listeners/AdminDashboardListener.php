<?php

namespace App\Modules\Users\Listeners;

use App\Modules\Users\Models\User;
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

        // pending users
        $users = User::where('status', '=', 0)->get();

        $admin_dashboard_data['pending_users'] = [
            'collection' => collect([]),
            'viewPath' => 'users::Partials/admin_dashboard'
        ];
        foreach($users as $user) {
            $user->url = route('users.profile.view', [ 'username' => $user->username ]);

            $admin_dashboard_data['pending_users']['collection']->push($user);
        }

        Cache::put('admin_dashboard_data', json_encode($admin_dashboard_data), 15);
    }
}
