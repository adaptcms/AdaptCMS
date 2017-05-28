<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/posts/simple-save',
        'admin/pages/simple-save',
        'admin/categories/simple-save',
        'admin/tags/simple-save',
        'admin/users/simple-save',
        'admin/settings/simple-save',
        'admin/themes/build'
    ];
}
