<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
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
	        'loginRedirect' => array('plugin' => false, 'controller' => 'pages', 'action' => 'display', 'home'),
	        'logoutRedirect' => array('plugin' => false, 'controller' => 'pages', 'action' => 'display', 'home')
        ),
		'Session',
		'Security' => array(
			'csrfExpires' => '+3 hour'
		)
	);

	public $helpers = array('Html', 'Form', 'Time', 'Cache');
	public $uses = array('PermissionValue', 'Log');

	public function beforeFilter()
	{
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

		$this->theme = 'Movie';

		if ($this->Auth->user('id')) {
			$this->logAction();
		}

		if ($this->RequestHandler->isAjax()) {
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
			or !empty($this->params->pass[0]) && strstr($this->params->pass[0], "home") or strstr($this->params->action, "ajax")) {
				$this->Auth->allow($this->params->action);
		} elseif (!empty($this->params->prefix) && $this->params->prefix == "admin" && !$this->Auth->User('id') or 
			!empty($this->params->pass) && $this->params->pass[0] == "admin" && !$this->Auth->User('id')) {
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
}
