<?php
App::uses('AppController', 'Controller');
/**
 * Class TemplatesController
 * @property CmsApiComponent $CmsApi
 * @property Template $Template
 */
class TemplatesController extends AppController
{
	/**
	 * Name of the Controller, 'Templates'
	 */
	public $name = 'Templates';

	/**
	 * @var
	 */
	private $permissions;

	/**
	 * Need API Component
	 * @var array
	 */
	public $components = array(
		'CmsApi'
	);

	/**
	 * Gets list of themes, passed to only add and edit views
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->permissions = $this->getPermissions();
	}

	/**
	 * Returns a paginated index of Templates
	 *
	 * @return \\ array of template and theme data
	 */
	public function admin_index()
	{
		$conditions = array();
		$theme_conditions = array();

		if (!empty($this->request->named['theme_id']))
			$conditions['Theme.id'] = $this->request->named['theme_id'];

		if (!empty($this->request->named['trash_temp']))
			$conditions['Template.only_deleted'] = true;

		if (!empty($this->request->named['trash_theme']))
			$theme_conditions['Theme.only_deleted'] = true;

		$this->Paginator->settings = $this->Template->updateQueryData(array(
			'contain' => array(
				'Theme' => array(
					'conditions' => $theme_conditions
				)
			),
			'conditions' => $conditions
		));

		$this->request->data['Template'] = $this->Paginator->paginate('Template');
		$this->request->data['Themes'] = $this->Template->Theme->find('all', array(
			'conditions' => $theme_conditions,
			'order' => 'Theme.id ASC'
		));

		$themes_dropdown = $this->Template->Theme->getThemesList();

		$this->loadModel('SettingValue');

		$current_theme = $this->SettingValue->find('first', array(
				'conditions' => array(
					'SettingValue.title' => 'default-theme'
				),
				'fields' => array(
					'data',
					'id'
				)
			)
		);

		$this->set('current_theme', $current_theme['SettingValue']);
		$this->set(compact('themes', 'themes_dropdown'));

		$active_path = VIEW_PATH . 'Themed';
		$active_themes = $this->Template->Theme->getThemes($active_path);

		$inactive_path = VIEW_PATH . 'Old_Themed';
		$inactive_themes = $this->Template->Theme->getThemes($inactive_path);

		$themes = array_merge($active_themes['themes'], $inactive_themes['themes']);
		$api_lookup = array_merge($active_themes['api_lookup'], $inactive_themes['api_lookup']);

		if (!empty($api_lookup)) {
			if ($data = $this->CmsApi->themesLookup($api_lookup)) {
				foreach ($themes as $key => $theme) {
					if (!empty($theme['api_id']) && !empty($data['data'][$theme['api_id']])) {
						$themes[$key]['data'] = $data['data'][$theme['api_id']];
					}
				}
			}
		}

		$theme_names = Set::extract('{n}.Theme.title', $this->request->data['Themes']);
		$i = count($this->request->data['Themes']);

		if (empty($theme_conditions['Theme.only_deleted'])) {
			foreach ($themes as $theme) {
				$title = $theme['title'];

				if (!in_array($title, $theme_names)) {
					$i++;

					$this->request->data['Themes'][$i]['Data'] = $theme;
					$this->request->data['Themes'][$i]['Theme']['title'] = $title;
				} else {
					foreach ($this->request->data['Themes'] as $key => $row) {
						if ($row['Theme']['title'] == $title) {
							$this->request->data['Themes'][$key]['Data'] = $theme;
						}
					}
				}
			}
		}
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
			if ($this->Template->save($this->request->data)) {
				$this->Session->setFlash('Your template has been added.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your template.', 'flash_error');
			}
		}

		if (empty($this->params->pass[0])) {
			$theme_id = 1;
		} else {
			$theme_id = $this->params->pass[0];
		}

		$themes = $this->Template->Theme->getThemesList();

		$this->set(compact('theme_id', 'themes'));

		if ($theme_id == 1) {
			$this->set('locations', $this->Template->folderFullList());
		} else {
			$this->set('locations', $this->Template->folderFullList($themes[$theme_id]));
		}
	}

	/**
	 * Before POST, sets request data to form
	 *
	 * After POST, flash error or flash success and redirect to index
	 *
	 * @param integer $id ID of the database entry
	 * @return array Array of block data
	 */
	public function admin_edit($id)
	{
		$this->Template->id = $id;

		if (!empty($this->request->data)) {
			if ($this->Template->save($this->request->data)) {
				$this->Session->setFlash('Your template has been updated.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update your template.', 'flash_error');
			}
		}

		$themes = $this->Template->Theme->getThemesList();

		$this->request->data = $this->Template->find('first', array(
			'conditions' => array(
				'Template.id' => $id
			),
			'contain' => array(
				'Theme'
			)
		));

		if ($this->request->data['Template']['theme_id'] == 1) {
			$this->set('locations', $this->Template->folderFullList());
		} else {
			$this->set('locations', $this->Template->folderFullList($themes[$this->request->data['Template']['theme_id']]));
		}

		if (strstr($this->request->data['Template']['location'], 'Plugin/')) {
			$path = APP;
		} elseif ($this->request->data['Template']['theme_id'] != 1) {
			$path = VIEW_PATH . 'Themed' . DS . $this->request->data['Theme']['title'] . DS;
			$theme = $this->request->data['Theme']['title'];
		} else {
			$path = VIEW_PATH;
		}

		$location = $path . $this->request->data['Template']['location'];

		if (is_readable($location)) {
			$handle = fopen($location, "r");
			if (filesize($location) > 0) {
				$template_contents = fread($handle, filesize($location));
			} else {
				$template_contents = null;
			}

			if (!empty($theme)) {
				$template_docs = $this->Template->getDocs($location, $theme);
			} else {
				$template_docs = $this->Template->getDocs($location);
			}
		} else {
			$template_docs = null;
			$template_contents = null;
		}

		if (is_writable($location)) {
			$writable = 1;
		} else {
			$writable = $location;
		}

		$this->set(compact('template_contents', 'template_docs', 'themes', 'writable'));

		$this->set('location', str_replace(
				"/" . basename(
					$this->request->data['Template']['location']
				),
				"",
				$this->request->data['Template']['location']
			)
		);
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
		$this->Template->id = $id;

		$data = $this->Template->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Template->remove($data);

		$this->Session->setFlash('The template `' . $title . '` has been deleted.', 'flash_success');

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
		$this->Template->id = $id;

		if ($this->Template->restore()) {
			$this->Session->setFlash('The template `' . $title . '` has been restored.', 'flash_success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('The template `' . $title . '` has NOT been restored.', 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Admin Ajax Theme Update
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_theme_update()
	{
		$type = 'error';
		$message = 'The Default Theme could not be updated.';

		if (!empty($this->request->data['Setting']['id']) &&
			!empty($this->request->data['Setting']['data'])
		) {

			$this->loadModel('SettingValue');

			$this->SettingValue->id = $this->request->data['Setting']['id'];

			if ($this->SettingValue->saveField('data', $this->request->data['Setting']['data'])) {
				$type = 'success';
				$message = 'The Default Theme has been set to `' . $this->request->data['Setting']['title'] . '`';
			}
		}

		return $this->_ajaxResponse('flash_' . $type, array(
			'message' => $message,
			'id' => 'theme-update-div'
		));
	}

	/**
	 * Admin Ajax Quick Search
	 * AJAX Function used on the main templates admin page, allowing a user to get instant results
	 * of templates based on their search.
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_quick_search()
	{
		$conditions = array(
			'conditions' => array(
				'OR' => array(
					'Template.location LIKE' => '%' . $this->request->data['search'] . '%',
					'Template.label LIKE' => '%' . $this->request->data['search'] . '%'
				)
			),
			'contain' => array(
				'Theme'
			),
			'fields' => array(
				'id', 'label', 'location', 'Theme.title'
			)
		);

		if (!empty($this->request->data['theme']))
			$conditions['conditions']['Template.theme_id'] = $this->request->data['theme'];

		$results = $this->Template->find('all', $conditions);

		$data = array();
		foreach ($results as $result) {
			if (!empty($this->request->data['element']) &&
				strstr($result['Template']['location'], "Elements/") || empty($this->request->data['element'])
			) {
				$data[] = array(
					'id' => $result['Template']['id'],
					'title' => $result['Template']['label'],
					'location' =>
					' (' . $result['Template']['location'] . ') - <i>' . $result['Theme']['title'] . ' Theme</i>'
				);
			}
		}

		return $this->_ajaxResponse(array('body' => json_encode($data)));
	}

	/**
	 * Admin Ajax Theme Refresh
	 * Function will go through all view files in specified theme and ensure
	 * that all are loaded up on the database. (in case of a new file being added via, say, FTP)
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_theme_refresh()
	{
		if (!empty($this->request->data['Theme']['name'])) {
			$files = $this->Template->folderAndFilesList($this->request->data['Theme']['name']);
		} else {
			$files = $this->Template->folderAndFilesList();
		}

		try {
			if (!empty($files)) {
				$data = $this->Template->find("all", array(
					'conditions' => array(
						'Template.theme_id' => $this->request->data['Theme']['id']
					),
					'fields' => array(
						'Template.location'
					)
				));

				$key = 0;
				$templates = array();

				$this->Template->create();

				if (!empty($data))
					$data = Set::extract('{n}.Template.location', $data);

				foreach ($files as $file) {
					if (empty($data) || !empty($data) && !in_array($file, $data)) {
						$title = explode('/', $file);

						$templates[$key]['Template']['title'] = end($title);
						$templates[$key]['Template']['label'] =
							str_replace('Plugin View', 'Plugin', str_replace('.ctp', '', Inflector::humanize(str_replace("/", " ", $file))));
						$templates[$key]['Template']['location'] = $file;
						$templates[$key]['Template']['theme_id'] = $this->request->data['Theme']['id'];
						$templates[$key]['Template']['created'] = $this->Template->dateTime();
						$templates[$key]['Template']['nowrite'] = true;

						$key++;
					}
				}

				if (!empty($templates))
					$this->Template->saveAll($templates);
			}

			$type = 'success';
			$message = 'The theme has been refreshed.';
		} catch(Exception $e) {
			$type = 'error';
			$message = 'The Theme could not be refreshed.';
		}

		return $this->_ajaxResponse('flash_' . $type, array(
			'message' => $message,
			'id' => 'theme-update-div'
		));
	}

	/**
	 * Admin AJax Template Locations
	 * Function that gets all folders and returns them as options for a select
	 *
	 * @return CakeResponse
	 */
	public function admin_ajax_template_locations()
	{
		$list = '';
		if ($this->request->data['Theme']['id'] == 1) {
			$folders = $this->Template->folderFullList();
		} else {
			$folders = $this->Template->folderFullList($this->request->data['Theme']['title']);
		}

		foreach ($folders as $key => $row) {
			$list .= "<option value='" . $key . "'>" . $row . "</option>";
		}

		return $this->_ajaxResponse(array('body' => $list));
	}
}