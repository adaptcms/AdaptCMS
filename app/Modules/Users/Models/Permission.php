<?php

namespace App\Modules\Users\Models;

use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Models\Permission as ParentPermission;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Permission extends ParentPermission implements PermissionContract
{
    use RefreshesPermissionCache;
}
