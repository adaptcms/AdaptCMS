<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Model', 'ConnectionManager');

/**
 * Class AppController
 * @property Cron $Cron
 * @property Module $Module
 * @property Permission $Permission
 * @property Role $Role
 * @property redirect $redirect
 * @property params $params
 */
class AppController extends Controller
{
    /**
     * Array of necessary components. DebugKit, Auth, Sesson and Security - by default.
     * 
     * @var array 
     */
    public $components = array(
        // 'DebugKit.Toolbar',
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

    /**
     * Array of Default helpers
     * 
     * @var array
     */
    public $helpers = array(
        'Html', 
        'Form', 
        'Time', 
        'Cache', 
        'AutoLoadJS',
        'Admin'
    );
    public $cacheAction = '1 day';

    /**
     * Will hold the permissions later on
     * 
     * @var string
     */
    private $permissions;

    /**
     * @var mixed
     */
    private $role;

    /**
     * A whole lot is going on in this one. We look for and attempt to load components/helpers, call Auth/Authorize,
     * load the layout, run the accessCheck, run the cron, blocks lookup, 
     * 
     * @return null
     */
    public function beforeFilter()
    {
//        echo '#1: ' . microtime() . '<br />';

        $system_path = realpath(CACHE . '/../system/');

        /*
        * Loads Up Components from JSON file
        */
        if (file_exists($system_path . '/components.json'))
        {
            $components_array = json_decode( file_get_contents($system_path . '/components.json'), true );

            foreach($components_array as $key => $component)
            {
                if (is_numeric($key))
                {
                    $this->$component = $this->Components->load($component);
                } elseif (strstr($key, '.')) {
                    $componentName = explode('.', $key);
                    $name = $componentName[1];

                    $this->$name = $this->Components->load($key, $component);
                } else {
                    $this->$key = $this->Components->load($key, $component);
                }
            }
        }

        /*
        * Loads Up Helpers from JSON file
        */
        if (file_exists($system_path . '/helpers.json'))
        {
            $helpers_array = json_decode( file_get_contents($system_path . '/helpers.json'), true );

            foreach($helpers_array as $key => $helper)
            {
                if (is_numeric($key))
                {
                    $this->helpers[] = $helper;
                } else {
                    $this->helpers[] = $key;
                }
            }
        }

        if ($this->params->controller != "install") {
            try {
                $db = ConnectionManager::getDataSource('default');
            } catch (Exception $e) {
                $this->redirect(array(
                    'controller' => 'install',
                    'action' => 'index',
                    'admin' => false,
                    'plugin' => null
                ));
            }
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

        $this->loadModel('Permission');

        if (!$this->getRole())
            $this->setRole();

//        echo '#2: ' . microtime() . '<br />';

        $this->blocksLookup();
//        echo '#3: ' . microtime() . '<br />';
        $this->accessCheck();
//        echo '#4: ' . microtime() . '<br />';
        $this->runCron();

        $this->loadModel('SettingValue');

        if ($cache_theme = Cache::read('Global.theme'))
        {
            $this->theme = $cache_theme;
        }
        else
        {
            if ($theme = $this->SettingValue->findByTitle('default-theme')) {
                $this->theme = $theme['SettingValue']['data'];
            }
            else
            {
                $this->theme = 'Default';
            }
        }

        if ($this->Auth->user('id')) {
            $current_user = $this->Auth->user();

            if (!empty($current_user['settings']))
            {
                $current_user['data'] = json_decode($current_user['settings'], true);
            }

            $this->set(compact('current_user'));
        }

        if ($this->request->is('ajax') || $this->request->is('rss'))
            Configure::write('debug', 0);

        // Number of Items Per Page
        if ($this->params->action == "admin_index") {
            if ($limit = $this->SettingValue->findByTitle('Number of Items Per Page')) {
                    $this->pageLimit = $limit['SettingValue']['data'];
            } else {
                    $this->pageLimit = 10;
            }
        }
//        echo '#5: ' . microtime() . '<br />';
    }

    /**
     * Loads the necessary layout
     * 
     * @param string $layout
     * @param string $prefix
     * @return void
     */
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
                $this->params->action == "admin" && strtolower($this->params->controller) != "users") {
                $this->layout = "admin";
                $this->set('prefix', 'admin');
            } elseif (!empty($this->params->prefix) && $this->params->prefix == "rss") {
                $this->layout = "rss/default";
            } else {
                $this->layout = "default";
            }
        }
    }

    /**
     * Access Check
     * 
     * @return void
     */
    public function accessCheck()
    {
        $webroot_exts = array(
            'json',
            'css',
            'js',
            'less',
            'xml'
        );

        if (!empty($this->allowedActions) && in_array($this->params->action, $this->allowedActions)) {
            $allowed = 1;
        } elseif (strstr($this->params->action, "login") or strstr($this->params->action, "activate") or strstr($this->params->action, "logout") 
            or strstr($this->params->action, "register") or strstr($this->params->action, "_password")
            or !empty($this->params->pass[0]) && strstr($this->params->pass[0], "denied")
            or !empty($this->params->pass[0]) && strstr($this->params->pass[0], "home")
            || !empty($this->params->prefix) && $this->params->prefix == "rss" || $this->params->controller == 'install' && !strstr($this->params->action, 'plugin') ||
            !empty($this->params->ext) && in_array($this->params->ext, $webroot_exts)) {
                        $this->Auth->allow($this->params->action);
        } elseif (!empty($this->params->prefix) && $this->params->prefix == "admin" && !$this->Auth->User('id')
            || $this->params->action == "admin" && !$this->Auth->User('id')
            && strtolower($this->params->controller) != "users"
            ) {
                $this->Auth->deny($this->params->action);
        } elseif ($this->getRole()) {
            if (empty($this->params->plugin)) {
                $this->params->plugin = '';
            }

            if ($this->params->action == "admin" && $this->params->controller == "pages") {
                $permission = $this->Permission->find('first', array(
                    'conditions' => array(
                        'Permission.role_id' => $this->getRole(),
                        'Permission.status' => 1,
                        'Permission.action LIKE' => '%admin%'
                    )
                ));
            } else {
                if (!empty($this->params->pass[0]) && is_numeric($this->params->pass[0])) {
                    $permission = $this->Permission->find('first', array(
                        'conditions' => array(
                            'Permission.role_id' => $this->getRole(),
                            'Permission.action' => $this->params->action,
                            'Permission.controller' => $this->params->controller,
                            'Permission.plugin' => $this->params->plugin,
                            'Permission.action_id' => $this->params->pass[0]
                        )
                    ));

                    if (empty($permission)) {
                        $permission = $this->Permission->find('first', array(
                            'conditions' => array(
                                'Permission.role_id' => $this->getRole(),
                                'Permission.action' => $this->params->action,
                                'Permission.controller' => $this->params->controller,
                                'Permission.plugin' => $this->params->plugin
                            )
                        ));
                    }
                } else {
                    $permission = $this->Permission->find('first', array(
                        'conditions' => array(
                            'Permission.role_id' => $this->getRole(),
                            'Permission.action' => $this->params->action,
                            'Permission.controller' => $this->params->controller
                        )
                    ));
                }
            }

            $this->permissions = $this->getRelatedPermissions($permission);
            $this->set('permissions', $this->permissions);

            if (isset($permission['Permission']['status']) && $permission['Permission']['status'] == 0) {
                $this->denyRedirect();
                $this->Auth->deny($this->params->action);
            } elseif (empty($permission['Permission']['status'])) {
                $this->denyRedirect();
                $this->Auth->deny($this->params->action);
            } elseif ($permission['Permission']['status'] == 1) {
                $this->Auth->allow($this->params->action);
            }
        }
    }

    /**
     * Getter for permissions
     * 
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
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
        if ($this->Auth->user('role_id')) {
            $this->role = $this->Auth->user('role_id');
        } else {
            $this->loadModel('Role');
            if ($role = $this->Role->findByDefaults('default-guest')) {
                $this->role = $role['Role']['id'];
            } else {
                $this->role = null;
            }
        }

        return $this->role;
    }

	public function permissionLookup( $params = array() )
	{
		$this->loadModel('Permission');

		if (!empty($params[0]))
		{
			$params = $params[0];
		}
		
		if ( empty($params['action']) )
		{
			$params['action'] = $this->params->action;
		}

		if ( empty($params['controller']) )
		{
			$params['controller'] = $this->params->controller;
		}

		if ( empty($params['plugin']) )
		{
			if ( !empty($this->params->plugin) )
			{
				$params['plugin'] = $this->params->plugin;
			} else {
				$params['plugin'] = '';
			}
		}

		$permission = $this->Permission->find('first', array(
			'conditions' => array(
				'Permission.role_id' => $this->getRole(),
				'Permission.action' => $params['action'],
				'Permission.controller' => $params['controller'],
				'Permission.plugin' => $params['plugin'],
			)
		));

		if (!empty($params['show']))
		{
			return $permission;
		}

		if (empty($permission) ||
			empty($permission['Permission']['status']))
		{
			return false;
		} else {
			return true;
		}
	}

	public function getRelatedPermissions($permission, $controller = null)
	{
		if (!empty($permission))
		{
            $data = array();

			if (!empty($permission['Permission']['controller']))
			{
				$controller = $permission['Permission']['controller'];
			} elseif (empty($controller))
			{
				$controller = $this->params->controller;
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

				$new_related['related'] = $this->Permission->find('all', array(
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
     * Returns whether user is authorized or not
     * 
     * @param string $user
     * @return boolean
     */
    public function isAuthorized($user = NULL) {
        if ($this->params['prefix'] === 'admin') {
            return true;
        }
        return true;
    }

    /**
     * If AJAX request, returns json_encode array, otherwise a redirect
     *
     * @return void
     */
    public function denyRedirect()
    {
    	if ($this->request->is('ajax')) {
            return die(json_encode(array(
                    'status' => 'error',
                    'message' => 'You do not have access to this page'
            )));
    	} else {
            if (Configure::read('dev') == 1)
            {
                die(debug($this->params));
            }
            else
            {
                $this->Session->setFlash('You do not have access to this page.', 'flash_error');

                if (Controller::referer() && Router::url( $this->here, true ) != Controller::referer())
                {
                    $this->redirect( Controller::referer() );
                }
                else
                {
                    $this->redirect(array(
                        'plugin' => null,
                        'admin' => false,
                        'controller' => 'pages',
                        'action' => 'display',
                        'denied'
                    ));
                }
            }
        }
    }

    /**
     * Slugs string
     * 
     * @param string $str
     * @param string $orig
     * @return string
     */
    public function slug($str, $orig = null) {
        if ($orig == null) {
            return strtolower(Inflector::slug($str, "-"));
        } else {
            return strtolower(Inflector::slug($str));
        }
    }

    public function blackhole($type) {
        if ($type != 'auth')
        {
            $this->Session->setFlash(
                'We have encountered an ' . $type . ' error. Please ensure you are logged in and for forms - refresh the page and submit again.',
                'flash_error'
            );
            $this->redirect( Controller::referer() );
        }
    }

    /**
     * Logs current page params
     */
    public function logAction()
    {
        $this->loadModel('Log');

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
        $this->Log->save($log_insert);
    }
    
    /**
     * Looks up any blocks that should run on page and loads them
     * 
     * @return none
     */
    public function blocksLookup()
    {
        if ($this->params->prefix != "admin") {
            $this->loadModel('Block');

            if (!empty($this->params['pass'][0])) {
                $location = $this->params->controller.'|'.$this->params->action.'|'.$this->params['pass'][0];
                $location2 = $this->params->controller.'|'.$this->params->action;

                $block_cond = array(
                    'conditions' => array(
                        'OR' => array(
                            array('Block.location LIKE' => '%"*"%'),
                            array('Block.location LIKE' => '%"' . $location . '"%'),
                            array('Block.location LIKE' => '%"' . $location2 . '"%')
                        ),
                        'Block.deleted_time' => '0000-00-00 00:00:00'
                    ),
                    'contain' => array(
                        'Module'
                    )
                );
            } else {
                $location = $this->params->controller.'|'.$this->params->action;
                $block_cond = array(
                    'conditions' => array(
                        'OR' => array(
                            array('Block.location LIKE' => '%"*"%'),
                            array('Block.location LIKE' => '%"' . $location . '"%')
                        ),
                        'Block.deleted_time' => '0000-00-00 00:00:00'
                    ),
                    'contain' => array(
                        'Module'
                    )
                );
            }

            $data = $this->Block->find('all', $block_cond);

            if (!empty($data))
            {
                $block_data = array();
                $block_permissions = array();

                foreach($data as $row)
                {
                    if (!empty($row['Block']['settings']))
                    {
                        $settings = json_decode($row['Block']['settings']);

                        foreach($settings as $key => $val)
                        {
                            $row['Block'][$key] = $val;
                        }

                        unset($row['Block']['settings']);
                    }

                    if ($row['Block']['type'] == "dynamic")
                    {
                        if ($row['Module']['is_plugin'] == 1)
                        {
                            $model = $row['Module']['model_title'];
                            $this->loadModel(
                                str_replace(' ','',$row['Module']['title']).'.'.$model
                                );
                        } else {
                            $model = $row['Module']['model_title'];
                            $this->loadModel($model);
                        }

                        $permissions = $this->Block->Module->Permission->find('first', array(
                            'conditions' => array(
                                'Permission.module_id' => $row['Module']['id'],
                                'Permission.action NOT LIKE' => '%admin%',
                                'Permission.role_id' => $this->getRole()
                            ),
                            'order' => 'Permission.related DESC',
                            'limit' => 1
                        ));

                        if (!empty($permissions))
                        {
                            $block_permissions
                            [$row['Block']['title']] =
                            $this->getRelatedPermissions($permissions);
                        }

                        if (method_exists($this->$model, 'getBlockData'))
                        {
                            $block_data
                            [$row['Block']['title']] = $this->$model->getBlockData(
                                $row['Block'], 
                                $this->Auth->user('id')
                                );
                        }
                    } elseif (!empty($row['Block']['data'])) {
                        $block_data[$row['Block']['title']] = $row['Block']['data'];
                    }
                }
            }

            $this->set(compact('block_data', 'block_permissions'));
        }
    }

    /**
     * Looks up any cron entries that need to run and run the model function
     *
     * @return void
     */
	public function runCron()
	{
        if ($this->params->controller != 'cron')
        {
            $this->loadModel('Cron');

            $find = $this->Cron->find('first', array(
                'conditions' => array(
                    'Cron.run_time <=' => date('Y-m-d H:i:s'),
                    'Cron.deleted_time' => '0000-00-00 00:00:00'
                ),
                'order' => 'run_time ASC'
            ));

            if (!empty($find))
            {
                $module = $this->Cron->Module->findById($find['Cron']['module_id']);

                if (!empty($module))
                    $find = array_merge($find, $module);

                $function = $find['Cron']['function'];

                if ($find['Module']['is_plugin'] == 1) {
                    $model = $find['Module']['model_title'];
                    $this->loadModel(
                        str_replace(' ','',$find['Module']['title']).'.'.$model
                    );
                } else {
                    $model = $find['Module']['model_title'];
                    $this->loadModel($model);
                }

                try {
                    $this->$model->$function();
                } catch (Exception $e) {

                }

                $amount = $find['Cron']['period_amount'];
                $type = $find['Cron']['period_type'];
                $run_time = date('Y-m-d H:i:s', strtotime('+' . $amount . ' '.$type));

                $this->Cron->id = $find['Cron']['id'];
                $this->Cron->saveField('run_time', $run_time);
            }
        }
	}

    /**
     * 
     * @param string $user
     */
    public function beforeFacebookLogin($user)
    {
    }

    /**
     * 
     * @return boolean
     */
    public function beforeFacebookSave()
    {
    	$this->loadModel('User');

    	$find = $this->User->find('first', array(
    		'conditions' => array(
    			'OR' => array(
    				'User.email' => $this->Connect->user('email'),
    				'User.facebook_id' => $this->Connect->user('facebook_id')
    			)
    		)
    	));

    	if (!empty($find)) {
    		if (!$this->Auth->user('id')) {
	    		$this->User->id = $find['User']['id'];
	    		$this->Auth->login($find['User']);

	    		if (empty($find['User']['facebook_id'])) {
	    			$this->User->saveField('facebook_id', $this->Connect->user('facebook_id'));
	    		}

				$this->User->saveField('login_time', $this->User->dateTime());
			}

    		return false;
    	} else {
	        $this->Connect->authUser['User']['email'] = $this->Connect->user('email');
	        $this->Connect->authUser['User']['username'] = $this->Connect->user('email');
	        $this->Connect->authUser['User']['status'] = 1;
	        $this->Connect->authUser['User']['login_time'] = $this->User->dateTime();

        	$this->loadModel('Role');
        	$role = $this->Role->find('first', array(
        		'conditions' => array(
        			'Role.title' => 'Facebook',
        			'Role.defaults' => 'default-facebook'
        		)
        	));

        	if (!empty($role)) {
	        	$this->Connect->authUser['User']['role_id'] = $role['Role']['id'];
	        }

	        return true;
	    }
    }

    /**
     * 
     * @return redirect
     */
    public function afterFacebookLogin()
    {
    	$this->Session->write('login_type', 'facebook');
    	$this->Session->setFlash('Welcome back '.$this->Auth->User('username').'!', 'flash_success');
        return $this->redirect('/');
    }

    /**
     * Gets list of timezones
     * 
     * @return array
     */
    public function getTimeZones()
    {
    	return array(
            '-12' => '(GMT -12:00) Eniwetok, Kwajalein',
            '-11' => '(GMT -11:00) Midway Island, Samoa',
            '-10' => '(GMT -10:00) Hawaii',
            '-9' => '(GMT -9:00) Alaska',
            '-8' => '(GMT -8:00) Pacific Time (US & Canada)',
            '-7' => '(GMT -7:00) Mountain Time (US & Canada)',
            '-6' => '(GMT -6:00) Central Time (US & Canada), Mexico City',
            '-5' => '(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima',
            '-4.5' => '(GMT -4:30) Caracas',
            '-4' => '(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago',
            '-3.5' => '(GMT -3:30) Newfoundland',
            '-3' => '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
            '-2' => '(GMT -2:00) Mid-Atlantic',
            '-1' => '(GMT -1:00 hour) Azores, Cape Verde Islands',
            '0' => '(GMT) Western Europe Time, London, Lisbon, Casablanca',
            '1' => '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
            '2' => '(GMT +2:00) Kaliningrad, South Africa',
            '3' => '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
            '3.5' => '(GMT +3:30) Tehran',
            '4' => '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
            '4.5' => '(GMT +4:30) Kabul',
            '5' => '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
            '5.5' => '(GMT +5:30) Mumbai, Kolkata, Chennai, New Delhi',
            '5.75' => '(GMT +5:45) Kathmandu',
            '6' => '(GMT +6:00) Almaty, Dhaka, Colombo',
            '6.5' => '(GMT +6:30) Yangon, Cocos Islands',
            '7' => '(GMT +7:00) Bangkok, Hanoi, Jakarta',
            '8' => '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
            '9' => '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
            '9.5' => '(GMT +9:30) Adelaide, Darwin',
            '10' => '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
            '11' => '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
            '12' => '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
        );
    }

    /**
     * Convenience function that based on permissions array/user_id, checks to see if user
     * has access to item. Returns true or false.
     *
     * @param array $permission
     * @param integer $user_id
     * @return boolean
     */
    public function hasPermission($permission = null, $user_id = null)
    {
        if (!empty($permission) && !empty($user_id) && $user_id == $this->Auth->User('id') ||
            !empty($permission) && $permission['any'] > 0 ||
            !empty($permission) && $permission['action'] == 'admin_add' && $permission['any'] == 0 ||
            !empty($permission) && $permission['any'] == 2)
        {
            return true;
        } else {
            return false;
        }
    }
}