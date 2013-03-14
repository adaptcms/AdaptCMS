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
						$user_check = $this->User->findByUsername($user['wp_users']['user_nicename']);

						if (empty($user_check))
						{
							$data = array();
							$this->User->create();

							$activate_code = md5(time());

							$data['User']['username'] = $user['wp_users']['user_nicename'];
							$data['User']['password'] = rand() * time();
							$data['User']['password_confirm'] = $data['User']['password'];
							$data['User']['email'] = $user['wp_users']['user_email'];
							$data['User']['created'] = $user['wp_users']['user_registered'];
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
								'name' => $user['wp_users']['display_name'],
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
							$user_data[$user['wp_users']['ID']] = $this->User->id;
						} else {
							$user_data[$user['wp_users']['ID']] = $user_check['User']['id'];
						}
					}
				}

				$pages = $this->User->query('SELECT * FROM ' . $prefix . 'posts WHERE post_type = "page"');

				if (!empty($pages))
				{
					foreach($pages as $page)
					{
						$page_check = $this->User->Page->findByTitle($page['wp_posts']['post_title']);

						if (empty($page_check))
						{
							$data = array();
							$this->User->Page->create();

							$data['Page']['user_id'] = $user_data[$page['wp_posts']['post_author']];
							$data['Page']['created'] = $page['wp_posts']['post_date'];
							$data['Page']['modified'] = $page['wp_posts']['post_modified'];
							$data['Page']['content'] = html_entity_decode($page['wp_posts']['post_content'], ENT_QUOTES, "UTF-8");
							$data['Page']['title'] = $page['wp_posts']['post_title'];

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
						if (!empty($post['wp_posts']['post_title']) && !empty($post['wp_posts']['post_content']))
						{
							$article_check = $this->User->Article->findByTitle($post['wp_posts']['post_title']);

							if (empty($article_check))
							{
								$data = array();
								$this->User->Article->create();

								$data['Article']['user_id'] = $user_data[$post['wp_posts']['post_author']];
								$data['Article']['created'] = $post['wp_posts']['post_date'];
								$data['Article']['modified'] = $post['wp_posts']['post_modified'];
								$data['Article']['publish_time'] = $data['Article']['created'];
								$data['Article']['title'] = $post['wp_posts']['post_title'];
								$data['Article']['settings'] = json_encode(
									array(
										'comment_status' => $post['wp_posts']['comment_status']
									)
								);

								if ($post['wp_posts']['post_status'] == 'publish')
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
									$data['ArticleValue']['data'] = $post['wp_posts']['post_excerpt'] . '<br /><br />' . $post['wp_posts']['post_content'];

									$theBad = array("“","”","‘","’","…","—","–");
									$theGood = array("\"","\"","'","'","...","-","-");
									$data['ArticleValue']['data'] = preg_replace('/[^(\x20-\x7F)]*/', '', html_entity_decode(
										str_replace($theBad, $theGood, $data['ArticleValue']['data']),
										ENT_QUOTES, 
										"UTF-8"
									));

									$this->User->Article->ArticleValue->save($data);

									$success++;

									$posts_data[$post['wp_posts']['ID']] = $this->User->Article->id;
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
						if (!empty($posts_data[$comment['wp_comments']['comment_post_ID']]))
						{
							$data = array();
							$this->User->Article->Comment->create();

							$data['Comment']['article_id'] = $posts_data[$comment['wp_comments']['comment_post_ID']];

							if (!empty($comment['wp_comments']['user_id']))
							{
								$data['Comment']['user_id'] = $user_data[$comment['wp_comments']['user_id']];
							} else {
								$data['Comment']['user_id'] = 0;
							}

							$data['Comment']['comment_text'] = html_entity_decode($comment['wp_comments']['comment_content'], ENT_QUOTES, "UTF-8");
							$data['Comment']['author_name'] = $comment['wp_comments']['comment_author'];
							$data['Comment']['author_email'] = $comment['wp_comments']['comment_author_email'];
							$data['Comment']['author_website'] = $comment['wp_comments']['comment_author_url'];
							$data['Comment']['author_ip'] = $comment['wp_comments']['comment_author_IP'];
							$data['Comment']['active'] = $comment['wp_comments']['comment_approved'];
							$data['Comment']['created'] = $comment['wp_comments']['comment_date'];
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
						$option['wp_options']['option_value'] = html_entity_decode($option['wp_options']['option_value'], ENT_QUOTES, "UTF-8");

						if ($option['wp_options']['option_name'] == 'blogname')
						{
							$setting = $this->SettingValue->findByTitle('Site Name');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option['wp_options']['option_value']))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						} elseif ($option['wp_options']['option_name'] == 'admin_email')
						{
							$setting = $this->SettingValue->findByTitle('Webmaster Email');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option['wp_options']['option_value']))
							{
								$saved = 1;
							} else {
								$saved = 0;
							}
						} elseif ($option['wp_options']['option_name'] == 'users_can_register')
						{
							$setting = $this->SettingValue->findByTitle('Is Registration Open?');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($option['wp_options']['option_value'] == 1)
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
						} elseif ($option['wp_options']['option_name'] == 'posts_per_page')
						{
							$setting = $this->SettingValue->findByTitle('Number of Items Per Page');

							$this->SettingValue->id = $setting['SettingValue']['id'];
							
							if ($this->SettingValue->saveField('data', $option['wp_options']['option_value']))
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