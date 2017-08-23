<?php

namespace App\Modules\Users\Listeners;

use App\Modules\Core\Events\InstallSeedEvent;

use App\Modules\Users\Models\Role;

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
        // create roles
        $roles = [
            [
                'name' => 'Member',
                'level' => 1,
                'redirect_route_name' => 'home'
            ],
            [
                'name' => 'Editor',
                'level' => 3,
                'redirect_route_name' => 'admin.dashboard'
            ],
            [
                'name' => 'Admin',
                'level' => 4,
                'redirect_route_name' => 'admin.dashboard'
            ],
            [
            	'name' => 'Demo',
            	'level' => 2,
            	'redirect_route_name' => 'admin.dashboard'
            ]
        ];
        foreach($roles as $role) {
            $model = new Role;

            $model->name = str_slug($role['name'], '-');
            $model->guard_name = 'web';
            $model->level = $role['level'];
            $model->core_role = 1;
            $model->redirect_route_name = $role['redirect_route_name'];

            $model->save();
        }
    }
}
