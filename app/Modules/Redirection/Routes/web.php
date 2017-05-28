<?php
try {
    $tmp_redirects = \App\Modules\Redirection\Models\Redirects::all();

    if (!empty($tmp_redirects)) {
        $redirects = [];
        foreach($tmp_redirects as $redirect) {
            $redirects[$redirect->from_url] = $redirect->to_url;
        }

        $url_match = str_replace(URL::to('/'), '', Request::url());

        if (isset($redirects[$url_match])) {
            Route::get($url_match, [
                'uses' => '\App\Modules\Redirection\Http\Controllers\RedirectsController@redirect'
            ]);
        }
    }
} catch(\Exception $e) {
    // do nothing
}
