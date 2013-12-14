<?php
App::uses('AppController', 'Controller');

/**
 * Class ArticlesController
 * @property Article $Article
 */
class ArticlesController extends AppController
{
	/**
	 * Name of the Controller, 'Articles'
	 */
	public $name = 'Articles';

	/**
	 * array of permissions for this page
	 */
	private $permissions;

	/**
	 * Array of helpers, 'Captcha' used for article comments and Fields for convenience methods.
	 */
	public $helpers = array(
		'Captcha',
		'Field'
	);

	public $cacheAction = array(
		'view' => 86400
	);

	/**
	 * In this beforeFilter we will get the permissions to be used in the view files
	 *
	 * Additionally, a list of categories is set to the view and for add/edit pages, a list of files
	 * for the image field type
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();

		if (strstr($this->request->action, 'admin_')) {
			$categories = $this->Article->Category->find('list');

			if (empty($categories)) {
				$this->Session->setFlash('Please add a category in order to manage articles.', 'flash_error');
				$this->redirect(array('action' => 'add', 'controller' => 'categories'));
			}

			$this->set(compact('categories'));
		}

		if ($this->request->action == "admin_add" || $this->request->action == "admin_edit") {
			$this->loadModel('File');

			$this->Paginator->settings = array(
				'conditions' => array(
					'File.mimetype LIKE' => '%image%'
				),
				'limit' => 9
			);

			$images = $this->Paginator->paginate('File');
			$image_path = WWW_ROOT;

			$this->set(compact('images', 'image_path'));
		}

		$this->permissions = $this->getPermissions();
	}

	/**
	 * Returns a paginated index of Articles
	 *
	 * @return array Array of data
	 */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
			$conditions['Article.only_deleted'] = true;

		if (!empty($this->request->named['category_id']))
			$conditions['Category.id'] = $this->request->named['category_id'];

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'contain' => array(
				'User',
				'Category'
			),
			'conditions' => $conditions
		);

		$this->request->data = $this->Article->Comment->getCommentsCount(
			$this->Paginator->paginate('Article')
		);
	}

	/**
	 * Returns nothing before post
	 *
	 * On POST, returns error flash or success flash and redirect to index on success
	 *
	 * @param category_id
	 * @return mixed
	 */
	public function admin_add($category_id = null)
	{
		$fields = $this->Article->Category->Field->getFields($category_id);

		$this->set(compact('fields', 'category_id'));

		if (!empty($this->request->data)) {
			$this->request->data['Article']['user_id'] = $this->Auth->user('id');

			if ($this->Article->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your article has been added.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your article.', 'flash_error');
			}
		}
	}

	/**
	 * Before POST, sets request data to form
	 *
	 * After POST, flash error or flash success and redirect to index
	 *
	 * @param integer $id id of the database entry, redirect to index if no permissions
	 * @return array Array of category data
	 */
	public function admin_edit($id)
	{
		$this->Article->id = $id;

		if (!empty($this->request->data)) {
			$this->request->data['Article']['user_id'] = $this->Auth->user('id');

			if ($this->Article->saveAssociated($this->Article->ArticleValue->checkOnEdit($this->request->data))) {
				$this->Session->setFlash('Your article has been updated.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update your article.', 'flash_error');
			}
		}

		$this->request->data = $this->Article->find('first', array(
			'conditions' => array(
				'Article.id' => $id
			),
			'contain' => array(
				'Category',
				'User'
			)
		));
		$this->hasAccessToItem($this->request->data);

		$category_id = $this->request->data['Category']['id'];

		$this->set('related_articles', $this->Article->getRelatedArticles(
			$id,
			$this->request->data['Article']['related_articles']
		));

		$comments = array();
		$conditions = array(
			'conditions' => array(
				'Comment.article_id' => $id
			)
		);

		$comments_count = $this->Article->Comment->find('count', $conditions);

		$cur_limit = !empty($this->request->named['limit']) ? $this->request->named['limit'] : 5;

		if ($comments_count > 0) {
			if ($comments_count > $cur_limit) {
				$diff = $comments_count - $cur_limit;

				if ($diff >= 5) {
					$new_comments_limit = $cur_limit + 5;
					$new_comments_amount = 5;
				} else {
					$new_comments_limit = $cur_limit + $diff;
					$new_comments_amount = $diff;
				}
			} else {
				$cur_limit = $comments_count;
			}

			$conditions['contain'] = array('User');
			$conditions['limit'] = $cur_limit;
			$conditions['order'] = 'Comment.created DESC';

			$comments = $this->Article->Comment->find('all', $conditions);
		}

		$fields = $this->Article->Category->Field->getFields($category_id, $id);

		$this->set(compact(
			'fields',
			'category_id',
			'comments',
			'comments_count',
			'new_comments_limit',
			'new_comments_amount',
			'cur_limit'
		));
	}

	/**
	 * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
	 *
	 * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
	 *
	 * @param integer $id id of the database entry, redirect to index if no permissions
	 * @param string $title Title of this entry, used for flash message
	 * @return void
	 */
	public function admin_delete($id, $title = null)
	{
		$this->Article->id = $id;

		$data = $this->Article->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Article->remove($data);

		$this->Session->setFlash('The article `' . $title . '` has been deleted.', 'flash_success');

		if ($permanent) {
			$this->redirect(array('action' => 'index', 'trash' => 1));
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Restoring an item will take an item in the trash and reset the delete time
	 *
	 * This makes it live wherever applicable
	 *
	 * @param integer $id ID of database entry, redirect if no permissions
	 * @param string $title Title of this entry, used for flash message
	 * @return void
	 */
	public function admin_restore($id, $title = null)
	{
		$this->Article->id = $id;

		$data = $this->Article->findById($id);
		$this->hasAccessToItem($data);

		if ($this->Article->restore()) {
			$this->Session->setFlash('The article `' . $title . '` has been restored.', 'flash_success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('The article `' . $title . '` has NOT been restored.', 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Admin Ajax Related Search
	 *
	 * AJAX Admin function that does a search based on a search parameter.
	 * If provided, it will also filter by a category and exclude an article.
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_related_search()
	{
		$conditions = array(
			'conditions' => array(
				'Article.title LIKE' => '%' . $this->request->data['search'] . '%'
			),
			'contain' => array(
				'Category'
			)
		);

		if (!empty($this->request->data['category']))
			$conditions['conditions']['Article.category_id'] = $this->request->data['category'];

		if (!empty($this->request->data['id']))
			$conditions['conditions']['Article.id !='] = $this->request->data['id'];

		if ($this->permissions['any'] == 0)
			$conditions['conditions']['Article.user_id'] = $this->Auth->user('id');

		$results = $this->Article->find('all', $conditions);

		$data = array();
		foreach ($results as $result) {
			$data[] = array(
				'id' => $result['Article']['id'],
				'title' => $result['Article']['title'],
				'category' =>
				' (' . $result['Category']['title'] . ')'
			);
		}

		return $this->_ajaxResponse(array('body' => json_encode($data)));
	}

	/**
	 * Admin Ajax Related Add
	 *
	 * AJAX Function that attempts to update the related articles in the admin.
	 * An array of ids are parsed to JSON and attempted to be saved.
	 *
	 * @return string html message on success or error
	 */
	public function admin_ajax_related_add()
	{
		$this->layout = 'ajax';
		$this->autoRender = false;

		$this->request->data['Article']['related_articles'] = json_encode(
			$this->request->data['Article']['ids']
		);

		$success = $this->Article->save($this->request->data);

		return $this->_ajaxResponse('Articles/admin_ajax_related_add', array('success' => $success));
	}

	/**
	 * View
	 *
	 * View action for an article. Permissions are checked, core article data is retrieved, threaded
	 * array of comments are retrieved and related comment settings.
	 *
	 * Related articles are also retrieved, a check is done to make sure the article data is not empty.
	 * One last check is to see if a custom template and exists and if so, use it.
	 *
	 * @param null|string $slug
	 * @param int|null $id
	 * @return array
	 */
	public function view($slug = null, $id = null)
	{
		$conditions = array(
			'conditions' => array(
				'Article.publish_time <=' => date('Y-m-d H:i:s'),
				'Article.status' => 1
			)
		);

		if (empty($slug) && !empty($this->request->params['slug']))
			$slug = $this->request->params['slug'];

		if (!empty($slug))
			$conditions['conditions']['Article.slug'] = $slug;

		if (empty($id) && !empty($this->request->params['id']))
			$id = $this->request->params['id'];

		if (!empty($id))
			$conditions['conditions']['Article.id'] = $id;

		$this->request->data = $this->Article->find('first', $conditions);

		if (empty($slug) || empty($this->request->data)) {
			$this->Session->setFlash('Invalid Article', 'flash_error');
			$this->redirect(array(
				'controller' => 'pages',
				'action' => 'display',
				'home'
			));
		}

		$article = $this->Article->getAllRelatedArticles(array($this->request->data));
		$this->request->data = $article[0];

		$this->hasAccessToItem($this->request->data);

		$this->request->data['Comments'] = $this->Article->Comment->find('threaded', array(
			'conditions' => array(
				'Comment.article_id' => $this->request->data['Article']['id'],
				'Comment.active' => 1
			),
			'contain' => array(
				'User'
			),
			'order' => 'Comment.created DESC'
		));

		$this->loadModel('SettingValue');

		if (!$this->Auth->user('id')) {
			$captcha = $this->SettingValue->findByTitle('Comment Post Captcha Non-Logged In');

			if (!empty($captcha['SettingValue']['data']) && $captcha['SettingValue']['data'] == 'Yes') {
				$this->set('captcha_setting', true);
			}
		}

		$wysiwyg = $this->SettingValue->findByTitle('Comment Post WYSIWYG Editor');

		if (!empty($wysiwyg['SettingValue']['data']) && $wysiwyg['SettingValue']['data'] == 'Yes') {
			$this->set(compact('wysiwyg'));
		}

		if (empty($this->request->data['Article']['id'])) {
			$this->Session->setFlash('Invalid Article', 'flash_error');
			$this->redirect(array(
				'controller' => 'pages',
				'action' => 'display',
				'home'
			));
		}

		if (!empty($this->request->data['RelatedArticles']['all'])) {
			$related_articles = $this->request->data['RelatedArticles'];
		} else {
			$related_articles = array();
		}

		$fields = $this->Article->Category->Field->getFields('Comment');
		$this->request->data['Comments'] = $this->Article->Category->Field->getAllModuleData('Comment', $fields, $this->request->data['Comments']);

		$this->set(compact('related_articles', 'fields'));
		$this->set('article', $this->request->data);

		if (!empty($this->request->data['Category']['slug'])) {
			$slug = $this->request->data['Category']['slug'];

			if ($this->theme != "Default" &&
				file_exists(VIEW_PATH . 'Themed/' . $this->theme . '/Articles/' . $slug . '.ctp') ||
				file_exists(VIEW_PATH . 'Articles/' . $slug . '.ctp')
			) {
				$this->render(implode('/', array($slug)));
			}
		}
	}

	/**
	 * Tag
	 * Listing of articles by tag
	 *
	 * @param string $tag
	 * @param integer $limit
	 *
	 * @return void
	 */
	public function tag($tag, $limit = 10)
	{
		if (empty($tag))
			$this->redirect('/');

		$slug = $this->Article->slug($tag);

		$conditions = array(
			'Article.tags LIKE' => '%"' . $slug . '"%',
			'Article.status' => 1,
			'Article.publish_time <=' => date('Y-m-d H:i:s')
		);

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'order' => 'Article.created DESC',
			'conditions' => $conditions,
			'contain' => array(
				'ArticleValue' => array(
					'Field',
					'File'
				),
				'Category',
				'User'
			),
			'limit' => $limit
		);

		$this->request->data = $this->Article->getAllRelatedArticles(
			$this->Paginator->paginate('Article')
		);

		$this->set('article', $this->request->data);
		$this->set('tag', $slug);
	}

	/**
	 * Not fully functional, renders rss file but headers are not proper XML.
	 *
	 * @param $category string
	 * @param $limit integer
	 * @return array of articles
	 */
	public function rss_index($category = null, $limit = 10)
	{
		$cond = array(
			'Article.status' => 1,
			'Article.publish_time <=' => date('Y-m-d H:i:s')
		);

		if (!empty($category))
			$cond['Category.slug'] = $category;

		$this->Paginator->settings = array(
			'contain' => array(
				'Category',
				'User',
				'ArticleValue' => array(
					'Field'
				)
			),
			'conditions' => $cond,
			'limit' => $limit,
			'order' => 'Article.created DESC'
		);

		$this->request->data = $this->Paginator->paginate('Article');
		Configure::write('debug', 0);
		$this->layout = 'rss/default';

		$this->loadModel('SettingValue');

		$sitename = $this->SettingValue->findByTitle('Site Name');

		if (!empty($sitename['SettingValue']['data'])) {
			$sitename = $sitename['SettingValue']['data'];
		} else {
			$sitename = 'Your Website';
		}

		$description = $this->SettingValue->findByTitle('RSS Description');

		if (!empty($description['SettingValue']['data'])) {
			$description = $description['SettingValue']['data'];
		} else {
			$description = "This is your website's RSS feed, enter in a little bit about your website.";
		}

		$this->set(compact('sitename', 'description'));
	}

	/**
	 * Experimental function, useable for a REST interface
	 *
	 * @return array json_encoded array of article data
	 */
	public function index()
	{
		$data = $this->Article->find('all', array(
			'conditions' => array(
				'Article.publish_time <=' => date('Y-m-d H:i:s'),
				'Article.status' => 1
			),
			'contain' => array(
				'User',
				'Category'
			),
			'fields' => array(
				'id', 'title', 'slug', 'tags', 'User.username', 'Category.title', 'status', 'publish_time', 'created'
			)
		));

		foreach ($data as $key => $row) {
			$data[$key] = $row['Article'];
			$data[$key]['category'] = $row['Category']['title'];
			$data[$key]['user'] = $row['User']['username'];
			unset($data[$key]['Category'], $data[$key]['User']);
		}

		$this->layout = '';
		$this->autoRender = false;

		echo json_encode($data);
	}
}