<?php

namespace App\Modules\Redirection\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Redirection\Models\Redirects;

use URL;
use Redirect;

class RedirectsController extends Controller
{
    public function redirect(Request $request)
    {
        $from_url = str_replace(URL::to('/'), '', $request->url());

        $redirect = Redirects::where('from_url', '=', $from_url)->first();

        if (empty($redirect)) {
            abort(404);
        }

        return Redirect::away($redirect->to_url, 301);
    }
}
