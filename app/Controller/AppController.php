<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Model', 'ConnectionManager');
App::uses('AdaptcmsView', 'View');
/**
 * Class AppController
 * @property Cron $Cron
 * @property Module $Module
 * @property Article $Article
 * @property User $User
 * @property Permission $Permission
 * @property Role $Role
 * @property Theme $Theme
 * @property Log $Log
 * @property ModuleValue $ModuleValue
 * @property Block $Block
 * @property SettingValue $SettingValue
 * @property Setting $Setting
 * @property CakeRequest $request
 */
class AppController extends Controller
{
    /**
     * Array of necessary components. DebugKit, Auth, Sesson and Security - by default.
     * 
     * @var array 
     */
    public $components = array(
        //'DebugKit.Toolbar',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
                'admin' => false,
                'plugin' => false
            ),        
            'loginRedirect' => array(
                'plugin' => false,
                'admin' => false,
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
        ),
        'Paginator'
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
        'Cache' => array('className' => 'AdaptcmsCache'),
        'AutoLoadJS',
        'Admin',
	    'AdaptHtml',
	    'View',
	    'Session'
    );

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
     * @var array
     */
    public $paginate = array();

    /**
     * @var int
     */
    public $pageLimit = 10;

    /**
     * @var array
     */
    public $allowedActions = array();

    /**
     * @var string
     */
    public $theme;

	private $_elementView;

	/**
	 * @var bool
	 */
	private $is_admin = false;

	/**
	 * @var array
	 */
	private $blocks = array();

	/**
	 * If set to true, disables parsing of template tags - used for angular templates.
	 *
	 * @var bool
	 */
	public $disable_parsing = false;

    /**
     * A whole lot is going on in this one. We look for and attempt to load components/helpers, call Auth/Authorize,
     * load the layout, run the accessCheck, run the cron.
     * 
     * @return null
     */
    public function beforeFilter()
    {
        /*
        * Loads Up Helpers from config file
        */
        if (Configure::check('internal.system.helpers'))
        {
            foreach(Configure::read('internal.system.helpers') as $key => $helper)
            {
                if (is_numeric($key))
                {
                    $this->helpers[] = $helper;
                } else {
                    $this->helpers[] = $key;
                }
            }
        }

        /*
        * Loads Up Components from config file
        */
        if (Configure::check('internal.system.components'))
        {
            foreach(Configure::read('internal.system.components') as $key => $component)
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

        if ($this->request->controller != "install") {
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

	    if (!empty($this->request->prefix) && $this->request->prefix == 'admin') {
		    $this->is_admin = true;
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

        $this->accessCheck();
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
	        Configure::write('User.id', $this->Auth->user('id'));

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
        if ($this->request->action == "admin_index" || $this->request->action == 'index') {
            $limit = $this->SettingValue->findByTitle('Number of Items Per Page');

            if (!empty($limit))
                $this->pageLimit = $limit['SettingValue']['data'];
        }

        $this->Paginator->settings = array(
            'limit' => $this->pageLimit
        );

        $this->viewClass = 'Adaptcms';
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
            if (!empty($this->request->prefix) && $this->is_admin or
                $this->request->action == "admin" && strtolower($this->request->controller) != "users") {
                $this->layout = "admin";
                $this->set('prefix', 'admin');
            } elseif (!empty($this->request->prefix) && $this->request->prefix == "rss") {
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

        if (!empty($this->allowedActions) && in_array($this->request->action, $this->allowedActions)) {
            $allowed = 1;
        } elseif ($this->request->action == "login" or strstr($this->request->action, "activate") or $this->request->action == "logout"
            or $this->request->action == "register" or strstr($this->request->action, "_password")
            or !empty($this->request->pass[0]) && $this->request->pass[0] == "denied"
            or !empty($this->request->pass[0]) && $this->request->pass[0] == "home"
            || !empty($this->request->prefix) && $this->request->prefix == "rss" || $this->request->controller == 'install' && !strstr($this->request->action, 'plugin') ||
            !empty($this->request->ext) && in_array($this->request->ext, $webroot_exts)) {
                        $this->Auth->allow($this->request->action);
        } elseif (!empty($this->request->prefix) && $this->is_admin && !$this->Auth->User('id')
            || $this->request->action == "admin" && !$this->Auth->User('id')
            && strtolower($this->request->controller) != "users"
            ) {
                $this->Auth->deny($this->request->action);
        } elseif ($this->getRole()) {
            if (empty($this->request->plugin)) {
                $this->request->plugin = '';
            }

            if ($this->request->action == "admin" && $this->request->controller == "pages") {
                $permission = $this->Permission->find('first', array(
                    'conditions' => array(
                        'Permission.role_id' => $this->getRole(),
                        'Permission.status' => 1,
                        'Permission.action LIKE' => '%admin%'
                    )
                ));
            } else {
                if (!empty($this->request->pass[0]) && is_numeric($this->request->pass[0])) {
//	                debug($this->request);
                    $permission = $this->Permission->find('first', array(
                        'conditions' => array(
                            'Permission.role_id' => $this->getRole(),
                            'Permission.action' => $this->request->action,
                            'Permission.controller' => $this->request->controller,
                            'Permission.plugin' => $this->request->plugin,
                            'Permission.action_id' => $this->request->pass[0]
                        )
                    ));

                    if (empty($permission)) {
                        $permission = $this->Permission->find('first', array(
                            'conditions' => array(
                                'Permission.role_id' => $this->getRole(),
                                'Permission.action' => $this->request->action,
                                'Permission.controller' => $this->request->controller,
                                'Permission.plugin' => $this->request->plugin
                            )
                        ));
                    }
                } else {
                    $permission = $this->Permission->find('first', array(
                        'conditions' => array(
                            'Permission.role_id' => $this->getRole(),
                            'Permission.action' => $this->request->action,
                            'Permission.controller' => $this->request->controller
                        )
                    ));
                }
            }

            $this->permissions = $this->getRelatedPermissions($permission);
            $this->set('permissions', $this->permissions);

            if (isset($permission['Permission']['status']) && $permission['Permission']['status'] == 0) {
                $this->denyRedirect();
                $this->Auth->deny($this->request->action);
            } elseif (empty($permission['Permission']['status'])) {
                $this->denyRedirect();
                $this->Auth->deny($this->request->action);
            } elseif ($permission['Permission']['status'] == 1) {
                $this->Auth->allow($this->request->action);
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

	    Configure::write('User.role', $this->role);

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
			$params['action'] = $this->request->action;
		}

		if ( empty($params['controller']) )
		{
			$params['controller'] = $this->request->controller;
		}

		if ( empty($params['plugin']) )
		{
			if ( !empty($this->request->plugin) )
			{
				$params['plugin'] = $this->request->plugin;
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
				$controller = $this->request->controller;
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
        if ($this->is_admin) {
            return true;
        }
        return true;
    }

    /**
     * If AJAX request, returns json_encode array, otherwise a redirect
     *
     * @return mixed
     */
    public function denyRedirect()
    {
    	if ($this->request->is('ajax')) {
		    return new CakeResponse(array(
			    'body' => 'You do not have access to this page',
			    'type' => 'json',
			    'status' => 401
		    ));
    	} else {
            if (Configure::read('dev') == 4)
            {
//                die(debug($this->params));
	            return true;
            }
            else
            {
                $this->Session->setFlash('You do not have access to this page.', 'error');

                if (Controller::referer() && !strstr(Controller::referer(), $this->here))
                {
                    return $this->redirect( Controller::referer() );
                }
                else
                {
                    return $this->redirect(array(
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
	 * Blackhole
	 *
	 * @param $type
	 * @return void
	 */
	public function blackhole($type) {
        if ($type != 'auth')
        {
            $this->Session->setFlash(
                'We have encountered an ' . $type . ' error. Please ensure you are logged in and for forms - try and submit again.',
                'error'
            );
            return $this->redirect( Controller::referer() );
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
            'plugin' => $this->request->plugin,
            'controller' => $this->request->controller,
            'action' => $this->request->action,
            'action_id' => $action_id,
            'date' => date('Y-m-d H:i:s')
        );
        $this->Log->save($log_insert);
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
            $this->loadModel('Block');

	        if (empty($block_lookup['block'])) {
	            $data = $this->Block->find('first', array(
		            'conditions' => array(
			            'Block.title' => $block
		            ),
		            'contain' => array(
			            'Module'
		            )
	            ));
		        $block_data['block'] = $data['Block'];
		        $block_data['block']['Module'] = $data['Module'];
		        $data = $block_data['block'];
	        } else {
		        $data = $block_lookup['block'];
	        }

	        $block_data = array();
	        $block_permissions = array();
            if (!empty($data))
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
			                $this->loadModel(
				                str_replace(' ','',$data['Module']['title']).'.'.$model
			                );
		                } else {
			                $model = $data['Module']['model_title'];
			                $this->loadModel($model);
		                }

	                    if (method_exists($this->$model, 'getBlockData'))
	                    {
	                        $block_data = $this->$model->getBlockData(
	                            $data,
	                            $this->Auth->user('id')
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
     * Looks up any cron entries that need to run and run the model function
     *
     * @param mixed $test
     * @return boolean
     */
	public function runCron($test = null)
	{
        $return = false;

        if ($this->request->controller != 'cron' || $test)
        {
            $this->loadModel('Cron');

            if (!$test)
            {
                $conditions = array(
                    'conditions' => array(
                        'Cron.run_time <=' => date('Y-m-d H:i:s'),
                        'Cron.active' => 1
                    ),
                    'order' => 'run_time ASC'
                );
            }
            else
            {
                $conditions = array(
                    'conditions' => array(
                        'Cron.id' => $test
                    )
                );
            }

            $find = $this->Cron->find('first', $conditions);

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

                $cron = array();

                try {
                    $this->$model->$function();
                    $cron['Cron']['active'] = 1;
                } catch (Exception $e) {
                    $cron['Cron']['active'] = 0;
                }

                $amount = $find['Cron']['period_amount'];
                $type = $find['Cron']['period_type'];

                $this->Cron->id = $find['Cron']['id'];

                $cron['Cron']['run_time'] = date('Y-m-d H:i:s', strtotime('+' . $amount . ' '.$type));
                $cron['Cron']['last_run'] = date('Y-m-d H:i:s');

                $this->Cron->save($cron);

                $return =  $cron['Cron']['active'];
            }
        }

        return $return;
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
    				'User.settings LIKE' => '%"facebook_id": "' . $this->Connect->user('facebook_id') . '"'
    			)
    		)
    	));

    	if (!empty($find)) {
    		if (!$this->Auth->user('id')) {
	    		$this->User->id = $find['User']['id'];
	    		$this->Auth->login($find['User']);

	    		if (empty($find['User']['settings']['facebook_id'])) {
	    			$this->User->saveField('settings', array_merge(
                        $find['User']['settings'],
                        array(
                            'facebook_id' => $this->Connect->user('facebook_id')
                        )
                    ));
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
     * @return void
     */
    public function afterFacebookLogin()
    {
    	$this->Session->write('login_type', 'facebook');
    	$this->Session->setFlash('Welcome back '.$this->Auth->User('username').'!', 'success');
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

	/**
	 * Has Access To Item
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function hasAccessToItem($data)
	{
		if (empty($data))
		{
			$this->Session->setFlash('Item does not exist.', 'error');
			$redirect = true;
		}

		if (!empty($data['User']['id']) && $data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
		{
			$this->Session->setFlash('You cannot access another users item.', 'error');
			$redirect = true;
		}

		return (isset($redirect) ? $this->redirect(array('action' => 'index')) : true);
	}

	/**
	 * Has Access To Admin
	 *
	 * @param null $role
	 * @return bool
	 */
	public function hasAccessToAdmin($role = null)
    {
        $permission = $this->Permission->find('first', array(
            'conditions' => array(
                'Permission.role_id' => !empty($role) ? $role : $this->getRole(),
                'Permission.status' => 1,
                'Permission.action LIKE' => '%admin%'
            )
        ));

        return !empty($permission) ? true : false;
    }

	public function _sendEmail()
	{

	}

	/**
	 * Ajax Response
	 *
	 * @param mixed $element
	 * @param array $params
	 * @param string $type
	 * @param string $status
	 *
	 * @return CakeResponse
	 */
	public function _ajaxResponse($element, $params = array(), $type = 'json', $status = 'success')
	{
		$this->layout = 'ajax';
		$this->autoRender = false;

		if (!is_array($element))
		{
			$contents = $this->_getElement($element, $params);
		}
		else
		{
			$contents = $element['body'];
		}

		if($type == 'json')
		{
			$data = array(
				'status' => $status,
				'data' => $contents
			);

			$body = json_encode($data);
		}
		else
		{
			$body = $contents;
		}

		return new CakeResponse(array('body' => $body, 'type' => $type));
	}

	/**
	 * Get Element
	 *
	 * @param $element
	 * @param array $params
	 *
	 * @return string
	 */
	public function _getElement($element, $params = array())
	{
		if (!$this->_elementView)
		{
			$this->_elementView = new AdaptcmsView($this);
		}

		return $this->_elementView->element($element, $params);
	}

	/**
	 * Check Captcha
	 *
	 * @param string $captcha
	 *
	 * @return bool
	 */
	public function checkCaptcha($captcha)
	{
		include_once(WWW_ROOT . 'libraries/captcha/securimage.php');

		if (!class_exists('DATABASE_CONFIG'))
			include_once(realpath('./../') . '/Config/database.php');

		$db = new DATABASE_CONFIG();

		$options = array(
			'use_database'    => true,
			'database_host'   => $db->default['host'],
			'database_name'   => $db->default['database'],
			'database_user'   => $db->default['login'],
			'database_pass'   => $db->default['password'],
			'database_table'   => $db->default['prefix'] . 'captcha_codes',
			'skip_table_check' => true,
			'no_session' => true,
			'database_driver' => Securimage::SI_DRIVER_MYSQL
		);

		$securimage = new Securimage($options);

		if (!empty($securimage) && !$securimage->check($captcha)) {
			return false;
		} else {
			return true;
		}
	}
}