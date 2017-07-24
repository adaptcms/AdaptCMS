<?php

namespace App\Modules\Users\Models;

use Spatie\Permission\Models\Role as ParentRole;
use Spatie\Permission\Traits\HasPermissions;

class Role extends ParentRole
{
    use HasPermissions;

    public $core_role_levels = [
        'admin' => 4,
        'editor' => 3,
        'demo' => 2,
        'member' => 1
    ];

    public $core_level_roles = [
        1 => 'member',
        2 => 'demo',
        3 => 'editor',
        4 => 'admin'
    ];

    public function scopeBySlug($query, $slug)
    {
        return $query->where('core_role', '=', 1)->where('name', '=', $slug)->first();
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('core_role', '=', 1)->where('level', '=', $level)->first();
    }
}
