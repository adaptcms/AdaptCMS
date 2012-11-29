<?php

class Permission extends AppModel {
	public $name = 'Permission';
    public $hasMany = array(
    	'PermissionValue'
    );
}