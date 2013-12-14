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

	/**
	 * After Find
	 *
	 * @param mixed $results
	 * @param bool $primary
	 * @return mixed
	 */
	public function afterFind($results, $primary = false)
	{
		if (!empty($results)) {
			foreach($results as $key => $result) {
				$action = $result['Permission']['action'];

				if (strstr($action, '_edit')) {
					$own_label = 'Edit Own';
					$any_label = 'Edit Any';
				} elseif (strstr($action, '_delete')) {
					$own_label = 'Delete Own';
					$any_label = 'Delete Any';
				} elseif (strstr($action, '_restore')) {
					$own_label = 'Restore Own';
					$any_label = 'Restore Any';
				} elseif (strstr($action, '_index')) {
					$own_label = 'List Own';
					$any_label = 'List Any';
				}

				if (empty($own_label)) {
					$own_label = 'Own';
					$any_label = 'Any';
				}

				$results[$key]['Permission']['own_label'] = $own_label;
				$results[$key]['Permission']['any_label'] = $any_label;
			}
		}

		return $results;
	}
}