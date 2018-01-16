<?php

namespace App\Modules\Sitemap\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Sitemap\Events\SitemapEvent;

use Cache;
use Core;
use Sitemap;

class SitemapController extends Controller
{
    /**
    * Index
    *
    * @param string|null $module
    *
    * @return string
    */
    public function index($module = null)
    {
        Core::debugDisable();

        Cache::put('sitemap_data', json_encode([]), 5);

        event(new SitemapEvent());

        $data = json_decode(Cache::get('sitemap_data'), true);

        foreach($data as $row) {
            Sitemap::addTag($row['url'], $row['date']['date'], $row['frequency'], $row['importance']);
        }

        Cache::forget('sitemap_data');

        return Sitemap::render();
    }

    /**
    * Url List
    *
    * @return string
    */
    public function urlList()
    {
        Core::debugDisable();

        Cache::put('sitemap_data', json_encode([]), 5);

        event(new SitemapEvent());

        $data = json_decode(Cache::get('sitemap_data'), true);

        $urlList = [];
        foreach($data as $row) {
            $urlList[] = $row['url'];
        }

        Cache::forget('sitemap_data');

        return implode(PHP_EOL, $urlList);
    }
}
