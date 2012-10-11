<?php

class UsersController extends AppController {
	public $name = 'Users';

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
	        $this->paginate = array(
	            'order' => 'User.created DESC',
	            'limit' => $this->pageLimit,
	            'contain' => array(
	            	'Role'
	            ),
	            'conditions' => array(
	            	'User.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
	    } else {
	        $this->paginate = array(
	            'order' => 'User.created DESC',
	            'limit' => $this->pageLimit,
	            'contain' => array(
	            	'Role'
	            ),
	            'conditions' => array(
	            	'User.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
	    }
        
        $this->request->data = $this->paginate('User');
	}

	public function admin_add()
	{
		$this->set('roles', $this->User->Role->find('list'));

        if ($this->request->is('post')) {
        	$this->request->data['User']['username'] = $this->slug($this->request->data['User']['username']);

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your role has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your role.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      $this->User->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->User->find('first', array(
	        	'conditions' => array(
	        		'User.id' => $id
	        	),
	        	'fields' => array(
	        		'User.username', 
	        		'User.email',
	        		'User.role_id',
	        		'User.id'
	        		)
	        	)
	        );
	        $this->set('roles', $this->User->Role->find('list'));
	    } else {
	    	unset($this->User->validate['password']);
	    	unset($this->User->validate['username']);
	    	$this->request->data['User']['username'] = $this->slug($this->request->data['User']['username']);
	    	if (empty($this->request->data['User']['password'])) {
	    		unset($this->request->data['User']['password']);
	    	}

	        if ($this->User->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your user has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your user.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->User->id = $id;

	    if (!empty($permanent)) {
	    	$delete = $this->User->delete($id);
	    } else {
	    	$delete = $this->User->saveField('deleted_time', $this->User->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The user `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The user `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_restore($id = null, $title = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->User->id = $id;

	    if ($this->User->saveField('deleted_time', '0000-00-00 00:00:00')) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The user `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The user `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function login() {
		if (!empty($this->request->data)) {
			$status = $this->User->findByUsername($this->request->data['User']['username']);

			if (!empty($status) && $status['User']['status'] == 0) {
				$this->loadModel('SettingValue');
				$user_status = $this->SettingValue->findByTitle('User Status');

				if ($user_status['SettingValue']['data'] == "Email Activation") {
					$custom_msg = ", please visit the link you received in your email in order to login.";
				} elseif ($user_status['SettingValue']['data'] == "Staff Activation") {
					$custom_msg = ", you must wait for an admin to activate your account.";
				} else {
					$custom_msg = null;
				}

				$this->Session->setFlash(
					Configure::read('alert_btn').
					'<strong>Error</strong> Your account is inactive'.$custom_msg,
					 'default', 
					 array(
					 	'class' => 'alert alert-error'
				));
			    return $this->redirect($this->Auth->redirect());
			} else {
				if ($this->Auth->login()) {
					$this->User->id = $this->Auth->user('id');
					$this->User->saveField('login_time', $this->User->dateTime());

					$this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Welcome back '.$this->Auth->User('username').'!', 'default', array('class' => 'alert alert-success'));
				    return $this->redirect($this->Auth->redirect());
				} else {
				    $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Username or password is incorrect', 'default', array('class' => 'alert alert-error'));
				}
			}
		}
	}

    public function logout() {
    	$this->Session->setFlash(
    		Configure::read('alert_btn').
    		'<strong>Success</strong> You have successfully logged out', 
    		'default', 
    		array(
    			'class' => 'alert alert-success'
    	));
        $this->redirect($this->Auth->logout());
    }

	public function register()
	{
		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$this->request->data['SecurityQuestionOptions'] = $this->SettingValue->findByTitle('Security Question Options');
		$user_status = $this->SettingValue->findByTitle('User Status');

		if (!empty($this->request->data['SecurityQuestionOptions']['SettingValue']['data_options'])) {
			foreach (json_decode($this->request->data['SecurityQuestionOptions']['SettingValue']['data_options']) as $row) {
				$security_options[$row] = $row;
			}
		}

		$this->set(compact('security_options'));

        if ($this->request->is('post')) {
        	$this->request->data['User']['security_answers'] = json_encode($this->request->data['Security']);
        	$role = $this->User->Role->findByDefaults('default-member');
        	$this->request->data['User']['role_id'] = $role['Role']['id'];

            if ($this->User->save($this->request->data)) {
            	$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $this->User->id));
            	
	        	if ($user_status['SettingValue']['data'] == "Email Activation") {
	        		$sitename = $this->SettingValue->findByTitle('sitename');
	        		$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');
	        		$email_subject = $this->SettingValue->findByTitle('User Register Email Subject');

	        		$activate_code[0]['activate_code'] = md5(time());
					// $this->User->id = $this->Auth->user('id');
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
						'activate_code' => $activate_code[0]['activate_code']
					));
					$email->send();

                	$this->Session->setFlash(
                		Configure::read('alert_btn').
                		'<strong>Success</strong> Account Created<br />
                		Please visit the link in the email to activate your account.', 
                		'default', 
                		array(
                			'class' => 'alert alert-success'
                	));
	        	} elseif ($user_status['SettingValue']['data'] == "Staff Activation") {

                	$this->Session->setFlash(
                		Configure::read('alert_btn').
                		'<strong>Success</strong> Account Created<br />
                		You cannot login until a staff member has activated your account.', 
                		'default', 
                		array(
                			'class' => 'alert alert-success'
                	));
	        	} else {
	            	$this->Auth->login($this->request->data['User']);

					$this->User->id = $this->Auth->user('id');
					$this->User->saveField('login_time', $this->User->dateTime());
                	$this->Session->setFlash(
                		Configure::read('alert_btn').'<strong>Success</strong> Account Created', 
                		'default', 
                		array(
                			'class' => 'alert alert-success'
                	));
				}

                return $this->redirect(array(
                    'controller' => 'pages',
                    'action' => 'display', 'home'
                ));
            } else {
            	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Account could not be created', 'default', array('class' => 'alert alert-error'));
            }
        }
     }

    public function ajax_check_user()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if($this->RequestHandler->isAjax()) {
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

    	if($this->RequestHandler->isAjax()) {
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

    public function ajax_forgot_password()
    {
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	$data = $this->User->findByUsername($this->request->data['User']['username']);

    	return $data['User']['security_answers'];
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
    		$code_match = json_decode($match['User']['settings']);

    		if ($match['User']['status'] == 1) {
    			return $this->redirect(array('action' => 'login'));
    		}

    		if (!empty($code_match[0]->activate_code) && $code_match[0]->activate_code == $activate_code) {
    			$data['User']['id'] = $match['User']['id'];
    			$data['User']['settings'] = null;
    			$data['User']['status'] = 1;

    			$this->User->save($data);

				$this->Session->setFlash(
                		Configure::read('alert_btn').'<strong>Success</strong> Account Activated. You may now login', 
                		'default', 
                		array(
                			'class' => 'alert alert-success'
            	));

                return $this->redirect(array('action' => 'login'));
    		} else {
				$this->Session->setFlash(
                		Configure::read('alert_btn').'<strong>Error</strong> Incorrect Code Entered', 
                		'default', 
                		array(
                			'class' => 'alert alert-error'
            	));
    		}
    	}
    }

    public function update_password()
    {
    	$this->loadModel('SettingValue');
    	$password_reset = $this->SettingValue->findByTitle('User Password Reset');

    	$this->set(compact('password_reset'));

    	if (!empty($this->params->named['change']) && $this->params->named['change'] == "forgot") {
    		if (!empty($this->params->named['username'])) {
    			$this->request->data['User']['username'] = $this->params->named['username'];
    		}
    		if (!empty($this->params->named['activate'])) {
    			$activate = $this->params->named['activate'];
    			$this->set(compact('activate'));
    		}

    		if (!empty($this->request->data)) {
    			if (!empty($this->request->data['User']['username'])) {
    				$user = $this->User->findByUsername($this->request->data['User']['username']);
    			} else {
    				$user = $this->User->findByEmail($this->request->data['User']['email']);
    			}
				
				if (!$user) {
					$this->Session->setFlash(
	                		Configure::read('alert_btn').'<strong>Error</strong> No user exists with this email', 
	                		'default', 
	                		array(
	                			'class' => 'alert alert-error'
	            	));
				} else {
					if (!empty($activate)) {

					} else {
		        		$sitename = $this->SettingValue->findByTitle('sitename');
		        		$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

		        		$activate_code[0]['activate_code'] = md5(time());
						$this->User->id = $user['User']['id'];

						if (!empty($user['User']['security_answers'])) {
							$existing = json_decode($user['User']['security_answers']);
							foreach($existing as $i => $row) {
								if (!empty($row->question) && !empty($row->answer)) {
									$data[$i] = array(
										'question' => $row->question, 
										'answer' => $row->answer
									);
								}
							}
						}

						$data[0]['activate_code'] = $activate_code[0]['activate_code'];

						$this->User->saveField('security_answers', json_encode($data));

		        		$email = new CakeEmail();

						$email->to($this->request->data['User']['email']);
						$email->from(array(
							$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
						));
						$email->subject($sitename['SettingValue']['data']." - Forgot Password Request");
						if ($this->theme != "Default") {
							// $email->theme($this->theme);
						}
						$email->emailFormat('html');
						$email->template('forgot_password');
						$email->viewVars(array(
							'data' => $user['User'],
							'sitename' => $sitename['SettingValue']['data'],
							'activate_code' => $activate_code[0]['activate_code']
						));
						if ($email->send()) {
							$this->set('activate', true);

							$this->Session->setFlash(
			                		Configure::read('alert_btn').'<strong>Success</strong> An email has been dispatched to continue.', 
			                		'default', 
			                		array(
			                			'class' => 'alert alert-success'
			            	));
						}
					}
				}
    		}
    	} else {
	    	if (!empty($this->request->data)) {

	    	}
	    }
    }

    public function profile($username = null)
    {
    	
    }
}