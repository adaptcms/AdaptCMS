<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

/**
 * Class SearchController
 */
class SearchController extends AppController
{
    /**
    * Name of the Controller, 'Search'
    */
	public $name = 'Search';

	/**
	* Search is not defined by a specific model. So no uses by default.
	*/
	public $uses = array();

	private $modules;

	public $disable_parsing = true;

	/**
	* Anyone can use the search feature
	*/
	public function beforeFilter()
	{
		$this->allowedActions = array(
			'index',
			'search'
		);

        $this->Security->unlockedActions = array('index', 'search');

		parent::beforeFilter();

		$this->loadModel('Module');

		$this->modules = $this->Module->find('list', array(
			'conditions' => array(
				'Module.is_searchable' => 1
			)
		));
		$modules = $this->modules;

		$this->set(compact('options', 'modules'));
	}

	/**
	* Brings in the search element
	*
	* @return void
	*/
	public function index()
	{
		if ($this->Session->read('current_page'))
			$this->Session->delete('current_page');
	}

	/**
	 * Hooks up to the 'getSearchParams' model function of a specified module
	 * (or all that have the is_searchable flag set, this includes plugins)
	 *
	 * @param $q
	 * @param null $cur_module
	 * @return mixed
	 */
	public function search($q, $cur_module = null)
	{
		$this->view = null;

		if (!empty($this->request->params['named']['clear_search']) && $this->Session->read('current_page'))
			$this->Session->delete('current_page');

		if (!empty($cur_module) && !empty($this->modules[$cur_module])) {
			$modules[$cur_module] = $this->modules[$cur_module];
		} else {
			$modules = $this->modules;
		}

		if (!empty($this->request->params['named']['page'])) {
			$orig_page = $this->request->params['named']['page'];
		} else {
			$orig_page = 1;
		}

		$q = Sanitize::clean($q, array(
			'encode' => true,
			'remove_html' => true
		));

		$body = array();
		foreach($modules as $module_id => $module) {
			$module = $this->Module->findById($module_id);
			$model = $this->Module->loadModelName($module, true);
			$module_name = $module['Module']['title'];
			$model_name = $model['name'];

			$this->loadModel(
				$model['load']
			);

			$this->Paginator->settings[$model['name']] = array(
				'conditions' => array(
					$model['name'] . '.title LIKE' => '%' . $q . '%'
				),
				'limit' => $this->pageLimit
			);

			if (!empty($this->request->params['named']['model']) && $this->request->params['named']['model'] == $model['name']) {
				$page = $orig_page;
				$this->Session->write('current_page.' . $model_name, $page);
			} elseif(!empty($this->request->params['named']['model']) && $this->Session->read('current_page.' . $model_name)) {
				$page = $this->Session->read('current_page.' . $model_name);
			} else {
				$page = 1;
			}

			$this->request->params['named']['page'] = $page;

			if ( method_exists($this->$model['name'], 'getSearchParams') && $this->$model['name']->getSearchParams( $q ) )
			{
				$params = $this->$model['name']->getSearchParams( $q );

				if ( is_array($params) )
				{
					$params['limit'] = $this->pageLimit;
					$this->Paginator->settings[$model['name']] = $params;

					if (!empty($params['permissions']) && !$this->permissionLookup(array($params['permissions'])))
						$results = array();
				}
			} else {
				$lookup = $this->permissionLookup(array(
					'controller' => str_replace(' ','', strtolower($module_name) ),
					'plugin' => ($module['Module']['is_plugin'] == 1 ? Inflector::tableize($model['name']) : '')
				));
				if (!$lookup)
					$results = array();
			}

			if (empty($results))
				$results = $this->Paginator->paginate($model['name']);

			if ($module['Module']['is_plugin'] == 1)
			{
				$element = str_replace('.', '.Search/', $model['load']);
			} else {
				$element = 'Search/' . $model['load'];
			}

			$element_data = $this->_getElement($element, array('results' => $results));

			$paginator_data = $this->request['paging'][$model['name']];
			$start = 0;

			if ($paginator_data['count'] > 1)
				$start = (($paginator_data['page'] - 1) * $paginator_data['limit'] + 1);

			$end = $start + $paginator_data['limit'] - 1;
			if ($paginator_data['count'] < $end) {
				$end = $paginator_data['count'];
			}

			$pages = array();
			if (!empty($paginator_data['pageCount'])) {
				for($i = 1; $i <= $paginator_data['pageCount']; $i++) {
					$pages[$i] = $i;
				}
			}

			$body[$model['name']] = array(
				'results' => $element_data,
				'count' => $paginator_data['count'],
				'name' => $module_name,
				'model' => $model['name'],
				'q' => $q,
				'paginator' => $paginator_data,
				'start' => $start,
				'end' => $end,
				'pages' => $pages,
				'path' => $this->request->webroot . 'search/search/' . $q . '/' . $cur_module
			);
		}

		return $this->_ajaxResponse(array('body' => $body), array(), 'json');
	}
}