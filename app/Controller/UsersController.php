<?php
App::uses('AppController', 'Controller');

/**
 * Class UsersController
 * @property Field $Field
 * @property SettingValue $SettingValue
 */
class UsersController extends AppController {
	public $name = 'Users';
	private $permissions;
	public $helpers = array('Captcha');

	public function beforeFilter()
	{
		$this->allowedActions = array(
			'quick_search',
			'active',
			'update_password',
			'forgot_password',
			'forgot_password_activate'
		);

        $this->Security->unlockedActions = array('ajax_check_user');

		parent::beforeFilter();

		$actions = array(
			'admin_index',
			'admin_add',
			'admin_edit'
		);

		if (in_array($this->params->action, $actions))
		{
			$this->set('roles', $this->User->Role->find('list'));
		}

		$this->permissions = $this->getPermissions();
	}

	public function admin_index()
	{
		$conditions = array();

	    if (!empty($this->params->named['role_id']))
	    {
	    	$conditions['Role.id'] = $this->params->named['role_id'];
	    }

	    if (isset($this->params->named['status']))
	    {
	    	$conditions['User.status'] = $this->params->named['status'];
	    }

	    if ($this->permissions['any'] == 0)
	    {
	    	$conditions['User.id'] = $this->Auth->user('id');
	    }

		if (!isset($this->params->named['trash'])) {
			$conditions['User.deleted_time'] = '0000-00-00 00:00:00';
	        $this->paginate = array(
	            'order' => 'User.created DESC',
	            'limit' => $this->pageLimit,
	            'contain' => array(
	            	'Role'
	            ),
	            'conditions' => array(
	            	$conditions
	            )
	        );
	    } else {
	    	$conditions['User.deleted_time !='] = '0000-00-00 00:00:00';
	        $this->paginate = array(
	            'order' => 'User.created DESC',
	            'limit' => $this->pageLimit,
	            'contain' => array(
	            	'Role'
	            ),
	            'conditions' => array(
	            	$conditions
	            )
	        );
	    }
        
        $this->request->data = $this->paginate('User');
	}

	public function admin_add()
	{
        if (!empty($this->request->data))
		{
        	$this->request->data['User']['username'] = $this->slug($this->request->data['User']['username']);

            if ($this->User->save($this->request->data))
            {
				if (!empty($this->request->data['ModuleValue']))
				{
					$this->loadModel('ModuleValue');
					$this->ModuleValue->saveMany($this->request->data['ModuleValue']);
				}

                $this->Session->setFlash('Your user has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your user.', 'flash_error');
            }
        }

		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));
		$user_status = $this->SettingValue->findByTitle('User Status');

		$this->loadModel('Theme');

		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$this->loadModel('Field');

		$fields = $this->Field->getFields('User');

		$this->set(compact('security_options', 'themes', 'timezones', 'fields'));
	}

	public function admin_edit($id = null)
	{

		$this->User->id = $id;

	    if (!empty($this->request->data))
	    {
	    	unset($this->User->validate['password']);
	    	unset($this->User->validate['username']);

	    	$this->request->data['User']['username'] = $this->slug($this->request->data['User']['username']);

	        if ($this->User->save($this->request->data))
	        {
				if (!empty($this->request->data['ModuleValue']))
				{
					$this->loadModel('ModuleValue');
					$this->ModuleValue->saveMany($this->request->data['ModuleValue']);
				}

	            $this->Session->setFlash('Your user has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your user.', 'flash_error');
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

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

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

		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));
		$user_status = $this->SettingValue->findByTitle('User Status');

		$this->loadModel('Theme');

		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$this->loadModel('Field');

		$fields = $this->Field->getFields('User', $this->request->data['User']['id']);

		$this->set(compact('security_options', 'themes', 'timezones', 'fields'));

		$this->request->data['Security'] = $this->User->getSecurityAnswers($this->request->data);
	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->User->id = $id;

        $data = $this->User->find('first', array(
        	'conditions' => array(
        		'User.id' => $id
        	)
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

	    if (!empty($permanent)) {
	    	$delete = $this->User->delete($id);
	    } else {
	    	$delete = $this->User->saveField('deleted_time', $this->User->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash('The user `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The user `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent)) {
	    	$this->redirect(array('action' => 'index', 'trash' => 1));
	    } else {
	    	$this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_restore($id = null, $title = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->User->id = $id;

        $data = $this->User->find('first', array(
        	'conditions' => array(
        		'User.id' => $id
        	)
        ));
        if ($data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));	        	
        }

	    if ($this->User->saveField('deleted_time', '0000-00-00 00:00:00')) {
	        $this->Session->setFlash('The user `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The user `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function login() {
		if (!empty($this->request->data))
		{
            $status = $this->User->findByUsername($this->request->data['User']['username']);
            $this->loadModel('SettingValue');

            if (!empty($status) && $status['User']['status'] == 0 || !empty($status) && $status['User']['deleted_time'] != '0000-00-00 00:00:00') {
                $user_status = $this->SettingValue->findByTitle('User Status');

                if ($user_status['SettingValue']['data'] == "Email Activation") {
                    $custom_msg = ", please visit the link you received in your email in order to login.";
                } elseif ($user_status['SettingValue']['data'] == "Staff Activation") {
                    $custom_msg = ", you must wait for an admin to activate your account.";
                } else {
                    $custom_msg = null;
                }

                $this->Session->setFlash('Your account is inactive' . $custom_msg, 'flash_error');
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
                            $this->redirect(array(
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

                    $this->Session->setFlash('Welcome back '.$this->Auth->User('username').'!', 'flash_success');
                    return $this->redirect( $this->Auth->redirect() );
                } else {
                    $this->Session->setFlash('Username or password is incorrect', 'flash_error');
                }
            }
		}
	}

    public function logout() {
    	$this->Session->setFlash('You have successfully logged out.', 'flash_success');

    	$this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }

    public function register()
	{
		if ($this->Auth->user('id')) {
        	$this->Session->setFlash("You can't register, you are logged in!", 'flash_error');
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

			$this->Session->setFlash($msg, 'flash_error');

			$this->redirect('/');
		}

		$user_status = $this->SettingValue->findByTitle('User Status');
		$captcha = $this->SettingValue->findByTitle('Registration Captcha');

        if (!empty($this->request->data))
        {
            if (!empty($captcha['SettingValue']['data']))
            {
                include_once(APP . 'webroot/libraries/captcha/securimage.php');
                $securimage = new Securimage();

                if ($captcha['SettingValue']['data'] == 'Yes' &&
                    !$securimage->check($this->request->data['captcha'])) {
                    $message = 'Invalid Captcha Answer. Please try again.';
                }
            }

        	$this->request->data['User']['security_answers'] = json_encode($this->request->data['Security']);
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

                	$this->Session->setFlash('Account Created - Please visit the link in the email to activate your account.', 'flash_success');
	        	} elseif ($user_status['SettingValue']['data'] == "Staff Activation") {
                	$this->Session->setFlash('Account Created - You cannot login until a staff member has activated your account.', 'flash_success');
	        	} else {
                    $temp_data = $this->request->data['User'];
                    $temp_data['password'] = $password;

                    $this->_welcomeEmail($temp_data, $sitename, $webmaster_email, $email_subject);

	            	$this->Auth->login($this->request->data['User']);

					$this->User->id = $this->Auth->user('id');
					$this->User->saveField('login_time', $this->User->dateTime());

                	$this->Session->setFlash('Account Created', 'flash_success');
				}

                return $this->redirect(array(
                    'controller' => 'pages',
                    'action' => 'display', 'home'
                ));
            } else {
            	if (empty($message)) {
            		$message = 'Account could not be created';
            	}

            	$this->Session->setFlash($message, 'flash_error');
            }
        }

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));

		if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes') {
			$this->set('captcha_setting', true);
		}

		$this->set(compact('security_options'));
    }

    public function ajax_check_user()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if($this->request->is('ajax')) {
	    	$count = $this->User->findByUsername($this->request->data['User']['username']);
	    	
	    	if (empty($count)) {
	    		$result = 1;
	    	} else {
	    		$result = 0;
	    	}

	    	return $result;
    	}
    }

    public function ajax_change_user()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if($this->request->is('ajax')) {
    		$this->User->id = $this->request->data['User']['id'];
	    	
	    	if ($this->User->saveField('status', $this->request->data['User']['status'])) {
				echo '<div id="user-change-status" class="alert alert-success">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Success</strong> The user has been activated.
	    			</div>';
	    	} else {
				echo '<div id="user-change-status" class="alert alert-error">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Error</strong> The user could not be activated.
	    			</div>';
	    	}

	    	return $result;
    	}
    }

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
    		$code_match = json_decode($match['User']['settings'], true);

    		if ($match['User']['status'] == 1) {
    			return $this->redirect(array('action' => 'login'));
    		}

    		if (!empty($code_match[0]['activate_code']) && $code_match['activate_code'] == $activate_code) {
    			$data['User']['id'] = $match['User']['id'];
    			$data['User']['settings'] = null;
    			$data['User']['status'] = 1;

    			$this->User->save($data);

            	$this->Session->setFlash('Account Activated - You may now login.', 'flash_success');

                return $this->redirect(array('action' => 'login'));
    		} else {
            	$this->Session->setFlash('Incorrect Code Entered', 'flash_error');
    		}
    	}
    }

    public function forgot_password()
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You didn't forget your password, you are logged in!", 'flash_error');
			return $this->redirect('/');
		}

		if (!empty($this->request->data))
		{
            include_once(APP . 'webroot/libraries/captcha/securimage.php');
        	$securimage = new Securimage();

	        if (empty($this->request->data['User']['captcha']) || !$securimage->check($this->request->data['User']['captcha']))
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

			            	$this->Session->setFlash('An email has been dispatched to continue.', 'flash_success');
						}
					}
    			}
	        }

	        if (!empty($error))
	        {
	        	$this->Session->setFlash($error, 'flash_error');
	        }
		}
    }

    public function forgot_password_activate()
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You didn't forget your password, you are logged in!", 'flash_error');
			return $this->redirect('/');
		}

		if (!empty($this->request->data))
		{
            include_once(APP . 'webroot/libraries/captcha/securimage.php');
        	$securimage = new Securimage();

	        if (empty($this->request->data['User']['captcha']) || !$securimage->check($this->request->data['User']['captcha']))
	        {
	            $error = 'Invalid Captcha Answer. Please try again.';
	        }

			if (empty($error))
			{
				$user = $this->User->findByUsername($this->request->data['User']['username']);

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
			            	$this->Session->setFlash('Your password has been updated. You may now login.', 'flash_success');
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
	        	$this->Session->setFlash($error, 'flash_error');
	        }
		}

		if (!empty($this->params['named']['username']))
		{
			$this->request->data['User']['username'] = $this->params['named']['username'];
		}

		if (!empty($this->params['named']['code']))
		{
			$this->request->data['User']['activate_code'] = $this->params['named']['code'];
		}
	}

    public function reset_password($username = null)
    {
		if ($this->Auth->user('id'))
		{
        	$this->Session->setFlash("You can't reset your password, you are logged in!", 'flash_error');
			return $this->redirect('/');
		}

    	$this->loadModel('SettingValue');
    	$password_reset = $this->SettingValue->findByTitle('User Password Reset');

    	$this->set(compact('password_reset'));

    	if (!empty($this->request->data))
    	{
            include_once(APP . 'webroot/libraries/captcha/securimage.php');
        	$securimage = new Securimage();

	        if (empty($this->request->data['User']['captcha']) || !$securimage->check($this->request->data['User']['captcha']))
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

			            	$this->Session->setFlash('Your password has been updated and you have been logged in', 'flash_success');
			            	return $this->redirect($this->Auth->redirect());
	    				}
	    			}
	    		}
			}

	        if (!empty($error))
	        {
	        	$this->Session->setFlash($error, 'flash_error');
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
        	$this->Session->setFlash('No username supplied', 'flash_error');
        	$this->redirect(array(
        		'controller' => 'pages',
        		'action' => 'display',
        		'home'
        	));
    	}

        if ($username != $this->Auth->user('username') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect('/');
        }

    	$this->request->data = $this->User->find('first', array(
    		'conditions' => array(
    			'User.username' => $username
    		),
    		'contain' => array(
    			'Article' => array(
    				'Category',
    				'limit' => 10
    			),
    			'Role',
    			'Comment' => array(
    				'Article',
    				'limit' => 10,
    				'order' => 'created DESC'
    			)
    		)
    	));

        if (empty($this->request->data))
        {
            $this->Session->setFlash('User does not exist.', 'flash_error');
            $this->redirect('/');
        }

        $this->loadModel('Field');

    	$data = $this->Field->getData('User', $this->request->data['User']['id']);

    	$this->set('fields', $data['field_data']);

    	$this->request->data = array_merge($this->request->data, $data['data']);
    }

    public function edit()
    {
    	if (!$this->Auth->user('id'))
    	{
    		$this->Session->setFlash('You must be logged in to access this page.', 'flash_error');
    		$this->redirect(array(
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

            	$this->Session->setFlash('Your account has been updated', 'flash_success');
			}
		}

    	$this->request->data = $this->User->findById($this->Auth->user('id'));
    	$this->request->data['User']['settings'] = json_decode(
    		$this->request->data['User']['settings'],
    		true
    	);

		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$security_options = $this->User->getSecurityOptions($this->SettingValue->findByTitle('Security Question Options'));
		$user_status = $this->SettingValue->findByTitle('User Status');

		$this->loadModel('Theme');

		$themes = $this->Theme->find('list');
		$timezones = $this->getTimeZones();

		$this->loadModel('Field');

		$fields = $this->Field->getFields('User', $this->Auth->user('id'));

		$this->set(compact('security_options', 'themes', 'timezones', 'fields'));

		$this->request->data['Security'] = $this->User->getSecurityAnswers($this->request->data);
    }

    public function quick_search()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->request->is('ajax') && !empty($this->request->data['User']['username'])) {
    		$data = array();
    		$find = $this->User->find('all', array(
    			'conditions' => array(
    				'User.username LIKE' => '%' . $this->request->data['User']['username'] . '%'
    			)
    		));

    		foreach($find as $row)
    		{
                $data[] = array(
                	'id' =>$row['User']['id'],
                	'username' => $row['User']['username']
                );
    		}

    		return json_encode(
    			$data
    		);
    	}
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