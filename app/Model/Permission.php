<?php
/**
 * Class Permission
 *
 * @property Module $Module
 * @property Role $Role
 */
class Permission extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_categories'
    */
	public $name = 'Permission';

    /**
    * Each permission belongs to a Role and (optionally) a Module
    */
	public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id'
        ),
        'Module' => array(
        	'className' => 'Module',
        	'foreignKey' => 'module_id'
        )
    );
}