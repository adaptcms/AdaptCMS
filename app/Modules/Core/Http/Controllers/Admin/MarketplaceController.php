<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Modules\Themes\Models\Theme;
use App\Modules\Plugins\Models\Plugin;

use Storage;
use Core;
use Zipper;
use Cache;
use Artisan;

class MarketplaceController extends Controller
{
    public function account()
    {


        return view('core::Admin/Marketplace/account');
    }

    public function purchase($id)
    {


        return view('core::Admin/Marketplace/purchase');
    }
}
