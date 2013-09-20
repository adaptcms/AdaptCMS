<?php
App::uses('AppController', 'Controller');

/**
 * Class BlocksController
 *
 * @property Block $Block
 */
class BlocksController extends AppController
{
	/**
	 * Name of the Controller, 'Blocks'
	 */
	public $name = 'Blocks';

	/**
	 * array of permissions for this page
	 */
	private $permissions;

	/**
	 * In this beforeFilter we will retrieve a list of themes and modules,
	 * pass a limit for the dropdown and get the block types as well as permissions
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->request->action == 'admin_edit' || $this->request->action == 'admin_add') {
			$this->set('models', $this->Block->getModules());

			$this->loadModel('Theme');
			$this->set('themes', $this->Theme->find('list', array(
					'order' => 'Theme.id ASC'
				)
			));

			for ($i = 1; $i <= 50; $i++) {
				$limit[$i] = $i;
			}

			$this->set(compact('limit'));
		}

		if (strstr($this->request->action, 'admin_')) {
			$block_types = $this->Block->block_types;

			$this->set(compact('block_types'));
		}

		$this->permissions = $this->getPermissions();
	}

	/**
	 * Returns a paginated index of Blocks
	 *
	 * @return array Array of block data
	 */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['type']))
			$conditions['Block.type'] = $this->request->named['type'];

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		if (isset($this->request->named['trash']))
			$conditions['Block.only_deleted'] = true;

		$this->Paginator->settings = array(
			'conditions' => array(
				$conditions
			),
			'contain' => array(
				'User'
			)
		);

		$this->request->data = $this->Paginator->paginate('Block');
	}

	/**
	 * Returns nothing before post
	 *
	 * On POST, returns error flash or success flash and redirect to index on success
	 *
	 * @return mixed
	 */
	public function admin_add()
	{
		if (!empty($this->request->data)) {
			$this->Block->create();

			$this->request->data['Block']['user_id'] = $this->Auth->user('id');

			if ($this->Block->save($this->request->data)) {
				$this->Session->setFlash('Your block has been added.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add block. Make sure you entered in all required fields.', 'flash_error');
			}
		}
	}

	/**
	 * Before POST, sets request data to form
	 *
	 * After POST, flash error or flash success and redirect to index
	 *
	 * @param integer $id id of the database entry, redirect to index if no permissions
	 * @return array Array of block data
	 */
	public function admin_edit($id)
	{
		$this->Block->id = $id;

		if (!empty($this->request->data)) {
			$this->request->data['Block']['user_id'] = $this->Auth->user('id');

			if ($this->Block->save($this->request->data)) {
				$this->Session->setFlash('Your Block has been updated.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update your Block.', 'flash_error');
			}
		}

		$this->request->data = $this->Block->formatData($this->Block->find('first', array(
			'conditions' => array(
				'Block.id' => $id
			),
			'contain' => array(
				'Module',
				'User'
			)
		)));
		$this->hasAccessToItem($this->request->data);
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
		$this->Block->id = $id;

		$data = $this->Block->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Block->remove($data);

		$this->Session->setFlash('The block `' . $title . '` has been deleted.', 'flash_success');

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
		$this->Block->id = $id;

		$data = $this->Block->findById($id);
		$this->hasAccessToItem($data);

		if ($this->Block->restore()) {
			$this->Session->setFlash('The block `' . $title . '` has been restored.', 'flash_success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('The block `' . $title . '` has NOT been restored.', 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Admin Ajax Get Model
	 *
	 * This method retrieves a list of Modules, used by the admin functions of Blocks.
	 * It is used when showing a list of Modules for Dynamic Blocks and includes the Modules custom options.
	 * Such as selecting a category for an Article block
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_get_model()
	{
		$custom = array();

		if ($this->request->data['Block']['type'] == "action") {
			$find = $this->Block->Module->findByTitle($this->request->data['Block']['module_id']);
			$model = $find['Module']['model_title'];

			$this->loadModel(
				$this->Block->loadModelName($find)
			);

			$model_name = $model;

			$data = $this->$model->find('all');
		} else {
			$model = $this->request->data['Block']['module_id'];
			$this->loadModel($model);

			if (strstr($model, '.')) {
				$ex = explode('.', $model);
				$model_name = $ex[1];
				$data = $this->$ex[1]->find('all');
			} else {
				$model_name = $model;
				$data = $this->$model->find('all');
			}

			if (method_exists($this->$model, 'getBlockCustomOptions')) {
				$custom = $this->$model->getBlockCustomOptions(
					json_decode(
						$this->request->data['Block']['custom'],
						true
					)
				);
			}
		}

		$list_data = array();

		foreach ($data as $row) {
			if (!empty($row[$model_name]['slug'])) {
				$list_data[$row[$model_name]['slug']] = $row[$model_name]['title'];
			} else {
				$list_data[$row[$model_name]['id']] = $row[$model_name]['title'];
			}
		}

		$body = array(
			'custom' => $custom,
			'data' => $list_data
		);

		return $this->_ajaxResponse(array('body' => json_encode($body)));
	}
}