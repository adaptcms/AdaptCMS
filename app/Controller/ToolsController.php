<?php
App::uses('AppController', 'Controller');

/**
 * Class ToolsController
 *
 * @property Plugin $Plugin
 */
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

	public $disable_parsing = true;

	/**
	 * Before Filter
	 *
	 * @return null|void
	 */
	public function beforeFilter()
	{
		$this->Security->unlockedActions = array('admin_create_plugin', 'admin_create_theme');

		parent::beforeFilter();
	}

	/**
	* Our Admin Index is a listing to our tools, so no data passed to the view.
	*
	* @return void
	*/
	public function admin_index()
	{
	}

	/**
	* This will use Cakes built in clearCache functionality to clear all cache excluding the system. (where component and helper list is stored) 
	*
	* @return void
	*/
	public function admin_clear_cache()
	{
		$total_count = 0;
		$success_count = 0;
		$folders = array('persistent', 'persistent/api', 'models', 'views', '/../templates');

		foreach($folders as $folder) {
			if (clearCache(null, $folder)) {
				$success_count++;
			}

			$total_count++;
		}

        if (function_exists('apc_clear_cache'))
        {
            apc_clear_cache();
            apc_clear_cache('user');
            apc_clear_cache('opcode');
        }

		if ($success_count == $total_count && $success_count > 0) {
			$this->Session->setFlash('Cache has been cleared.', 'success');
		} else {
			$this->Session->setFlash('Cache could not be cleared.', 'error');
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
					'error'
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

				$this->loadModel('SettingValue');

				$sitename = $this->SettingValue->findByTitle('Site Name');
				$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

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

				$field_types['file'] = $this->User->Field->FieldType->findBySlug('file');
				$field_types['select'] = $this->User->Field->FieldType->findBySlug('dropdown');
				$field_types['textfield'] = $this->User->Field->FieldType->findBySlug('text');
				$field_types['textarea'] = $this->User->Field->FieldType->findBySlug('textarea');

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
							$data['Field']['title'] = $this->User->slug($field[$prefix . 'fields']['name']);
							$data['Field']['category_id'] = $section_data[$field[$prefix . 'fields']['section']];
							$data['Field']['field_type_id'] = $field_types[$field[$prefix . 'fields']['type']]['FieldType']['id'];
							$data['Field']['field_type_slug'] = $field_types[$field[$prefix . 'fields']['type']]['FieldType']['slug'];
							$data['Field']['description'] = $field[$prefix . 'fields']['description'];

							if (!empty($field[$prefix . 'fields']['data']))
							{
								$field_options = json_encode( explode(',', $field[$prefix . 'fields']['data']) );
								$data['Field']['field_options'] = strip_tags($field_options);
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
						if (!empty($field_data[$row[$prefix . 'data']['field_name']]) &&
                            !empty($field_data[$row[$prefix . 'data']['field_name']]) &&
                            !empty($article_data[$row[$prefix . 'data']['item_id']]))
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
					$this->Session->setFlash('AdaptCMS 2.x data has been converted.', 'success');
					$this->redirect(
						array(
							'action' => 'index'
						)
					);
				} else {
					$this->Session->setFlash('AdaptCMS 2.x data could not be converted.', 'error');
				}
			}
		}
	}

	/**
	* This is a big one. After we get the wordpress prefix, category the content will be copied to and textfield for post content
	* we then loop through each wordpress item and save it. Currently this includes pages, posts, users (password is reset with email),
	* some site options and comments.
	*
	* @return void on success
	*/
	public function admin_convert_wordpress()
	{
		$this->loadModel('User');

		$textarea = $this->User->Field->FieldType->findBySlug('textarea');

		$content_fields = $this->User->Article->Category->Field->find('all', array(
			'conditions' => array(
				'Field.field_type_id' => $textarea['FieldType']['id']
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
				$this->Session->setFlash('Cannot detect wordpress install. Ensure DB Prefix is correct and wordpress is in same database as AdaptCMS.', 'error');
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
					$this->Session->setFlash('Wordpress data has been converted.', 'success');
					$this->redirect(
						array(
							'action' => 'index'
						)
					);
				} else {
					$this->Session->setFlash('Wordpress data could not be converted.', 'error');
				}
			}
		}
	}

	/**
	 * Admin Convert Onecms
	 *
	 * @return void
	 */
	public function admin_convert_onecms()
	{
		$this->loadModel('User');

		if (!empty($this->request->data['Convert']['prefix']))
		{
			$prefix = $this->request->data['Convert']['prefix'];

			$check = $this->User->query('CHECK TABLE ' . $prefix . 'games');

			if ($check[0][0]['Msg_text'] != 'OK')
			{
				$this->Session->setFlash('Cannot detect onecms install. Ensure DB Prefix is correct and onecms is in same database as AdaptCMS.', 'error');
			} else {
				$success = 0;
				$total = 0;

				// Users
				$user_data = array();
				$users = $this->User->query('SELECT * FROM ' . $prefix . 'users');

				$this->loadModel('SettingValue');

				$roles = array();
				if (!empty($users))
				{
					$sitename = $this->SettingValue->findByTitle('Site Name');
					$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

					foreach($users as $user)
					{
						if (!empty($user[$prefix . 'users']['username'])) {
							$user_check = $this->User->findByUsername($user[$prefix . 'users']['username']);

							if (empty($user_check))
							{
								if (empty($roles[$user[$prefix . 'users']['level']])) {
									$conditions = array();
									switch($user[$prefix . 'users']['level']) {
										case 'Super Admin':
											$conditions['Role.defaults'] = 'default-admin';
											break;
										case 'Super Staff':
											$conditions['OR']['Role.title LIKE'] = '%staff%';
											$conditions['OR']['Role.defaults IS NULL'];
											break;
										case 'Staff':
											$conditions['OR']['Role.title LIKE'] = '%staff%';
											$conditions['OR']['Role.defaults IS NULL'];
											break;
										case 'Member':
											$conditions['Role.defaults'] = 'default-member';
											break;

									}

									$role = $this->User->Role->find('first', array('conditions' => $conditions));

									if (!empty($role)) {
										$roles[$user[$prefix . 'users']['level']] = $role['Role']['id'];
									}
								}


								$data = array();
								$this->User->create();

								$activate_code = md5(time());

								$data['User']['username'] = $user[$prefix . 'users']['username'];
								$data['User']['password'] = rand() * time();
								$data['User']['password_confirm'] = $data['User']['password'];
								$data['User']['email'] = $user[$prefix . 'users']['email'];
								$data['User']['status'] = 1;
								$data['User']['role_id'] = $roles[$user[$prefix . 'users']['level']];
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
									/*
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
									*/

									$success++;
								}

								$total++;
								$user_data[$user[$prefix . 'users']['username']] = $this->User->id;
							} else {
								$user_data[$user[$prefix . 'users']['username']] = $user_check['User']['id'];
							}
						}
					}
				}

				// Systems
				$category_system_check = $this->User->Category->find('first', array(
					'conditions' => array(
						'Category.title LIKE' => '%System%'
					)
				));

				if (!empty($category_system_check)) {
					$category_systems = $category_system_check['Category']['id'];
				} else {
					$this->User->Category->create();

					$category['Category']['title'] = 'Systems';
					$category['Category']['user_id'] = $this->Auth->user('id');

					if ($this->User->Category->save($category)) {
						$category_systems = $this->User->Category->id;
					}
				}

				$game_systems = $this->User->query('SELECT * FROM ' . $prefix . 'systems');

				$systems = array();
				if (!empty($game_systems) && !empty($category_systems))
				{
					foreach($game_systems as $system)
					{
						if (!empty($system[$prefix . 'systems']['name'])) {
							$system_check = $this->User->Article->find('first', array(
								'conditions' => array(
									'Article.title' => $system[$prefix . 'systems']['name'],
									'Article.category_id' => $category_systems
								)
							));

							if (empty($system_check))
							{
								$data = array();
								$this->User->Article->create();

								$data['Article']['user_id'] = $this->Auth->user('id');
								$data['Article']['category_id'] = $category_systems;
								$data['Article']['title'] = $system[$prefix . 'systems']['name'];
								$data['Article']['status'] = 1;

								if ($this->User->Article->save($data))
								{
									$systems[$system[$prefix . 'systems']['id']] = $this->User->Article->id;
									$success++;
								}

								$total++;
							}
						}
					}
				}

				// Games
				$category_game_check = $this->User->Category->find('first', array(
					'conditions' => array(
						'Category.title LIKE' => '%Game%'
					)
				));

				if (!empty($category_game_check)) {
					$category_games = $category_game_check['Category']['id'];
				} else {
					$this->User->Category->create();

					$category['Category']['title'] = 'Games';
					$category['Category']['user_id'] = $this->Auth->user('id');

					if ($this->User->Category->save($category)) {
						$category_games = $this->User->Category->id;
					}
				}

				$field_types = array();
				$textfield = $this->User->Field->FieldType->findBySlug('text');
				$textarea = $this->User->Field->FieldType->findBySlug('textarea');

				$field_types['textfield'] = $textfield['FieldType'];
				$field_types['textarea'] = $textarea['FieldType'];

				$game_fields_check = array(
					'publisher' => 'publisher',
					'developer' => 'developer',
					'genre' => 'genre',
					'esrb' => 'esrb',
					'des' => 'description'
				);
				$game_fields = array();
				foreach($game_fields_check as $key => $field) {
					$find = $this->User->Field->find('first', array(
						'conditions' => array(
							'Field.title' => $field,
							'Field.category_id' => $category_games
						)
					));

					if (!empty($find)) {
						$find['Field']['key'] = $key;
						$game_fields[] = $find['Field'];
					} else {
						$this->User->Field->create();

						$data['Field']['title'] = $field;
						$data['Field']['category_id'] = $category_games;

						if ($key == 'des') {
							$data['Field']['field_type_id'] = $field_types['textarea']['id'];
							$data['Field']['field_type_slug'] = $field_types['textarea']['slug'];
						} else {
							$data['Field']['field_type_id'] = $field_types['textfield']['id'];
							$data['Field']['field_type_slug'] = $field_types['textfield']['slug'];
						}

						$data['Field']['user_id'] = $this->Auth->user('id');

						if ($this->User->Field->save($data)) {
							$data['Field']['id'] = $this->User->Field->id;
							$data['Field']['key'] = $key;
							$game_fields[] = $data['Field'];
						}
					}
				}

				$games = $this->User->query('SELECT * FROM ' . $prefix . 'games');

				$games_data = array();
				if (!empty($games) && !empty($category_games))
				{
					foreach($games as $game)
					{
						if (!empty($game[$prefix . 'games']['name'])) {
							$game_check = $this->User->Article->find('first', array(
								'conditions' => array(
									'Article.title' => $game[$prefix . 'games']['name'],
									'Article.category_id' => $category_games
								)
							));

							if (empty($game_check))
							{
								$data = array();
								$this->User->Article->create();

								if (!empty($user_data[$game[$prefix . 'games']['username']])) {
									$data['Article']['user_id'] = $user_data[$game[$prefix . 'games']['username']];
								} else {
									$data['Article']['user_id'] = $this->Auth->user('id');
								}

								$data['Article']['category_id'] = $category_games;
								$data['Article']['title'] = $game[$prefix . 'games']['name'];
								$data['Article']['status'] = 1;

								if (!empty($game_fields)) {
									foreach($game_fields as $key => $field) {
										$data['ArticleValue'][$key]['field_id'] = $field['id'];
										$data['ArticleValue'][$key]['data'] = $game[$prefix . 'games'][$field['key']];
									}
								}

								if (!empty($systems[$game[$prefix . 'games']['system']])) {
									$data['RelatedData'] = array($systems[$game[$prefix . 'games']['system']]);
								}

								if ($this->User->Article->saveAssociated($data))
								{
									$games_data[$game[$prefix . 'games']['id']] = $this->User->Article->id;
									$success++;
								}

								$total++;
							} else {
								$games_data[$game[$prefix . 'games']['id']] = $game_check['Article']['id'];
							}
						}
					}
				}

				// Categories
				$categories = $this->User->query('SELECT * FROM ' . $prefix . 'cat');

				$categories_data = array();
				if (!empty($categories))
				{
					foreach($categories as $category)
					{
						if (!empty($category[$prefix . 'cat']['name'])) {
							$category_check = $this->User->Category->findBySlug($category[$prefix . 'cat']['name']);

							if (empty($category_check))
							{
								$data = array();
								$this->User->Category->create();

								$data['Category']['user_id'] = $this->Auth->user('id');
								$data['Category']['title'] = $category[$prefix . 'cat']['name'];

								if ($this->User->Category->save($data))
								{
									$categories_data[$category[$prefix . 'cat']['name']] = $this->User->Category->id;
									$success++;
								}

								$total++;
							} else {
								$categories_data[$category[$prefix . 'cat']['name']] = $category_check['Category']['id'];
							}
						}
					}
				}

				// Fields
				$fields = $this->User->query('SELECT * FROM ' . $prefix . 'fields');

				$fields_data = array();
				$invalid_field_types = array(
					'games',
					'system',
					'systems',
					'company',
					'album'
				);
				if (!empty($fields))
				{
					foreach($fields as $field)
					{
						if (!empty($field[$prefix . 'fields']['name'])) {
							$field_check = $this->User->Field->findByTitle($field[$prefix . 'fields']['name']);

							if (empty($field_check) && !in_array($field[$prefix . 'fields']['type'], $invalid_field_types))
							{
								$data = array();
								$this->User->Field->create();

								$data['Field']['user_id'] = $this->Auth->user('id');
								$data['Field']['title'] = $field[$prefix . 'fields']['name'];
								$data['Field']['label'] = $field[$prefix . 'fields']['name'];
								$data['Field']['description'] = $field[$prefix . 'fields']['des'];

								if (!empty($categories_data[$field[$prefix . 'fields']['cat']])) {
									$data['Field']['category_id'] = $categories_data[$field[$prefix . 'fields']['cat']];
								} else {
									$category = $this->User->Category->find('first');

									$data['Field']['category_id'] = $category['Category']['id'];
								}

								if (empty($field_types[$field[$prefix . 'fields']['type']])) {
									if ($field[$prefix . 'fields']['type'] == 'textarea') {
										$field_type_name = 'textarea';
									} else {
										$field_type_name = 'text';
									}

									$field_type = $this->User->Field->FieldType->findBySlug($field_type_name);
									$field_types[$field[$prefix . 'fields']['type']] = $field_type['FieldType'];
								}

								$data['Field']['field_type_id'] = $field_types[$field[$prefix . 'fields']['type']]['id'];
								$data['Field']['field_type_slug'] = $field_types[$field[$prefix . 'fields']['type']]['slug'];

								if ($this->User->Field->save($data))
								{
									$fields_data[$field[$prefix . 'fields']['name']] = $this->User->Field->id;
									$success++;
								}

								$total++;
							} elseif (!empty($field_check)) {
								$fields_data[$field[$prefix . 'fields']['name']] = $field_check['Field']['id'];
							}
						}
					}
				}

				// Articles
				$articles = $this->User->query('SELECT * FROM ' . $prefix . 'content');

				$articles_data = array();
				if (!empty($articles))
				{
					foreach($articles as $article)
					{
						if (!empty($article[$prefix . 'content']['name'])) {
							$article_check = $this->User->Article->findByTitle($article[$prefix . 'content']['name']);

							if (empty($article_check))
							{
								$data = array();
								$this->User->Article->create();

								$data['Article']['user_id'] = $user_data[$article[$prefix . 'content']['username']];
								$data['Article']['title'] = $article[$prefix . 'content']['name'];
								$data['Article']['status'] = 1;
								$data['Article']['category_id'] = $categories_data[$article[$prefix . 'content']['cat']];
								$data['Article']['created'] = date('Y-m-d H:i:s', $article[$prefix . 'content']['date']);

								if ($this->User->Article->save($data))
								{
									$articles_data[$article[$prefix . 'content']['id']] = $this->User->Article->id;
									$success++;
								}

								$total++;
							} else {
								$articles_data[$article[$prefix . 'content']['id']] = $article_check['Article']['id'];
							}
						}
					}
				}

				// Field Data
				$fielddata = $this->User->query('SELECT * FROM ' . $prefix . 'fielddata');

				if (!empty($fielddata))
				{
					foreach($fielddata as $row)
					{
						if (!empty($fields_data[$row[$prefix . 'fielddata']['name']])) {
							$data = array();
							$this->User->Article->ArticleValue->create();

							if ($row[$prefix . 'fielddata']['cat'] == 'games') {
								$data['ArticleValue']['article_id'] = $games_data[$row[$prefix . 'fielddata']['id2']];
							} else {
								$data['ArticleValue']['article_id'] = $articles_data[$row[$prefix . 'fielddata']['id2']];
							}

							$data['ArticleValue']['data'] = $row[$prefix . 'fielddata']['data'];
							$data['ArticleValue']['field_id'] = $fields_data[$row[$prefix . 'fielddata']['name']];

							if ($this->User->Article->ArticleValue->save($data))
							{
								$success++;
							}

							$total++;
						}
					}
				}

				// Pages
				$pages = $this->User->query('SELECT * FROM ' . $prefix . 'pages');

				if (!empty($pages))
				{
					foreach($pages as $page)
					{
						if (!empty($page[$prefix . 'pages']['name'])) {
							$page_check = $this->User->Page->findByTitle($page[$prefix . 'pages']['name']);

							if (empty($page_check))
							{
								$data = array();
								$this->User->Page->create();

								$data['Page']['content'] = $page[$prefix . 'pages']['content'];
								$data['Page']['title'] = $page[$prefix . 'pages']['name'];
								$data['Page']['user_id'] = $this->Auth->user('id');

								if ($this->User->Page->save($data))
								{
									$success++;
								}

								$total++;
							}
						}
					}
				}

				if ($success == $total)
				{
					$this->Session->setFlash('OneCMS data has been converted.', 'success');
					$this->redirect(
						array(
							'action' => 'index'
						)
					);
				} else {
					$this->Session->setFlash('OneCMS data could not be converted.', 'error');
				}
			}
		}
	}

	/**
	 * Admin Routes List
	 *
	 * @return void
	 */
	public function admin_routes_list()
	{
		$find = array(
			'{ {',
			'} }',
			'url ('
		);
		$replace = array(
			'{{',
			'}}',
			'url('
		);

		$this->set(compact('find', 'replace'));
		$this->set('routes', Configure::read('current_routes'));
	}

	/**
	 * Admin Feeds
	 *
	 * @return void
	 */
	public function admin_feeds()
	{
		$categories = $this->Permission->Role->User->Category->find('slugList');

		$limits = array();
		for($i = 0; $i <= 50; $i++) {
			$limits[$i] = $i;
		}

		$this->set(compact('categories', 'limits'));
	}

	/**
	 * Admin Create Plugin
	 *
	 * @return CakeResponse
	 */
	public function admin_create_plugin()
	{
		$this->disable_parsing = true;
		$path = CACHE . 'persistent' . DS . 'create_plugin_' . $this->Auth->user('id') . '.tmp';

		if ($this->request->is('post')) {
			$this->layout = false;

			$data = file_get_contents("php://input");
			$status = true;
			$msg = 'true';

			if (empty($this->request->query['finish'])) {
				file_put_contents($path, $data);
			} else {
				$this->loadModel('Plugin');

				$data = json_decode($data, true);

				$active_plugins = $this->Plugin->getPlugins( $this->Plugin->getActivePath() );
				$inactive_plugins = $this->Plugin->getPlugins( $this->Plugin->getInactivePath() );

				$active_plugins = array_keys($active_plugins['plugins']);
				$inactive_plugins = array_keys($inactive_plugins['plugins']);

				if (in_array($data['basicInfo']['name'], $active_plugins)) {
					$msg = 'This name is already used by an existing installed plugin. Please choose a different name.';
					$status = false;
				} elseif (in_array($data['basicInfo']['name'], $inactive_plugins)) {
					$msg = 'This name is already used by an existing inactive plugin. Please choose a different name.';
					$status = false;
				}

				if ($status) {
					$url = Configure::read('Component.Api.api_url') . 'v1/plugins?limit=999';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$response = json_decode(curl_exec($ch), true);
					curl_close($ch);

					if (in_array($data['basicInfo']['name'], Set::extract('{n}.title', $response['data']))) {
						$msg = 'This name is already used by a plugin on the official website. Please choose a different name.';
						$status = false;
					}
				}

				if ($status) {
					$this->Plugin->createPlugin($data);

					$msg = 'Your plugin has been created and set to inactive.';
					$this->Session->setFlash($msg, 'success');
				}
			}

			return $this->_ajaxResponse(array('body' => $msg), array(), 'json', $status);
		}
	}

	/**
	 * Admin Create Theme
	 *
	 * @return CakeResponse
	 */
	public function admin_create_theme()
	{
		$this->disable_parsing = true;
		$path = CACHE . 'persistent' . DS . 'create_theme_' . $this->Auth->user('id') . '.tmp';

		if ($this->request->is('post')) {
			$this->layout = false;

			$data = file_get_contents("php://input");
			$status = true;
			$msg = 'true';

			if (empty($this->request->query['finish'])) {
				file_put_contents($path, $data);
			} else {
				$this->loadModel('Theme');

				$data = json_decode($data, true);

				$active_themes = $this->Theme->getThemes( $this->Theme->getActivePath() );
				$inactive_themes = $this->Theme->getThemes( $this->Theme->getInactivePath() );

				$active_themes = array_keys($active_themes['themes']);
				$inactive_themes = array_keys($inactive_themes['themes']);

				if (in_array($data['basicInfo']['name'], $active_themes)) {
					$msg = 'This name is already used by an existing installed theme. Please choose a different name.';
					$status = false;
				} elseif (in_array($data['basicInfo']['name'], $inactive_themes)) {
					$msg = 'This name is already used by an existing inactive theme. Please choose a different name.';
					$status = false;
				}

				if ($status) {
					$url = Configure::read('Component.Api.api_url') . 'v1/themes?limit=999';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$response = json_decode(curl_exec($ch), true);
					curl_close($ch);

					if (in_array($data['basicInfo']['name'], Set::extract('{n}.title', $response['data']))) {
						$msg = 'This name is already used by a theme on the official website. Please choose a different name.';
						$status = false;
					}
				}

				if ($status) {
					$msg = $this->Theme->createTheme($data);

					$this->Session->setFlash('Your theme has been created and set to inactive.', 'success');
				}
			}

			return $this->_ajaxResponse(array('body' => $msg), array(), 'json', $status);
		}
	}
}