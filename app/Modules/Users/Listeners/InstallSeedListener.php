<?php

namespace App\Modules\Users\Listeners;

use App\Events\InstallSeedEvent;

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
                'user_id' => 1,
                'level' => 1
            ],
            [
                'name' => 'Editor',
                'user_id' => 1,
                'level' => 2
            ],
            [
                'name' => 'Admin',
                'user_id' => 1,
                'level' => 3
            ]
        ];
        foreach($roles as $role) {
            $model = new Role;

            $model->name = $role['name'];
            $model->slug = str_slug($model->name, '-');
            $model->user_id = $role['user_id'];
            $model->level = $role['level'];

            $model->save();
        }
    }
}
