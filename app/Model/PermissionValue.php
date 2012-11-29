<?php

class PermissionValue extends AppModel {
	public $name = 'PermissionValue';
	public $belongsTo = array(
        'Permission' => array(
            'className'    => 'Permission',
            'foreignKey'   => 'permission_id'
        ),
        'Role' => array(
            'className'    => 'Role',
            'foreignKey'   => 'role_id'
        )
    );
}