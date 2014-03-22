<?php
App::uses('AppController', 'Controller');

/**
 * Class UsersController
 *
 * @property Field $Field
 * @property SettingValue $SettingValue
 */
class UsersController extends AppController
{
	public $name = 'Users';
	private $permissions;
	public $helpers = array('Captcha');

    public $cacheAction = array(
        'profile' => '1 day'
    );

	/**
	 * Before Filter
	 *
	 * @return null|void
	 */
	public function beforeFilter()
	{
		$this->allowedActions = array(
			'active',
			'update_password',
			'forgot_password',
			'forgot_password_activate'
		);

        $this->Security->unlockedActions = array('ajax_check_user');

		parent::beforeFilter();

		if (strstr($this->request->action, 'admin_'))
			$this->set('roles', $this->User->Role->find('list'));

		$this->permissions = $this->getPermissions();
	}

	/**
	 * Admin Index
	 *
	 * @return void
	 */
	public function admin_index()
	{
		$conditions = array();

	    if (!empty($this->request->params['named']['role_id']))
	    	$conditions['Role.id'] = $this->request->params['named']['role_id'];

	    if (isset($this->request->params['named']['status']))
	    	$conditions['User.status'] = $this->request->params['named']['status'];

	    if ($this->permissions['any'] == 0)
	    	$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['User.only_deleted'] = true;

        $this->Paginator->settings = array(
            'contain' => array(
                'Role'
            ),
            'conditions' => $conditions
        );
        
        $this->request->data = $this->Paginator->paginate('User');
	}

	/**
	 * Admin Add
	 *
	 * @return void
	 */
	public function admin_add()
	{
        if (!empty($this->request->data))
		{
            if ($this->User->save($this->request->data))
            {
				if (!empty($this->request->data['ModuleValue']))
				{
					$this->loadModel('ModuleValue');

					$this->ModuleValue->setModuleId($this->request->data['ModuleValue'], $this->User->id);
				}

                $this->Session->setFlash('Your user has been added.', 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your user.', 'error');
            }
        }

		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));

		$this->loadModel('Theme');

		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$fields = $this->User->Field->getFields('User');

		if (!empty($this->request->data['ModuleValue'])) {
			$fields = $this->User->mergeModuleData($this->request->data['ModuleValue'], $fields);
		}

		$this->set(compact('security_options', 'themes', 'timezones', 'fields'));
	}

	/**
	 * Admin Edit
	 *
	 * @param integer $id
	 *
	 * @return void
	 */
	public function admin_edit($id)
	{
		$this->User->id = $id;

	    if (!empty($this->request->data))
	    {
	    	unset($this->User->validate['password']);
	    	unset($this->User->validate['username']);

	        if ($this->User->save($this->request->data))
	        {
				if (!empty($this->request->data['ModuleValue']))
				{
					$this->loadModel('ModuleValue');

					$this->ModuleValue->saveMany($this->request->data['ModuleValue']);
				}

	            $this->Session->setFlash('Your user has been updated.', 'success');
	            return $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your user.', 'error');
	        }
	    }

        $this->request->data = $this->User->find('first', array(
        	'conditions' => array(
        		'User.id' => $id
        	),
        	'contain' => array(
        		'Article' => array(
        			'Category'
        		)
        	)
        ));
		$this->hasAccessToItem($this->request->data);

        $this->request->data['User']['password'] = '';

        $this->loadModel('Setting');

        $this->set('settings', $this->Setting->SettingValue->find('all', array(
        	'conditions' => array(
        		'Setting.title' => 'Users'
        	),
        	'contain' => array(
        		'Setting'
        	)
        )));

    	$this->request->data['User']['settings'] = json_decode(
    		$this->request->data['User']['settings'],
    		true
    	);

		$this->request->data['SecurityQuestions'] = $this->Setting->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->Setting->SettingValue->findByTitle('Security Question Options'));

		$this->loadModel('Theme');
		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$fields = $this->User->Field->getFields('User', $this->request->data['User']['id']);

		$this->set(compact('security_options', 'themes', 'timezones', 'fields'));

		$this->request->data['Security'] = $this->User->getSecurityAnswers($this->request->data);
	}

	/**
	 * Admin Delete
	 *
	 * @param null $id
	 * @param null $title
	 *
	 * @return void
	 */
	public function admin_delete($id, $title = null)
	{
	    $this->User->id = $id;

        $data = $this->User->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->User->remove($data);

		$this->Session->setFlash('The user `'.$title.'` has been deleted.', 'success');

		if ($permanent)
		{
			return $this->redirect(array('action' => 'index', 'trash' => 1));
		} else {
			return $this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Admin Restore
	 *
	 * @param null $id
	 * @param null $title
	 *
	 * @return void
	 */
	public function admin_restore($id, $title = null)
	{
	    $this->User->id = $id;

        $data = $this->User->findById($id);
		$this->hasAccessToItem($data);

	    if ($this->User->restore()) {
	        $this->Session->setFlash('The user `'.$title.'` has been restored.', 'success');
	        return $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The user `'.$title.'` has NOT been restored.', 'error');
	        return $this->redirect(array('action' => 'index'));
	    }
	}

	/**
	 * Login
	 *
	 * @return void
	 */
	public function login() {
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
		}

		if ($this->Auth->user('id')) {
			$this->Session->setFlash("You can't login, you are logged in!", 'error');
			return $this->redirect('/');
		}

		if (!empty($this->request->data) && !empty($this->request->data['User']['username']))
		{
            $status = $this->User->findByUsername($this->request->data['User']['username']);
            $this->loadModel('SettingValue');

            if (!empty($status) && $status['User']['status'] == 0) {
                $user_status = $this->SettingValue->findByTitle('User Status');

                if ($user_status['SettingValue']['data'] == "Email Activation") {
                    $custom_msg = ", please visit the link you received in your email in order to login.";
                } elseif ($user_status['SettingValue']['data'] == "Staff Activation") {
                    $custom_msg = ", you must wait for an admin to activate your account.";
                } else {
                    $custom_msg = null;
                }

                $this->Session->setFlash('Your account is inactive' . $custom_msg, 'error');
                return $this->redirect( $this->Auth->redirect() );
            } else {
                $password_reset = $this->SettingValue->findByTitle('User Password Reset');

                if (!empty($password_reset) && $password_reset['SettingValue']['data'] > 0) {
                    $user = $this->User->findByUsername($this->request->data['User']['username']);
                    if (!empty($user)) {
                        $diff = strtotime($user['User']['last_reset_time']);
                        $math = round((time() - $diff) / (60 * 60 * 24), 0);

                        if ($user['User']['last_reset_time'] == '0000-00-00 00:00:00' ||
                            $math > $password_reset['SettingValue']['data']) {
                            return $this->redirect(array(
                                'action' => 'reset_password',
                                $this->request->data['User']['username']
                            ));
                        }
                    }
                }

                if ($this->Auth->login())
                {
                    $this->User->id = $this->Auth->user('id');
                    $this->User->saveField('login_time', $this->User->dateTime());

                    $this->Session->setFlash('Welcome back '.$this->Auth->User('username').'!', 'success');

                    if (!$this->hasAccessToAdmin( $this->Auth->User('Role.id') ))
                    {
                        return $this->redirect('/');
                    }
                    else
                    {
                        return $this->redirect('/admin');
                    }
                } else {
                    $this->Session->setFlash('Username or password is incorrect', 'error');
                }
            }
		}
	}

	/**
	 * Logout
	 *
	 * @return void
	 */
	public function logout() {
    	$this->Session->destroy();

		$this->Session->setFlash('You have successfully logged out.', 'success');

        return $this->redirect($this->Auth->logout());
    }

	/**
	 * Register
	 *
	 * @return void
	 */
	public function register()
	{
		if ($this->Auth->user('id')) {
        	$this->Session->setFlash("You can't register, you are logged in!", 'error');
			return $this->redirect('/');
		}

        $this->loadModel('SettingValue');
		
		$reg_closed = $this->SettingValue->findByTitle('Is Registration Open?');

		if (!empty($reg_closed) && $reg_closed['SettingValue']['data'] == 'No') {
			$closed_msg = $this->SettingValue->findByTitle('Closed Registration Message');

			if (!empty($closed_msg)) {
				$msg = $closed_msg['SettingValue']['data'];
			} else {
				$msg = 'Registration is closed at this time.';
			}

			$this->Session->setFlash($msg, 'error');

			return $this->redirect('/');
		}

		$user_status = $this->SettingValue->findByTitle('User Status');
		$captcha = $this->SettingValue->findByTitle('Registration Captcha');

        if (!empty($this->request->data))
        {
            if (!empty($captcha['SettingValue']['data']))
            {
	            if ($captcha['SettingValue']['data'] == 'Yes' && empty($this->request->data['captcha']) ||
		            $captcha['SettingValue']['data'] == 'Yes' && !$this->checkCaptcha($this->request->data['captcha'])) {
		            $message = 'Invalid Captcha Answer. Please try again.';
	            }
            }

        	$role = $this->User->Role->findByDefaults('default-member');
        	$this->request->data['User']['role_id'] = $role['Role']['id'];

            if (empty($user_status['SettingValue']['data']))
                $this->request->data['User']['status'] = 1;

            if (empty($message) && $this->User->save($this->request->data))
            {
                $password = $this->request->data['User']['password'];
            	$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $this->User->id));

                $sitename = $this->SettingValue->findByTitle('Site Name');
                $webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');
                $email_subject = $this->SettingValue->findByTitle('User Register Email Subject');
            	
	        	if ($user_status['SettingValue']['data'] == "Email Activation")
	        	{
	        		$activate_code['activate_code'] = md5(time());
					$this->User->saveField('settings', json_encode($activate_code));

	        		$email = new CakeEmail();

					$email->to($this->request->data['User']['email']);
					$email->from(array(
						$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
					));
					$email->subject($email_subject['SettingValue']['data']);
					if ($this->theme != "Default") {
						// $email->theme($this->theme);
					}
					$email->emailFormat('html');
					$email->template('register');
					$email->viewVars(array(
						'data' => $this->request->data['User'],
						'sitename' => $sitename['SettingValue']['data'],
						'activate_code' => $activate_code['activate_code']
					));
					$email->send();

                	$this->Session->setFlash('Account Created - Please visit the link in the email to activate your account.', 'success');
	        	} elseif ($user_status['SettingValue']['data'] == "Staff Activation") {
                	$this->Session->setFlash('Account Created - You cannot login until a staff member has activated your account.', 'success');
	        	} else {
                    $temp_data = $this->request->data['User'];
                    $temp_data['password'] = $password;

                    $this->_welcomeEmail($temp_data, $sitename, $webmaster_email, $email_subject);

	            	$this->Auth->login($this->request->data['User']);

					$this->User->id = $this->Auth->user('id');
					$this->User->saveField('login_time', $this->User->dateTime());

                	$this->Session->setFlash('Account Created', 'success');
				}

                return $this->redirect(array(
                    'controller' => 'pages',
                    'action' => 'display', 'home'
                ));
            } else {
            	if (empty($message)) {
            		$message = 'Account could not be created';
            	}

            	$this->Session->setFlash($message, 'error');
            }
        }

		$questions = $this->SettingValue->findByTitle('Security Questions');

		$security_questions = $questions['SettingValue']['data'];

		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));

		if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes') {
			$this->set('captcha_setting', true);
		}

		$this->set(compact('security_options', 'security_questions'));
    }

	/**
	 * Ajax Check User
	 *
	 * @return CakeResponse
	 */
	public function ajax_check_user()
    {
        $count = $this->User->findByUsername($this->request->data['User']['username']);

	    $result = (empty($count) ? 1 : 0);

	    return $this->_ajaxResponse(array('body' => $result));
    }

    /**
     * Ajax Change User
     *
     * @return CakeResponse
     */
    public function admin_ajax_change_user()
    {
        $this->User->id = $this->request->data['User']['id'];

        $success = $this->User->saveField('status', $this->request->data['User']['status']);
	    $status = ($this->request->data['User']['status'] == 1 ? 'activated' : 'de-activated');

	    return $this->_ajaxResponse('Users/admin_ajax_change_user', array(
		    'success' => $success,
		    'status' => $status
	    ));
    }

	/**
	 * Activate
	 *
	 * @param null $username
	 * @param null $activate_code
	 * @return CakeResponse
	 */
	public function activate($username = null, $activate_code = null) {
    	if (!empty($this->request->data)) {
    		if (!empty($this->request->data['User']['username'])) {
    			$username = $this->request->data['User']['username'];
    		}
    		if (!empty($this->request->data['User']['activate_code'])) {
    			$activate_code = $this->request->data['User']['activate_code'];
    		}
    	}

    	if (!empty($username) && !empty($activate_code)) {
    		$match = $this->User->findByUsername($username);

		    if (empty($match)) {
			    $this->Session->setFlash('Invalid username', 'error');
				return $this->redirect('/');
		    }

    		$code_match = json_decode($match['User']['settings'], true);

    		if ($match['User']['status'] == 1) {
    			return $this->redirect(array('action' => 'login'));
    		}

    		if (!empty($code_match[0]['activate_code']) && $code_match['activate_code'] == $activate_code) {
    			$data['User']['id'] = $match['User']['id'];
    			$data['User']['settings'] = null;
    			$data['User']['status'] = 1;

    			$this->User->save($data);

            	$this->Session->setFlash('Account Activated - You may now login.', 'success');

                return $this->redirect(array('action' => 'login'));
    		} else {
            	$this->Session->setFlash('Incorrect Code Entered', 'error');
    		}
    	}
    }

    public function forgot_password()
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You didn't forget your password, you are logged in!", 'error');
			return $this->redirect('/');
		}

		if (!empty($this->request->data))
		{
	        if (empty($this->request->data['User']['captcha']) || !$this->checkCaptcha($this->request->data['User']['captcha']))
	        {
	            $error = 'Invalid Captcha Answer. Please try again.';
	        }

	        if (empty($error))
	        {
    			if (!empty($this->request->data['User']['username'])) {
    				$user = $this->User->findByUsername($this->request->data['User']['username']);
    			}
    			elseif (!empty($this->request->data['User']['email']))
				{
    				$user = $this->User->findByEmail($this->request->data['User']['email']);
    			}

    			if (empty($user))
    			{
    				$error = 'Could not find user by that username/email.';
    			}
    			else
    			{
    				$security_answers = $this->User->getSecurityAnswers($user);

    				if (!empty($security_answers['activate_code']) && !empty($security_answers['activate_time']))
    				{
    					$time_diff = time() - $security_answers['activate_time'];
    				}

    				if (!empty($time_diff) && $time_diff < 86400)
    				{
    					$error = 'Please check your email, a forgot password request was sent out less than 24 hours ago.';
    				}
    				else
    				{
	    				$this->loadModel('SettingValue');

		        		$sitename = $this->SettingValue->findByTitle('Site Name');
		        		$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

						$this->User->id = $user['User']['id'];

						$security_answers['activate_code'] = md5(time());
						$security_answers['activate_time'] = time();

						$this->User->saveField('security_answers', json_encode($security_answers));

		        		$email = new CakeEmail();

						$email->to($user['User']['email']);
						$email->from(array(
							$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
						));
						$email->subject($sitename['SettingValue']['data']." - Forgot Password Request");
						if ($this->theme != "Default") {
							$email->theme($this->theme);
						}
						$email->emailFormat('html');
						$email->template('forgot_password');
						$email->viewVars(array(
							'data' => $user['User'],
							'sitename' => $sitename['SettingValue']['data'],
							'activate_code' => $security_answers['activate_code']
						));

						if ($email->send())
						{
							$this->set('activate', true);

			            	$this->Session->setFlash('An email has been dispatched to continue.', 'success');
						}
					}
    			}
	        }

	        if (!empty($error))
	        {
	        	$this->Session->setFlash($error, 'error');
	        }
		}
    }

    /**
     * Forgot Password Activate
     *
     * @return void
     */
    public function forgot_password_activate()
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You didn't forget your password, you are logged in!", 'error');
			return $this->redirect('/');
		}

	    if (!empty($this->request->params['named']['username']))
		    $this->request->data['User']['username'] = $this->request->params['named']['username'];

	    if (!empty($this->request->data['User']['username'])) {
	        $user = $this->User->findByUsername($this->request->data['User']['username']);

		    $security = $this->User->getSecurityQuestion($user);

	        $this->set(compact('security'));
	    }

		if ($this->request->is('post'))
		{
	        if (empty($this->request->data['User']['captcha']) || !$this->checkCaptcha($this->request->data['User']['captcha']))
	        {
	            $error = 'Invalid Captcha Answer. Please try again.';
	        }

			if (!empty($this->request->data['User']['security_question']) && !empty($this->request->data['User']['security_answer'])) {
				$security = $this->User->getSecurityQuestion($user, false, $this->request->data['User']['security_question']);

				if ($security['answer'] != $this->request->data['User']['security_answer']) {
					$error = 'You entered the incorrect security answer. Please try again.';
					$this->request->data['User']['security_answer'] = '';
				}
			}

			if (empty($error))
			{
				if (empty($user))
				{
					$error = 'Could not find user by that username.';
				}
				else
				{
					$security_answers = $this->User->getSecurityAnswers($user);

					if (!empty($security_answers['activate_code']) && $security_answers['activate_code'] == $this->request->data['User']['activate_code'])
					{
						unset($security_answers['activate_code'], $security_answers['activate_time']);

						$this->request->data['User']['id'] = $user['User']['id'];
						$this->request->data['User']['security_answers'] = json_encode($security_answers);

						if ($this->User->save($this->request->data))
						{
			            	$this->Session->setFlash('Your password has been updated. You may now login.', 'success');
			            	return $this->redirect(array('action' => 'login'));
			            }
			            else
			            {
			            	$error = 'Could not updated password.';
			            }
					}
					else
					{
						$error = 'Either the user has no forgot password code or code entered is incorrect.';
					}
				}
			}

	        if (!empty($error))
	        {
		        $this->request->data['User']['captcha'] = '';
	        	$this->Session->setFlash($error, 'error');
	        }
		}

		if (!empty($this->request->params['named']['code']))
			$this->request->data['User']['activate_code'] = $this->request->params['named']['code'];
	}

    public function reset_password($username = null)
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You can't reset your password, you are logged in!", 'error');
			return $this->redirect('/');
		}

    	$this->loadModel('SettingValue');
    	$password_reset = $this->SettingValue->findByTitle('User Password Reset');

	    $this->set('reset_time', $password_reset['SettingValue']['data']);

    	if (!empty($this->request->data))
    	{
	        if (empty($this->request->data['User']['captcha']) || !$this->checkCaptcha($this->request->data['User']['captcha']))
	        {
	            $error = 'Invalid Captcha Answer. Please try again.';
	        }

			if (empty($error))
			{
	    		$user = $this->User->findByUsername($this->request->data['User']['username']);

	    		if (empty($user))
	    		{
	            	$error = 'That username does not exist.';
	    		}
	    		else
	    		{
	    			if (AuthComponent::password($this->request->data['User']['password_current']) != 
	    				$user['User']['password'])
	    			{
						$error = 'Current Password is Incorrect. Please try again';
	    			}
	    			else
	    			{
	    				$this->request->data['User']['id'] = $user['User']['id'];
	    				$this->request->data['User']['last_reset_time'] = $this->User->dateTime();
	    				$this->request->data['User']['login_time'] = $this->User->dateTime();

	    				if ($this->User->save($this->request->data))
	    				{
	    					$this->Auth->login();

			            	$this->Session->setFlash('Your password has been updated and you have been logged in', 'success');
			            	return $this->redirect($this->Auth->redirect());
	    				}
	    			}
	    		}
			}

	        if (!empty($error))
	        {
	        	$this->Session->setFlash($error, 'error');
	        }
    	}

    	if (!empty($username))
    	{
    		$this->request->data['User']['username'] = $username;
    	}
	}

    public function profile($username = null)
    {
    	if (!empty($this->request->params['username']))
    	{
    		$username = $this->request->params['username'];
    	}

    	if (empty($username) && $this->Auth->user('id')) {
    		$username = $this->Auth->user('username');
    	} elseif (empty($username)) {
        	$this->Session->setFlash('No username supplied', 'error');
        	return $this->redirect(array(
        		'controller' => 'pages',
        		'action' => 'display',
        		'home'
        	));
    	}

        if ($username != $this->Auth->user('username') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'error');
            return $this->redirect('/');
        }

    	$this->request->data = $this->User->find('first', array(
    		'conditions' => array(
    			'User.username' => $username
    		),
    		'contain' => array(
    			'Article' => array(
    				'limit' => 10,
				    'order' => 'created DESC'
    			),
    			'Role',
    			'Comment' => array(
    				'limit' => 10,
    				'order' => 'created DESC'
    			)
    		)
    	));

	    $this->request->data['Article'] = $this->User->Category->getCategories($this->request->data['Article']);
	    $this->request->data['Comment'] = $this->User->Article->getArticles($this->request->data['Comment']);

        if (empty($this->request->data))
        {
            $this->Session->setFlash('User does not exist.', 'error');
            return $this->redirect('/');
        }

        $this->loadModel('Field');

    	$data = $this->Field->getFields('User');

	    $user[0] = $this->request->data;

	    $returned_data = $this->Field->getAllModuleData('User', $data, $user);
	    $this->request->data = $returned_data[0];

	    if (!empty($this->request->data['Data']))
		    $this->set('field_data', $this->request->data['Data']);

	    $this->set('user', $this->request->data['User']);
	    $this->set('articles', $this->request->data['Article']);
	    $this->set('comments', $this->request->data['Comment']);
	    $this->set('role', $this->request->data['Role']);
    }

    public function edit()
    {
    	if (!$this->Auth->user('id'))
    	{
    		$this->Session->setFlash('You must be logged in to access this page.', 'error');
    		return $this->redirect(array(
    			'action' => 'login'
    		));
    	}

		if (!empty($this->request->data))
		{
			$this->User->id = $this->Auth->user('id');

	    	unset($this->User->validate['password']);
	    	unset($this->User->validate['username']);

			if ($this->User->save($this->request->data))
			{
				if (!empty($this->request->data['ModuleValue']))
				{
					$this->loadModel('ModuleValue');
					$this->ModuleValue->saveMany($this->request->data['ModuleValue']);
				}

            	$this->Session->setFlash('Your account has been updated', 'success');
			}
		}

    	$this->request->data = $this->User->findById($this->Auth->user('id'));
    	$this->request->data['User']['settings'] = json_decode(
    		$this->request->data['User']['settings'],
    		true
    	);
	    $settings = $this->request->data['User']['settings'];

		$this->loadModel('SettingValue');

		$questions = $this->SettingValue->findByTitle('Security Questions');

	    $security_questions = $questions['SettingValue']['data'];
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));

		$this->loadModel('Theme');

		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$fields = $this->User->Field->getFields('User', $this->Auth->user('id'));

		$this->set(compact('security_options', 'security_questions', 'themes', 'timezones', 'fields', 'settings'));

		$this->request->data['Security'] = $this->User->getSecurityAnswers($this->request->data);
    }

	/**
	 * Quick Search
	 *
	 * @return CakeResponse
	 */
	public function ajax_quick_search()
    {
	    $data = array();

    	if (!empty($this->request->data['User']['username']))
	    {
    		$find = $this->User->find('all', array(
    			'conditions' => array(
    				'User.username LIKE' => '%' . $this->request->data['User']['username'] . '%'
    			)
    		));

    		foreach($find as $row)
    		{
                $data[] = array(
                	'id' => $row['User']['id'],
                	'username' => $this->User->slug($row['User']['username'])
                );
    		}
    	}

	    return $this->_ajaxResponse(array('body' => $data));
    }

    public function _welcomeEmail($data, $sitename, $webmaster_email, $email_subject)
    {
        $site_name = $sitename['SettingValue']['data'];

        $email = new CakeEmail();

        $email->to($data['email']);
        $email->from(array(
            $webmaster_email['SettingValue']['data'] => $site_name
        ));
        $email->subject($email_subject['SettingValue']['data']);
        if ($this->theme != "Default") {
            // $email->theme($this->theme);
        }
        $email->emailFormat('html');
        $email->template('new_account');
        $email->viewVars(array(
            'data' => $data,
            'sitename' => $site_name
        ));
        $email->send();
    }
}