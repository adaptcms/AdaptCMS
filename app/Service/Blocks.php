<?php
App::uses('CakeSession', 'Model/Datasource');
App::uses('ClassRegistry', 'Utility');
/**
 * Class Blocks
 *
 * @property Block $Block
 * @property SessionHelper $Session
 */
class Blocks
{
	/**
	 * @var array
	 */
	private $blocks = array();

	private $role;

	private $Block;

	public function __construct()
	{
		$this->Block = ClassRegistry::init('Block');

		if (!Configure::read('User.role')) {
			$this->setRole();
		} else {
			$this->role = Configure::read('User.role');
		}
	}

	/**
	 * Block Lookup
	 * Looks up any blocks that should run on page and loads them
	 *
	 * @param string $block
	 * @param string $type
	 * @return array
	 */
	public function blockLookup($block, $type = 'data')
	{
		$block_lookup = $this->getBlock($block);
		if (empty($block_lookup[$type])) {
			if (empty($block_lookup['block'])) {
				$data = $this->Block->find('first', array(
					'conditions' => array(
						'Block.title' => $block
					),
					'contain' => array(
						'Module'
					)
				));

				if (!empty($data['Block']))
					$block_data['block'] = $data['Block'];

				if (!empty($data['Module']))
					$block_data['block']['Module'] = $data['Module'];

				if (!empty($block_data['block'])) {
					$data = $block_data['block'];
				} else {
					$data = array();
				}
			} else {
				$data = $block_lookup['block'];
			}

			$block_data = array();
			$block_permissions = array();
			if (!empty($data['type']))
			{
				if (!empty($data['settings']))
				{
					$settings = json_decode($data['settings']);

					if (!empty($settings)) {
						foreach($settings as $key => $val)
						{
							$data[$key] = $val;
						}
					}

					unset($data['settings']);
				}

				if ($data['type'] == "dynamic")
				{
					if ($type == 'permissions') {
						$permissions = $this->Block->Module->Permission->find('first', array(
							'conditions' => array(
								'Permission.module_id' => $data['Module']['id'],
								'Permission.action NOT LIKE' => '%admin%',
								'Permission.role_id' => $this->getRole()
							),
							'order' => 'Permission.related DESC',
							'limit' => 1
						));

						if (!empty($permissions))
							$block_permissions = $this->getRelatedPermissions($permissions);
					} else {
						if ($data['Module']['is_plugin'] == 1)
						{
							$model = $data['Module']['model_title'];
							$this->$model = ClassRegistry::init(
								str_replace(' ','',$data['Module']['title']).'.'.$model
							);
						} else {
							$model = $data['Module']['model_title'];
							$this->$model = ClassRegistry::init($model);
						}

						if (method_exists($this->$model, 'getBlockData'))
						{
							$block_data = $this->$model->getBlockData(
								$data,
								CakeSession::read('Auth.User.id')
							);
						}
					}
				} elseif (!empty($data['data'])) {
					$block_data = $data['data'];
				}
			}

			$this->setBlock($block, array(
				'block' => $data,
				'data' => $block_data,
				'permissions' => $block_permissions
			));
		}

		return $this->getBlock($block);
	}

	public function getRelatedPermissions($permission, $controller = null)
	{
		if (!empty($permission))
		{
			$data = array();

			if (!empty($permission['Permission']['controller']))
			{
				$controller = $permission['Permission']['controller'];
			}

			if (is_array($permission) && empty($permission['Permission']))
			{
				foreach($permission as $row)
				{
					$data[] = $this->getRelatedPermissions($row);
				}

				return $data;
			}

			if (!empty($permission['Permission']['related']))
			{
				$related_values = json_decode($permission['Permission']['related'], true);
				$related = array();

				$values = array();

				if (!empty($related_values))
				{
					foreach($related_values as $key => $val)
					{
						$action = $val['action'][0];
						$controller = !empty($val['controller'][0]) ? $val['controller'][0] : $controller;

						$related['related'][$controller][$action] = array();

						$values['OR'][$key]['AND'] = array(
							'Permission.action' => $action,
							'Permission.controller' => $controller,
							'Permission.status' => 1
						);
					}
				}

				$new_related['related'] = $this->Block->User->Role->Permission->find('all', array(
					'conditions' => array(
						'Permission.role_id' => $this->getRole(),
						$values
					)
				));

				foreach($new_related['related'] as $key => $row)
				{
					$related['related']
					[$row['Permission']['controller']]
					[$row['Permission']['action']] = $row['Permission'];
					unset($new_related['related'][$key]);
				}

				$related['related'] = array_merge($new_related['related'], $related['related']);

				$permissions = array_merge(
					$permission['Permission'],
					$related
				);
			} else {
				$permissions = $permission['Permission'];
			}

			return !empty($permissions) ? $permissions : array();
		} else {
			return false;
		}
	}

	/**
	 * Set Block
	 *
	 * @param $block
	 * @param $data
	 * @return void
	 */
	public function setBlock($block, $data)
	{
		$this->blocks[$block] = $data;
	}

	/**
	 * Get Block
	 *
	 * @param $block
	 * @return array
	 */
	public function getBlock($block)
	{
		if (!empty($this->blocks[$block])) {
			return $this->blocks[$block];
		} else {
			return array();
		}
	}

	/**
	 * Get Blocks
	 *
	 * @return array
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 * Gets ID of role or null if none
	 *
	 * @return integer or null on false
	 */
	public function getRole()
	{
		return $this->role;
	}

	public function setRole()
	{
		if (CakeSession::read('Auth.User.role_id')) {
			$this->role = CakeSession::read('Auth.User.role_id');
		} else {
			$role = $this->Block->User->Role->findByDefaults('default-guest');

			if (!empty($role)) {
				$this->role = $role['Role']['id'];
			} else {
				$this->role = null;
			}
		}

		return $this->role;
	}
}