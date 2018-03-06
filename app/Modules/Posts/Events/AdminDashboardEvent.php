<?php

namespace App\Modules\Posts\Events;

use Illuminate\Queue\SerializesModels;

use Cache;

class AdminDashboardEvent
{
    use SerializesModels;

    public $admin_dashboard_data = [];

    /**
     * Create a new event instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct()
    {
        
    }
}
