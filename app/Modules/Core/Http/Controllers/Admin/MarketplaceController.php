<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Modules\Plugins\Models\Plugin;
use App\Modules\Themes\Models\Theme;

use Artisan;
use Cache;
use Core;
use Storage;
use Zipper;

class MarketplaceController extends Controller
{
    public function account()
    {
        return view('core::Admin/Marketplace/account');
    }

    public function purchase($id)
    {
        return view('core::Admin/Marketplace/purchase', compact('id'));
    }
}
