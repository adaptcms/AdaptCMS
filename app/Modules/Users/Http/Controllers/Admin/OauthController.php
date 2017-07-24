<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Modules\Users\Models\User;

class OauthController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => '3',
            'redirect_uri' => route('admin.oauth.callback'),
            'response_type' => 'code',
            'scope' => 'account paid-extensions'
        ]);

        return redirect('http://server.local/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        dd($request->all());

        $http = new Client;

        $response = $http->post('http://your-app.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => 'client-id',
                'client_secret' => 'client-secret',
                'redirect_uri' => route('admin.oauth.callback'),
                'code' => $request->code,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
