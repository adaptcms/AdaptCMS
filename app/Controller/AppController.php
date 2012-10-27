<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AppController extends Controller {
	public $components = array(
		'DebugKit.Toolbar' => array(
			'debug' => 2
		),
		'RequestHandler',
		'Auth' => array(
		        'loginAction' => array(
	            'controller' => 'users',
	            'action' => 'login',
	            'admin' => false,
	            'plugin' => false
            ),        
	        'loginRedirect' => array(
	        	'plugin' => false, 
	        	'controller' => 'pages', 
	        	'action' => 'display', 
	        	'home'
	        ),
	        'logoutRedirect' => array(
	        	'plugin' => false, 
	        	'controller' => 'pages', 
	        	'action' => 'display', 
	        	'home'
	        )
        ),
		'Session',
		'Security' => array(
			'csrfExpires' => '+3 hour'
		)
	);

	public $helpers = array('Html', 'Form', 'Time', 'Cache', 'LastFM');
	public $uses = array('PermissionValue', 'Log');

	public function beforeFilter()
	{
		if (file_exists(WWW_ROOT.'installer') && $this->params->controller != "install") {
			$this->redirect(array('controller' => 'install', 'admin' => false));
		}

		parent::beforeFilter();
		$this->Security->blackHoleCallback = 'blackhole';
		$this->Auth->allow();

		$this->Auth->authorize = 'Controller';
        $this->Auth->authenticate = array(
            'all' => array(
                'scope'
            ),
            'Form'
        );
        $this->set('username', $this->Auth->user('username'));

        $this->layout();
		$this->accessCheck();
		$this->moduleLookup();

		$this->theme = 'Default';

		if ($this->Auth->user('id')) {
			$this->logAction();
		}

		if ($this->RequestHandler->isAjax() || $this->RequestHandler->isRss()) {
			Configure::write('debug',0);
		}

		// Number of Items Per Page
		if ($this->params->action == "admin_index") {
			$this->loadModel('SettingValue');
			if ($limit = $this->SettingValue->findByTitle('Number of Items Per Page')) {
				$this->pageLimit = $limit['SettingValue']['data'];
			} else {
				$this->pageLimit = 10;
			}
		}
	}

	public function layout($layout = null, $prefix = null)
	{
		if (!empty($layout)) {
			$this->layout = $layout;
			if (!empty($prefix)) {
				$this->set('prefix', $prefix);
			} else {
				$this->set('prefix', 'admin');
			}
		} else {
			if (!empty($this->params->prefix) && $this->params->prefix == "admin" or 
				!empty($this->params->pass) && $this->params->pass[0] == "admin") {
				$this->layout = "admin";
				$this->set('prefix', 'admin');
			} elseif (!empty($this->params->prefix) && $this->params->prefix == "rss") {
				$this->layout = "rss/default";
			} else {
				$this->layout = "default";
			}
		}
	}

	public function accessCheck()
	{
		if ($this->Auth->user('role_id')) {
			$role_id = $this->Auth->user('role_id');
		} else {
			$this->loadModel('Role');
			if ($role = $this->Role->findByDefaults('default-guest')) {
				$role_id = $role['Role']['id'];
			} else {
				$role_id = null;
			}
		}

		if (strstr($this->params->action, "login") or strstr($this->params->action, "activate") or strstr($this->params->action, "logout") 
			or strstr($this->params->action, "register") or strstr($this->params->action, "_password")
			or !empty($this->params->pass[0]) && strstr($this->params->pass[0], "denied")
			or !empty($this->params->pass[0]) && strstr($this->params->pass[0], "home") or strstr($this->params->action, "ajax")
			|| !empty($this->params->prefix) && $this->params->prefix == "rss" || $this->params->controller == 'install') {
				$this->Auth->allow($this->params->action);
		} elseif (!empty($this->params->prefix) && $this->params->prefix == "admin" && !$this->Auth->User('id')
			|| !empty($this->params->pass) && $this->params->pass[0] == "admin" && !$this->Auth->User('id')
			) {
				$this->Auth->deny($this->params->action);
		} elseif ($role_id) {
			if (empty($this->params->plugin)) {
				$this->params->plugin = null;
			}

			if (strstr($this->params->url, "admin")) {
				$permission1 = $this->PermissionValue->find('first', array(
					'conditions' => array(
						'PermissionValue.role_id' => $role_id,
						'PermissionValue.action' => 1,
						'PermissionValue.pageAction LIKE' => '%admin%'
						),
					'fields' => array(
						'PermissionValue.action'
						)
					)
				);
				$permission[0] = $permission1;
			} else {
				if (!empty($this->params->pass[0]) && is_numeric($this->params->pass[0])) {
					$permission1 = $this->PermissionValue->find('first', array(
						'conditions' => array(
							'PermissionValue.role_id' => $role_id,
							'PermissionValue.pageAction' => $this->params->action,
							'PermissionValue.controller' => $this->params->controller,
							'PermissionValue.plugin' => $this->params->plugin,
							'PermissionValue.type' => 'individual',
							'PermissionValue.action_id' => $this->params->pass[0]
							),
						'fields' => array(
							'PermissionValue.action'
							)
						)
					);

					if (empty($permission1)) {
						$permission1 = $this->PermissionValue->find('first', array(
							'conditions' => array(
								'PermissionValue.role_id' => $role_id,
								'PermissionValue.pageAction' => $this->params->action,
								'PermissionValue.controller' => $this->params->controller,
								'PermissionValue.plugin' => $this->params->plugin,
								'PermissionValue.type' => 'default',
								),
							'fields' => array(
								'PermissionValue.action'
								)
							)
						);
					}

				} else {
					$permission1 = $this->PermissionValue->find('first', array(
						'conditions' => array(
							'PermissionValue.role_id' => $role_id,
							'PermissionValue.pageAction' => $this->params->action,
							'PermissionValue.controller' => $this->params->controller,
							),
						'fields' => array(
							'PermissionValue.action'
							)
						)
					);
				}

				$permission[0] = $permission1;
			}

			if (!empty($permission[0]['PermissionValue']['action']) && $permission[0]['PermissionValue']['action'] == 0) {
				$this->denyRedirect();
				$this->Auth->deny($this->params->action);
			} elseif (empty($permission[0]['PermissionValue']['action'])) {
				$this->denyRedirect();
				$this->Auth->deny($this->params->action);
			} elseif ($permission[0]['PermissionValue']['action'] == 1) {
				$this->Auth->allow($this->params->action);
			}
		}
	}

	public function isAuthorized($user = NULL) {
        if ($this->params['prefix'] === 'admin') {
            return true;
        }
        return true;
    }

    public function denyRedirect()
    {
    	die(debug($this->params));
    	$this->redirect(array(
    		'plugin' => false,
    		'admin' => false, 
    		'controller' => 'Pages', 
    		'action' => 'display', 
    		'denied'
    		)
    	);
    }

	public function slug($str, $orig = null) {
		if ($orig == null) {
			return strtolower(Inflector::slug($str, "-"));
		} else {
			return strtolower(Inflector::slug($str));
		}
	}

	public function blackhole($type) {
		// debug($this->params);
    	// die(debug($type));
	}

	public function logAction()
	{
		if (!empty($this->params['pass'][0])) {
			$action_id = $this->params['pass'][0];
		} else {
			$action_id = 0;
		}

		$log_insert['Log'] = array(
			// 'data' => json_encode($this->params),
			'user_id' => $this->Auth->user('id'),
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'plugin' => $this->params->plugin,
			'controller' => $this->params->controller,
			'action' => $this->params->action,
			'action_id' => $action_id,
			'date' => date('Y-m-d H:i:s')
		);
		// $this->Log->save($log_insert);
	}

	public function moduleLookup()
	{
		if ($this->params->prefix != "admin") {
			$this->loadModel('Module');

			if (!empty($this->params['pass'][0])) {
				$location = $this->params->controller.'|'.$this->params->action.'|'.$this->params['pass'][0];
				$location2 = $this->params->controller.'|'.$this->params->action;

				$module_cond = array(
					'conditions' => array(
						'OR' => array(
							array('Module.location LIKE' => '%"*"%'),
							array('Module.location LIKE' => '%"' . $location . '"%'),
							array('Module.location LIKE' => '%"' . $location2 . '"%')
						),
						'Module.deleted_time' => '0000-00-00 00:00:00'
					),
					'contain' => array(
						'ComponentModel'
					)
				);
			} else {
				$location = $this->params->controller.'|'.$this->params->action;
				$module_cond = array(
					'conditions' => array(
						'OR' => array(
							array('Module.location LIKE' => '%"*"%'),
							array('Module.location LIKE' => '%"' . $location . '"%')
						),
						'Module.deleted_time' => '0000-00-00 00:00:00'
					),
					'contain' => array(
						'ComponentModel'
					)
				);
			}

			$data = $this->Module->find('all', $module_cond);

			if (!empty($data)) {
				$module_data = array();
				$models = array();

				foreach($data as $row) {
					if ($row['ComponentModel']['is_plugin'] == 1) {
						$model = $row['ComponentModel']['model_title'];
						$this->loadModel(
							str_replace(' ','',$row['ComponentModel']['title']).'.'.$model
						);
					} else {
						$model = $row['ComponentModel']['model_title'];
						$this->loadModel($model);
					}

					$module_data
						[$row['Module']['title']] = $this->$model->getModuleData($row['Module']);
				}
			}

			$this->set(compact('module_data'));
		}
	}
}
