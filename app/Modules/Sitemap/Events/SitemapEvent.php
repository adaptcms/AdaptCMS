<?php

namespace App\Modules\Sitemap\Events;

use Illuminate\Queue\SerializesModels;

use Cache;

class SitemapEvent
{
    use SerializesModels;

    public $sitemap_data = [];

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
