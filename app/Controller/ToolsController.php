<?php

class ToolsController extends AppController
{
    /**
    * Name of the Controller, 'Tools'
    */
	public $name = 'Tools';

	/**
	* We do many Database things, so we will load models manually. So no uses by default.
	*/
	public $uses = array();

	/**
	* Our Admin Index is a listing to our tools, so no data passed to the view.
	*
	* @return none
	*/
	public function admin_index()
	{
	}

	/**
	* This will use Cakes built in clearCache functionality to clear all cache excluding the system. (where component and helper list is stored) 
	*
	* @return redirect
	*/
	public function admin_clear_cache()
	{
		$total_count = 0;
		$success_count = 0;
		$folders = array('persistent', 'models', 'views');

		foreach($folders as $folder) {
			if (clearCache(null, $folder)) {
				$success_count++;
			}

			$total_count++;
		}

		if ($success_count == $total_count && $success_count > 0) {
			$this->Session->setFlash('Cache has been cleared.', 'flash_success');
		} else {
			$this->Session->setFlash('Cache could not be cleared.', 'flash_error');
		}

		$this->redirect(
			array(
				'action' => 'index'
			)
		);
	}

	/**
	* A fairly simple method that first gets the Database connection and gets a list of all tables.
	* We then loop through the tables, do a check - if not OK, then attempt repair. Attempt to analyze and if needed, optimize table.
	* The affected tables are not just adaptcms, all tables in the database are included.
	*
	* @return array of messages - a note by each table will appear
	*/
	public function admin_optimize_database()
	{
		$db = ConnectionManager::getDataSource('default');
		$tables = $db->listSources();

		$this->loadModel('User');

		$messages = array();
		if (!empty($tables)) {
			foreach($tables as $table) {
				$check = $this->User->query('CHECK TABLE ' . $table);
				
				if ($check[0][0]['Msg_text'] == 'OK') {
					$messages[$table]['check'] = 1;
				} else {
					$messages[$table]['check'] = $check[0][0]['Msg_text'];

					$repair = $this->User->query('REPAIR TABLE ' . $table);

					if ($repair[0][0]['Msg_text'] == 'OK') {
						$messages[$table]['repair'] = 1;
					} else {
						$messages[$table]['repair'] = $repair[0][0]['Msg_text'];
					}
				}

				if ($messages[$table]['check'] == 1 || !empty($messages[$table]['repair']) && $messages[$table]['repair'] == 1) {
					$analyze = $this->User->query('ANALYZE TABLE ' . $table);

					if ($analyze[0][0]['Msg_text'] == 'Table is already up to date') {
						$messages[$table]['analyze'] = 1;
					} else {
						$messages[$table]['analyze'] = $analyze[0][0]['Msg_text'];

						$optimize = $this->User->query('OPTIMIZE TABLE ' . $table);

						if ($optimize[0][0]['Msg_text'] == 'OK' || $optimize[0][0]['Msg_text'] == 'Table is already up to date') {
							$messages[$table]['optimize'] = 1;
						} else {
							$messages[$table]['optimize'] = $optimize[0][0]['Msg_text'];
						}
					}
				}
			}
		}

		$this->set(compact('messages'));
	}

	public function admin_convert_adaptcms()
	{
		if (!empty($this->request->data))
		{
			$this->loadModel('User');
			$prefix = $this->request->data['Convert']['prefix'];

			$check = $this->User->query('CHECK TABLE ' . $prefix . 'sections');

			if ($check[0][0]['Msg_text'] != 'OK')
			{
				$this->Session->setFlash(
					'Cannot detect AdaptCMS 2.x install. Ensure DB Prefix is correct and is in same database as AdaptCMS ' . ADAPTCMS_VERSION, 
					'flash_error'
				);
			}
			else
			{
				$user_data = array();
				$secion_data = array();
				$field_data = array();

				$success = 0;
				$total = 0;
				$users = $this->User->query('SELECT * FROM ' . $prefix . 'users');

				if (!empty($users))
				{
					$member = $this->User->Role->findByDefaults('default-member');

					foreach($users as $user)
					{
						$user_check = $this->User->findByUsername($user[$prefix . 'users']['username']);

						if (empty($user_check))
						{
							$data = array();
							$this->User->create();

							$activate_code = md5(time());

							$data['User']['username'] = $user[$prefix . 'users']['username'];
							$data['User']['password'] = rand() * time();
							$data['User']['password_confirm'] = $data['User']['password'];
							$data['User']['email'] = $user[$prefix . 'users']['email'];
							$data['User']['created'] = $user[$prefix . 'users']['reg_date'];
							$data['User']['status'] = 1;
							$data['User']['role_id'] = $member['Role']['id'];
							$data['Security'] = array(
								0 => array(
									'question' => 'What was your mothers maiden name?',
									'answer' => 'Mother'
								),
								1 => array(
									'question' => 'Your favorite sport?',
									'answer' => 'Sport'
								)
							);
							$data['User']['settings'] = array(
								'activate_code' => $activate_code
							);
							$data['User']['theme_id'] = 1;

							if ($this->User->save($data))
							{
				    //     		$email = new CakeEmail();

								// $email->to($data['User']['email']);
								// $email->from(array(
								// 	$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
								// ));
								// $email->subject('Reset Password Notification');
								// $email->emailFormat('html');
								// $email->template('forgot_password');
								// $email->viewVars(array(
								// 	'data' => $data['User'],
								// 	'activate_code' => $activate_code
								// ));
								// $email->send();

								$success++;
							}

							$total++;
							$user_data[$user[$prefix . 'users']['id']] = $this->User->id;
						} else {
							$user_data[$user[$prefix . 'users']['id']] = $user_check['User']['id'];
						}
					}
				}	

				$sections = $this->User->query('SELECT * FROM ' . $prefix . 'sections');

				if (!empty($sections))
				{
					foreach($sections as $section)
					{
						$section_check = $this->User->Category->findByTitle($section[$prefix . 'sections']['name']);

						if (empty($section_check))
						{
							$data = array();
							$this->User->Category->create();

							$data['Category']['user_id'] = $this->Auth->user('id');
							$data['Category']['created'] = $this->User->dateTime();
							$data['Category']['title'] = $section[$prefix . 'sections']['name'];

							if ($this->User->Category->save($data))
							{
								$section_data[$section[$prefix . 'sections']['name']] = $this->User->Category->id;
								$success++;
							}

							$total++;
						}
						else
						{
							$section_data[$section[$prefix . 'sections']['name']] = $section_check['Category']['id'];
						}
					}
				}

				$field_types = array(
					'file' => 'file',
					'select' => 'dropdown',
					'textfield' => 'text',
					'textarea' => 'textarea'
				);

				$fields = $this->User->query('SELECT * FROM ' . $prefix . 'fields');

				if (!empty($fields))
				{
					foreach($fields as $field)
					{
						$field_check = $this->User->Category->Field->findByTitle($field[$prefix . 'fields']['name']);

						if (empty($field_check) && $field[$prefix . 'fields']['section'] != 'user-profile' && !empty($section_data[$field[$prefix . 'fields']['section']]))
						{
							$data = array();
							$this->User->Category->Field->create();

							$data['Field']['user_id'] = $this->Auth->user('id');
							$data['Field']['created'] = $this->User->dateTime();
							$data['Field']['title'] = $this->slug($field[$prefix . 'fields']['name']);
							$data['Field']['category_id'] = $section_data[$field[$prefix . 'fields']['section']];
							$data['Field']['field_type'] = $field_types[$field[$prefix . 'fields']['type']];
							$data['Field']['description'] = $field[$prefix . 'fields']['description'];

							if (!empty($field[$prefix . 'fields']['data']))
							{
								$field_options = json_encode( explode(',', $field[$prefix . 'fields']['data']) );
								$data['Field']['field_options'] = $field_options;
							}

							if (!empty($field[$prefix . 'fields']['required']))
							{
								$data['Field']['required'] = 1;
							}

							if ($this->User->Category->Field->save($data))
							{
								$field_data[$field[$prefix . 'fields']['name']] = $this->User->Category->Field->id;
								$success++;
							}

							$total++;
						}
						elseif (!empty($field_check))
						{
							$field_data[$field[$prefix . 'fields']['name']] = $field_check['Field']['id'];
						}
					}
				}

				$content = $this->User->query('SELECT * FROM ' . $prefix . 'content');

				if (!empty($content))
				{
					foreach($content as $article)
					{
						$article_check = $this->User->Category->Article->findByTitle($article[$prefix . 'content']['name']);

						if (empty($article_check))
						{
							if (!empty($section_data[$article[$prefix . 'content']['section']]))
							{
								$data = array();
								$this->User->Category->Article->create();

								if (!empty($article[$prefix . 'content']['user_id']))
								{
									$data['Article']['user_id'] = $user_data[$article[$prefix . 'content']['user_id']];
								}
								else
								{
									$data['Article']['user_id'] = $this->Auth->user('id');
								}

								$data['Article']['created'] = $this->User->dateTime($article[$prefix . 'content']['date']);
								$data['Article']['modified'] = $this->User->dateTime($article[$prefix . 'content']['last_edit']);
								$data['Article']['title'] = $article[$prefix . 'content']['name'];
								$data['Article']['category_id'] = $section_data[$article[$prefix . 'content']['section']];
								$data['Article']['status'] = 1;
								$data['Article']['publish_time'] = $data['Article']['created'];

								if ($this->User->Category->Article->save($data))
								{
									$article_data[$article[$prefix . 'content']['id']] = $this->User->Category->Article->id;
									$success++;
								}

								$total++;
							}
						}
						else
						{
							$article_data[$article[$prefix . 'content']['id']] = $article_check['Article']['id'];
						}
					}
				}

				$comments = $this->User->query('SELECT * FROM ' . $prefix . 'comments');

				if (!empty($comments))
				{
					foreach($comments as $comment)
					{
						if (!empty($article_data[$comment[$prefix . 'comments']['article_id']]))
						{
							$data = array();
							$this->User->Category->Article->Comment->create();

							$data['Comment']['article_id'] = $article_data[$comment[$prefix . 'comments']['article_id']];

							if (!empty($user_data[$comment[$prefix . 'comments']['user_id']]))
							{
								$data['Comment']['user_id'] = $user_data[$comment[$prefix . 'comments']['user_id']];
							}

							$data['Comment']['comment_text'] = $comment[$prefix . 'comments']['comment'];
							
							if (!empty($comment[$prefix . 'comments']['email']))
							{
								$data['Comment']['author_email'] = $comment[$prefix . 'comments']['email'];
							}

							if (!empty($comment[$prefix . 'comments']['website']))
							{
								$data['Comment']['author_website'] = $comment[$prefix . 'comments']['website'];
							}

							if (!empty($comment[$prefix . 'comments']['ip']))
							{
								$data['Comment']['author_ip'] = $comment[$prefix . 'comments']['ip'];
							}

							$data['Comment']['active'] = 1;
							$data['Comment']['created'] = $this->User->dateTime($comment[$prefix . 'comments']['date']);
							$data['Comment']['modified'] = $this->User->dateTime($comment[$prefix . 'comments']['date']);

							if ($this->User->Category->Article->Comment->save($data))
							{
								$success++;
							}

							$total++;
						}
					}
				}

				$values = $this->User->query('SELECT * FROM ' . $prefix . 'data WHERE field_type = "content-custom-data"');

				if (!empty($values))
				{
					foreach($values as $row)
					{
						if (!empty($field_data[$row[$prefix . 'data']['field_name']]) && !empty($field_data[$row[$prefix . 'data']['field_name']]))
						{
							$data = array();
							$this->User->Category->Article->ArticleValue->create();

							$data['ArticleValue']['article_id'] = $article_data[$row[$prefix . 'data']['item_id']];
							$data['ArticleValue']['field_id'] = $field_data[$row[$prefix . 'data']['field_name']];
							$data['ArticleValue']['file_id'] = 0;
							$data['ArticleValue']['data'] = stripslashes($row[$prefix . 'data']['data']);

							if ($this->User->Category->Article->ArticleValue->save($data))
							{
								$success++;
							}

							$total++;
						}
					}
				}

				if ($success == $total)
				{
					$this->Session->setFlash('AdaptCMS 2.x data has been converted.', 'flash_success');
					$this->redirect(
						array(
							'action' => 'index'
						)
					);
				} else {
					$this->Session->setFlash('AdaptCMS 2.x data could not be converted.', 'flash_error');
				}
			}
		}
	}

	/**
	* This is a big one. After we get the wordpress prefix, category the content will be copied to and textfield for post content
	* we then loop through each wordpress item and save it. Currently this includes pages, posts, users (password is reset with email),
	* some site options and comments.
	*
	* @return redirect on success
	*/
	public function admin_convert_wordpress()
	{
		$this->loadModel('User');

		$content_fields = $this->User->Article->Category->Field->find('all', array(
			'conditions' => array(
				'Field.field_type' => 'textarea'
			),
			'contain' => array(
				'Category'
			)
		));

		$fields = array();
		foreach($content_fields as $field)
		{
			$fields[$field['Field']['id']] = $field['Field']['title'] . ' (' . $field['Category']['title'] . ')';
		}

		$categories = $this->User->Article->Category->find('list');

		$this->set(compact('fields', 'categories'));

		if (!empty($this->request->data))
		{
			$prefix = $this->request->data['Convert']['prefix'];

			$check = $this->User->query('CHECK TABLE ' . $prefix . 'comments');

			if ($check[0][0]['Msg_text'] != 'OK')
			{
				$this->Session->setFlash('Cannot detect wordpress install. Ensure DB Prefix is correct and wordpress is in same database as AdaptCMS.', 'flash_error');
			} else {
				$user_data = array();
				$success = 0;
				$total = 0;
				$users = $this->User->query('SELECT * FROM ' . $prefix . 'users');

				$member = $this->User->Role->findByDefaults('default-member');

				$this->loadModel('SettingValue');

				if (!empty($users))
				{
	        		$sitename = $this->SettingValue->findByTitle('Site Name');
	        		$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

					foreach($users as $user)
					{
						$user_check = $this->User->findByUsername($user[$prefix . 'users']['user_nicename']);

						if (empty($user_check))
						{
							$data = array();
							$this->User->create();

							$activate_code = md5(time());

							$data['User']['username'] = $user[$prefix . 'users']['user_nicename'];
							$data['User']['password'] = rand() * time();
							$data['User']['password_confirm'] = $data['User']['password'];
							$data['User']['email'] = $user[$prefix . 'users']['user_email'];
							$data['User']['created'] = $user[$prefix . 'users']['user_registered'];
							$data['User']['status'] = 1;
							$data['User']['role_id'] = $member['Role']['id'];
							$data['Security'] = array(
								0 => array(
									'question' => 'What was your mothers maiden name?',
									'answer' => 'Mother'
								),
								1 => array(
									'question' => 'Your favorite sport?',
									'answer' => 'Sport'
								)
							);
							$data['User']['settings'] = array(
								'name' => $user[$prefix . 'users']['display_name'],
								'activate_code' => $activate_code
							);
							$data['User']['theme_id'] = 1;

							if ($this->User->save($data))
							{
				        		$email = new CakeEmail();

								$email->to($data['User']['email']);
								$email->from(array(
									$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
								));
								$email->subject('Reset Password Notification');
								$email->emailFormat('html');
								$email->template('forgot_password');
								$email->viewVars(array(
									'data' => $data['User'],
									'activate_code' => $activate_code
								));
								$email->send();

								$success++;
							}

							$total++;
							$user_data[$user[$prefix . 'users']['ID']] = $this->User->id;
						} else {
							$user_data[$user[$prefix . 'users']['ID']] = $user_check['User']['id'];
						}
					}
				}

				$pages = $this->User->query('SELECT * FROM ' . $prefix . 'posts WHERE post_type = "page"');

				if (!empty($pages))
				{
					foreach($pages as $page)
					{
						$page_check = $this->User->Page->findByTitle($page[$prefix . 'posts']['post_title']);

						if (empty($page_check))
						{
							$data = array();
							$this->User->Page->create();

							$data['Page']['user_id'] = $user_data[$page[$prefix . 'posts']['post_author']];
							$data['Page']['created'] = $page[$prefix . 'posts']['post_date'];
							$data['Page']['modified'] = $page[$prefix . 'posts']['post_modified'];
							$data['Page']['content'] = html_entity_decode($page[$prefix . 'posts']['post_content'], ENT_QUOTES, "UTF-8");
							$data['Page']['title'] = $page[$prefix . 'posts']['post_title'];

							if ($this->User->Page->save($data))
							{
								$success++;
							}

							$total++;
						}
					}
				}

				$posts = $this->User->query('SELECT * FROM ' . $prefix . 'posts WHERE post_type = "post"');

				if (!empty($posts))
				{
					$posts_data = array();

					foreach($posts as $post)
					{
						if (!empty($post[$prefix . 'posts']['post_title']) && !empty($post[$prefix . 'posts']['post_content']))
						{
							$article_check = $this->User->Article->findByTitle($post[$prefix . 'posts']['post_title']);

							if (empty($article_check))
							{
								$data = array();
								$this->User->Article->create();

								$data['Article']['user_id'] = $user_data[$post[$prefix . 'posts']['post_author']];
								$data['Article']['created'] = $post[$prefix . 'posts']['post_date'];
								$data['Article']['modified'] = $post[$prefix . 'posts']['post_modified'];
								$data['Article']['publish_time'] = $data['Article']['created'];
								$data['Article']['title'] = $post[$prefix . 'posts']['post_title'];
								$data['Article']['settings'] = json_encode(
									array(
										'comment_status' => $post[$prefix . 'posts']['comment_status']
									)
								);

								if ($post[$prefix . 'posts']['post_status'] == 'publish')
								{
									$data['Article']['status'] = 1;
								} else {
									$data['Article']['status'] = 0;
								}

								$data['Article']['category_id'] = $this->request->data['Convert']['category'];

								if ($this->User->Article->save($data))
								{
									$data = array();
									$this->User->Article->ArticleValue->create();

									$data['ArticleValue']['article_id'] = $this->User->Article->id;
									$data['ArticleValue']['file_id'] = 0;
									$data['ArticleValue']['field_id'] = $this->request->data['Convert']['field'];
									$data['ArticleValue']['data'] = $post[$prefix . 'posts']['post_excerpt'] . '<br /><br />' . $post[$prefix . 'posts']['post_content'];

									$theBad = array("“","”","‘","’","…","—","–");
									$theGood = array("\"","\"","'","'","...","-","-");
									$data['ArticleValue']['data'] = preg_replace('/[^(\x20-\x7F)]*/', '', html_entity_decode(
										str_replace($theBad, $theGood, $data['ArticleValue']['data']),
										ENT_QUOTES, 
										"UTF-8"
									));

									$this->User->Article->ArticleValue->save($data);

									$success++;

									$posts_data[$post[$prefix . 'posts']['ID']] = $this->User->Article->id;
								}

								$total++;
							}
						}
					}
				}

				$comments = $this->User->query('SELECT * FROM ' . $prefix . 'comments');

				if (!empty($comments))
				{
					foreach($comments as $comment)
					{
						if (!empty($posts_data[$comment[$prefix . 'comments']['comment_post_ID']]))
						{
							$data = array();
							$this->User->Article->Comment->create();

							$data['Comment']['article_id'] = $posts_data[$comment[$prefix . 'comments']['comment_post_ID']];

							if (!empty($comment[$prefix . 'comments']['user_id']))
							{
								$data['Comment']['user_id'] = $user_data[$comment[$prefix . 'comments']['user_id']];
							} else {
								$data['Comment']['user_id'] = 0;
							}

							$data['Comment']['comment_text'] = html_entity_decode($comment[$prefix . 'comments']['comment_content'], ENT_QUOTES, "UTF-8");
							$data['Comment']['author_name'] = $comment[$prefix . 'comments']['comment_author'];
							$data['Comment']['author_email'] = $comment[$prefix . 'comments']['comment_author_email'];
							$data['Comment']['author_website'] = $comment[$prefix . 'comments']['comment_author_url'];
							$data['Comment']['author_ip'] = $comment[$prefix . 'comments']['comment_author_IP'];
							$data['Comment']['active'] = $comment[$prefix . 'comments']['comment_approved'];
							$data['Comment']['created'] = $comment[$prefix . 'comments']['comment_date'];
							$data['Comment']['comment_parent'] = 0;

							if ($this->User->Article->Comment->save($data))
							{
								$success++;
							}

							$total++;
						}
					}
				}

				$options_values = "'blogname', 'admin_email', 'users_can_register', 'posts_per_page'";
				$options = $this->User->query('SELECT * FROM ' . $prefix . 'options WHERE option_name IN (' . $options_values . ')');

				if (!empty($options))
				{
					foreach($options as $option)
					{
						$option[$prefix . 'options']['option_value'] = html_entity_decode($option[$prefix . 'options']['option_value'], ENT_QUOTES, "UTF-8");

						if ($option[$prefix . 'options']['option_name'] == 'blogname')
						{
							$setting = $this->SettingValue->findByTitle('Site Name');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option[$prefix . 'options']['option_value']))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						} elseif ($option[$prefix . 'options']['option_name'] == 'admin_email')
						{
							$setting = $this->SettingValue->findByTitle('Webmaster Email');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option[$prefix . 'options']['option_value']))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						} elseif ($option[$prefix . 'options']['option_name'] == 'users_can_register')
						{
							$setting = $this->SettingValue->findByTitle('Is Registration Open?');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($option[$prefix . 'options']['option_value'] == 1)
							{
								$value = 'Yes';
							} else {
								$value = 'No';
							}

							if ($this->SettingValue->saveField('data', $value))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						} elseif ($option[$prefix . 'options']['option_name'] == 'posts_per_page')
						{
							$setting = $this->SettingValue->findByTitle('Number of Items Per Page');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option[$prefix . 'options']['option_value']))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						}

						if ($saved == 1)
						{
							$success++;
						}

						$total++;
					}
				}

				if ($success == $total)
				{
					$this->Session->setFlash('Wordpress data has been converted.', 'flash_success');
					$this->redirect(
						array(
							'action' => 'index'
						)
					);
				} else {
					$this->Session->setFlash('Wordpress data could not be converted.', 'flash_error');
				}
			}
		}
	}
}