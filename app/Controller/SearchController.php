<?php

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

	/**
	* Anyone can use the search feature
	*/
	public function beforeFilter()
	{
		$this->allowedActions = array(
			'index',
			'search'
		);

		parent::beforeFilter();

		$this->loadModel('Module');

		$modules = $this->Module->find('list', array(
			'conditions' => array(
				'Module.is_searchable' => 1
			)
		));

		if ($this->params->action == 'search')
		{
			$modules = implode(',', array_keys($modules) );
		}

		$this->set(compact('options'));
	}

	/**
	* Brings in the search element
	*
	* @return none
	*/
	public function index()
	{
	}

	/**
	* Hooks up to the 'getSearchParams' model function of a specified module 
	* (or all that have the is_searchable flag set, this includes plugins)
	*
	* @return associative array of search data
	*/
	public function search()
	{
		if ( empty($this->request->data['Search']['q']) ||
			$this->request->is('get') )
		{
			$this->redirect('/');
		}

		$q = $this->request->data['Search']['q'];

		if ( !empty($this->request->data['Search']['module']) &&
			is_numeric($this->request->data['Search']['module']) )
		{
			$module_id = $this->request->data['Search']['module'];
			$module = $this->Module->findById($module_id);
			$model = $this->Module->loadModelName($module, true);
			$module_name = $module['Module']['title'];

			$this->loadModel(
				$model['load']
			);

			$this->paginate = array(
				'conditions' => array(
					'title LIKE' => '%' . $q . '%'
				)
			);

			if ( method_exists($this->$model['name'], 'getSearchParams') && $this->$model['name']->getSearchParams( $q ) )
			{
				$params = $this->$model['name']->getSearchParams( $q );

				if ( is_array($params) )
				{
					$this->paginate = $params;
				
					if (!empty($params['permissions']) && !$this->permissionLookup(array(
							$params['permissions']
						)))
					{
						if ($this->RequestHandler->isAjax())
						{
							return false;
						} else {
							$this->redirect('/');
						}
					}
				}
			} else {
				if (!$this->permissionLookup(array(
						'controller' => str_replace(' ','', strtolower($module['Module']['title']) ),
						'plugin' => ($module['Module']['is_plugin'] == 1 ? Inflector::tableize($model['name']) : '')
					)))
				{
					if ($this->RequestHandler->isAjax())
					{
						return false;
					} else {
						$this->redirect('/');
					}
				}
			}

			$data = $this->paginate(
				$model['name']
			);

			if ($module['Module']['is_plugin'] == 1)
			{
				$this->set('model', str_replace('.', '.Search/', $model['load']) );
			} else {
				$this->set('model', 'Search/' . $model['load']);
			}

			$this->set( compact( 'data', 'q', 'module_name', 'module_id' ) );
		} else {
			$this->set( compact( 'q' ) );
		}
	}
}