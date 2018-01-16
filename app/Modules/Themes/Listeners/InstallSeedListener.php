<?php

namespace App\Modules\Themes\Listeners;

use App\Modules\Core\Events\InstallSeedEvent;

use App\Modules\Themes\Models\Theme;

class InstallSeedListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(InstallSeedEvent $event)
    {
        $theme = new Theme;

        $theme->name = 'Default';
        $theme->user_id = 1;
        $theme->status = 1;
        $theme->custom = 0;

        $theme->save();
    }
}
