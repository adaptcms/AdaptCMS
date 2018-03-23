<?php

namespace App\Modules\Users\Models;

use Spatie\Permission\Models\Role as ParentRole;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Role extends ParentRole
{
    use HasPermissions,
        RefreshesPermissionCache;

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

    /**
    * Scope By Slug
    *
    * @param object $query
    * @param string $slug
    *
    * @return object
    */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('core_role', '=', 1)->where('name', '=', $slug)->first();
    }

    /**
    * Scope By Level
    *
    * @param object $query
    * @param integer $level
    *
    * @return object
    */
    public function scopeByLevel($query, $level)
    {
        return $query->where('core_role', '=', 1)->where('level', '=', $level)->first();
    }
    
    /**
    * Add
    *
    * @param array $postArray
    *
    * @return Role
    */
    public function add($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->level = $postArray['level'];
        $this->guard_name = 'web';
        $this->core_role = 0;
        $this->redirect_route_name = !empty($postArray['redirect_route_name']) ? $postArray['redirect_route_name'] : 'home';
        
        $this->save();
        
        return $this;
    }
    
    /**
    * Edit
    *
    * @param array $postArray
    *
    * @return Role
    */
    public function edit($postArray = [])
    {
        $this->name = $postArray['name'];
        $this->level = $postArray['level'];
        $this->redirect_route_name = !empty($postArray['redirect_route_name']) ? $postArray['redirect_route_name'] : 'home';
        
        $this->save();
        
        return $this;
    }

    /**
    * Get Roles List
    *
    * @return array
    */
    public function getRolesList()
    {
        return Role::pluck('name', 'id');
    }
}
