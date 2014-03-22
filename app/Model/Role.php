<?php
/**
 * Class Role
 *
 * @property Permission $Permission
 * @property User $User
 */
class Role extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_roles'
    */
	public $name = 'Role';

	/**
	* Our validate rules. The Role title must not be empty and must be unique.
	*/
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Role title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Role title has already been used'
			)
        )
    );

    /**
    * Many Users belong to a Role and many permissions belong to a role
    */
	public $hasMany = array(
		'User' => array(
			'dependent' => true
		),
		'Permission' => array(
			'dependent' => true
		)
	);

    /**
     * @var array
     */
    public $actsAs = array(
	    'Slug' => array(
            'slugField' => 'title'
	    ),
	    'Delete'
    );

	/**
	* Before saving, we have some strict rules to adhere to. There must be:
	* one guest role, one member role and one with no default. (for example, admin)
	*
	* If a new Role is added with, say, a setting of the default member group, it removes
	* the flag from the previous one and so on.
	*
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
	{
		if (!empty($this->data['Role']['title'])) {

		}

		if (empty($this->data['Role']['defaults']))
			$this->data['Role']['defaults'] = null;

		if (!isset($this->data['Role']['old_defaults']) &&
			!empty($this->data['Role']['defaults']))
		{
			$this->updateAll(
				array('Role.defaults' => null),
				array('Role.defaults' => $this->data['Role']['defaults'])
			);

			return true;
		}

		if (!empty($this->data['Role']['old_defaults']) && $this->data['Role']['defaults'] != $this->data['Role']['old_defaults'])
		{
			if (empty($this->data['Role']['old_defaults']) && !empty($this->data['Role']['defaults']))
			{
				$find = $this->find('count', array(
					'conditions' => array(
						'defaults' => $this->data['Role']['defaults'],
						'id !=' => $this->data['Role']['id']
					)
				));

				if ($find > 0)
				{
					$this->updateAll(
						array('Role.defaults' => null),
						array('Role.defaults' => $this->data['Role']['defaults'])
					);
				} else {
					return true;
				}
			} elseif (!empty($this->data['Role']['old_defaults']) && empty($this->data['Role']['defaults']))
			{
				$find = $this->find('count', array(
					'conditions' => array(
						'defaults' => $this->data['Role']['old_defaults'],
						'id !=' => $this->data['Role']['id']
					)
				));

				if ($find == 0)
				{
					return false;
				}
			} else {
				$this->updateAll(
					array('Role.defaults' => '"' . $this->data['Role']['old_defaults'] . '"'),
					array(
						'Role.defaults' => $this->data['Role']['defaults'],
						'Role.id !=' => $this->data['Role']['id']
					)
				);			
			}
		}

		return true;
	}

	/**
	* Before Delete
	*
	* Deletion will only occur if there are two roles with a default (member and guest)
	* and one without a default. (admin level at minimum)
	*
	* @param boolean $cascade
	*
	* @return boolean
	*/
	public function beforeDelete($cascade = true)
	{
		$data = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			)
		));

		$find = $this->find('all', array(
			'conditions' => array(
				'defaults' => null,
				'id !=' => $data['Role']['id']
			),
			'limit' => 2
		));
		$count = count($find);

		if (!empty($data['Role']['defaults']) && $count < 2)
		{
			return false;
		} elseif (!empty($data['Role']['defaults']) && $count == 2)
		{
			return false;
		} elseif (empty($data['Role']['defaults']) && $count == 0)
		{
			return false;
		}

		return true;
	}

	/**
	 * Get Category Permission Roles
	 *
	 * @param array $category
	 * @param null $action
	 * @return array
	 */
	public function getCategoryPermissions($category, $action = null)
	{
		$roles = array();

		if (!empty($action)) {
			$actions = array($action);
		} else {
			$actions = array(
				'admin_index',
				'admin_add',
				'admin_edit',
				'admin_delete',
				'admin_restore',
				'view'
			);
		}

		$list = $this->find('all');
		foreach($list as $role) {
			$roles[$role['Role']['id']] = array(
				'name' => $role['Role']['title'],
				'id' => $role['Role']['id']
			);

			foreach($actions as $action) {
				if (empty($category['settings']) || !isset($category['settings']['permissions'][$role['Role']['id']][$action]['any'])) {
					$permission = $this->Permission->find('first', array(
						'conditions' => array(
							'Permission.controller' => 'articles',
							'Permission.action' => $action,
							'Permission.role_id' => $role['Role']['id']
						)
					));

					if (empty($permission)) {
						$roles[$role['Role']['id']][$action] = array(
							'has_access' => false
						);
					} else {
						if ($action == 'admin_add') {
							$permission['Permission']['own'] =  $permission['Permission']['status'];
							$permission['Permission']['any'] =  $permission['Permission']['status'];
						} elseif($action == 'view' && $permission['Permission']['any'] == 2) {
							$permission['Permission']['any'] = $permission['Permission']['status'];
						}

						$roles[$role['Role']['id']][$action] = array(
							'has_access' => true,
							'own' => $permission['Permission']['own'],
							'any' => $permission['Permission']['any']
						);
					}
				} else {
					if (!isset($category['settings']['permissions'][$role['Role']['id']][$action]['own']))
					{
						$category['settings']['permissions'][$role['Role']['id']][$action]['own'] = 2;
					}

					$roles[$role['Role']['id']][$action] = array(
						'has_access' => true,
						'own' => $category['settings']['permissions'][$role['Role']['id']][$action]['own'],
						'any' => $category['settings']['permissions'][$role['Role']['id']][$action]['any']
					);
				}
			}
		}

		return $roles;
	}

	/**
	 * Get Article Permissions
	 *
	 * @param array $article
	 * @return array
	 */
	public function getArticlePermissions($article)
	{
		$roles = array();
		$list = $this->find('all');
		foreach($list as $role) {
			$role_id = $role['Role']['id'];

			if (empty($article['settings']) || !isset($article['settings']['permissions'][$role_id]['view']['status'])) {
				$permission = $this->Permission->find('first', array(
					'conditions' => array(
						'Permission.controller' => 'articles',
						'Permission.action' => 'view',
						'Permission.role_id' => $role['Role']['id'],
						'Permission.status' => 1
					)
				));

				if (empty($permission)) {
					$has_access = false;
				} else {
					$has_access = true;
				}
			} else {
				$has_access = $article['settings']['permissions'][$role_id]['view']['status'];
			}

			$roles[$role['Role']['id']] = array(
				'name' => $role['Role']['title'],
				'id' => $role['Role']['id'],
				'status' => $has_access
			);
		}

		return $roles;
	}
}