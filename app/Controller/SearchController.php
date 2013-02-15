<?php

class SearchController extends AppController
{
	public $uses = array();

	public function beforeFilter()
	{
		$this->allowedActions = array(
			'index',
			'search'
		);

		parent::beforeFilter();
	}

	public function index()
	{
		$this->loadModel('Module');

		$options = $this->Module->find('list', array(
			'conditions' => array(
				'Module.is_searchable' => 1
			)
		));

		$this->set(compact('options'));
	}

	public function search()
	{
		if ( empty($this->request->data['Search']['q']) ||
			$this->request->is('get') )
		{
			$this->redirect('/');
		}

		$q = $this->request->data['Search']['q'];

		$this->loadModel('Module');

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
			$modules = implode(',', array_keys($this->Module->find('list', array(
				'conditions' => array(
					'Module.is_searchable' => 1
				)
			))));
			$this->set( compact( 'modules', 'q' ) );
		}
	}
}