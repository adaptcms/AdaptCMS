<?php

namespace App\Modules\Redirection\Models;

use Illuminate\Database\Eloquent\Model;

class Redirects extends Model
{
    protected $table = 'plugin_redirection_redirects';
    protected $fillable = [
        'from_url',
        'to_url'
    ];

}
