<?php

namespace App\Modules\Users\ModelFilters;

use EloquentFilter\ModelFilter;

use App\Modules\Users\Models\User;

class UserFilter extends ModelFilter
{
    public function __construct($query, array $input = [], $relationsEnabled = true)
    {
        parent::__construct($query, $input, $relationsEnabled);

        $this->query->orderBy('created_at', 'DESC');
    }

    public function status($status = null)
    {
        return $this->where('status', '=', $status);
    }

    public function role($role)
    {
        $user_ids = User::hasRoleUserIds($role);
        
        if (!empty($user_ids)) {
            return $query->whereIn('id', $user_ids);
        } else {
            // no user ID's for this role, return empty
            return $query->where('id', '=', '-1');
        }
    }
}
