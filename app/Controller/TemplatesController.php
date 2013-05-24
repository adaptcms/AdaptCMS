<?php

class TemplatesController extends AppController
{
    /**
    * Name of the Controller, 'Templates'
    */
	public $name = 'Templates';

    private $permissions;

	/**
	* Need API Component
	*/
	public $components = array(
		'Api'
	);

	/**
	* Gets list of themes, passed to only add and edit views
	*/
	public function beforeFilter()
	{
		parent::beforeFilter();

        $this->permissions = $this->getPermissions();
	}

    private function _getThemes()
    {
        $themes = $this->Template->Theme->find('list', array(
            'order' => 'Theme.id ASC',
            'conditions' => array(
                'Theme.deleted_time' => '0000-00-00 00:00:00'
            )
        ));

        return $themes;
    }

    /**
    * Returns a paginated index of Templates
    *
    * @return associative array of template and theme data
    */
	public function admin_index()
	{
        $conditions = array();

        if (!empty($this->params->named['theme_id']))
        {
            $conditions['Theme.id'] = $this->params->named['theme_id'];
        }

		if (!isset($this->params->named['trash_temp']))
		{
			$conditions['Template.deleted_time'] = '0000-00-00 00:00:00';
		} else {
			$conditions['Template.deleted_time !='] = '0000-00-00 00:00:00';
	    }

		$this->paginate = array(
            'order' => 'Template.created DESC',
            'contain' => array(
            	'Theme'
            ),
            'conditions' => array(
            	$conditions
            ),
            'limit' => $this->pageLimit
        );
        
		$this->request->data['Template'] = $this->paginate('Template');
		$this->request->data['Themes'] = $this->Template->Theme->find('all', array(
                'order' => 'Theme.id ASC'
            )
        );

		foreach ($this->request->data['Themes'] as $key => $row)
		{
			if ($row['Theme']['deleted_time'] == "0000-00-00 00:00:00")
			{
				$themes[$row['Theme']['title']] = $row['Theme']['title'];
			}

			if (!empty($this->params->named['trash_theme']) && $row['Theme']['deleted_time'] == "0000-00-00 00:00:00" ||
				empty($this->params->named['trash_theme']) && $row['Theme']['deleted_time'] != "0000-00-00 00:00:00") {
				unset($this->request->data['Themes'][$key]);
			}
		}

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
        $this->set(compact('themes'));

		$active_path = VIEW_PATH . 'Themed';
		$active_themes = $this->getThemes($active_path);

		$inactive_path = VIEW_PATH . 'Old_Themed';
		$inactive_themes = $this->getThemes($inactive_path);

		$themes = array_merge($inactive_themes['themes'], $active_themes['themes']);
		$api_lookup = array_merge($inactive_themes['api_lookup'], $active_themes['api_lookup']);

		if (!empty($api_lookup))
		{
			if ($data = $this->Api->themesLookup($api_lookup))
			{
				foreach($themes as $key => $theme)
				{
					if (!empty($theme['api_id']) && !empty($data['data'][$theme['api_id']]))
					{
						$themes[$key]['data'] = $data['data'][$theme['api_id']];
					}
				}
			}
		}

        $theme_names = Set::extract('{n}.Theme.title', $this->request->data['Themes']);
        $key = count($this->request->data['Themes']);

        foreach($themes as $theme)
        {
            $title = $theme['title'];

            if (!in_array($title, $theme_names))
            {
                $this->request->data['Themes'][$key]['Data'] = $theme;
                $this->request->data['Themes'][$key]['Theme']['title'] = $title;

                $key++;
            }
            elseif (!empty($theme['data']))
            {
                foreach($this->request->data['Themes'] as $key => $row)
                {
                    if ($row['Theme']['title'] == $title)
                        $this->request->data['Themes'][$key]['Data'] = $theme;
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
        if (!empty($this->request->data))
        {
            if ($this->Template->save($this->request->data))
            {
                $this->Session->setFlash('Your template has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your template.', 'flash_error');
            }
        }

		if (empty($this->params->pass[0]))
		{
			$theme_id = 1;
		} else {
			$theme_id = $this->params->pass[0];
		}

        $themes = $this->_getThemes();

	    $this->set(compact('theme_id', 'themes'));

	    if ($theme_id == 1)
	    {
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
    * @param id ID of the database entry
    * @return associative array of block data
    */
	public function admin_edit($id = null)
	{
      	$this->Template->id = $id;

	    if (!empty($this->request->data))
	    {
	        if ($this->Template->save($this->request->data)) {
	            $this->Session->setFlash('Your template has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your template.', 'flash_error');
	        }
	    }

        $themes = $this->_getThemes();

    	$this->request->data = $this->Template->find('first', array(
            'conditions' => array(
                'Template.id' => $id
            ),
            'contain' => array(
                'Theme'
            )
        ));

	    if ($this->request->data['Template']['theme_id'] == 1)
	    {
			$this->set('locations', $this->Template->folderFullList());
		} else {
			$this->set('locations', $this->Template->folderFullList($themes[$this->request->data['Template']['theme_id']]));
		}

    	if (strstr($this->request->data['Template']['location'], 'Plugin/'))
    	{
    		$path = APP;
    	} elseif ($this->request->data['Template']['theme_id'] != 1) {
            $path = VIEW_PATH . 'Themed' . DS .$this->request->data['Theme']['title'] . DS;
        } else {
    		$path = VIEW_PATH;
    	}

        if (is_readable($path . $this->request->data['Template']['location']))
        {
	 		$handle = fopen($path . $this->request->data['Template']['location'], "r");
	 		if (filesize($path . $this->request->data['Template']['location']) > 0)
	 		{
				$template_contents = fread($handle, filesize($path.$this->request->data['Template']['location']));
    		} else {
    			$template_contents = null;
    		}
    	} else {
    		$template_contents = null;
    	}

    	$this->set(compact('template_contents', 'themes'));

    	$this->set('location', str_replace(
    		"/".basename(
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
    * @param id ID of the database entry, redirect to index if no permissions
    * @param title Title of this entry, used for flash message
    * @param permanent If not NULL, this means the item is in the trash so deletion will now be permanent
    * @return redirect
    */
	public function admin_delete($id = null, $title = null, $permanent = null)
	{
	    $this->Template->id = $id;

	    $location = $this->Template->find('first', array(
	    	'conditions' => array(
	    		'Template.id' => $id,
    		),
	    	'fields' => array(
	    		'location'
	    	)
	    ));

    	if (strstr($location['Template']['location'], 'Plugin'))
    	{
    		$path = APP;
    	} else {
    		$path = VIEW_PATH;
    	}

        if (!empty($permanent))
        {
            $delete = $this->Template->delete($id);
		    if (is_readable($path . $location['Template']['location']))
		    {
		    	unlink($path . $location['Template']['location']);
		    }
        } else {
            $delete = $this->Template->saveField('deleted_time', $this->Template->dateTime());
        }

	    if ($delete)
	    {
	        $this->Session->setFlash('The template `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The template `'.$title.'` has NOT been deleted.', 'flash_error');
	    }

	    if (!empty($permanent))
	    {
	    	$count = $this->Template->find('count', array(
	    		'conditions' => array(
	    			'Template.deleted_time !=' => '0000-00-00 00:00:00'
	    		)
	    	));

	    	$params = array('action' => 'index');

	    	if ($count > 0)
	    	{
	    		$params['trash'] = 1;
	    	}

	    	$this->redirect($params);
	    } else {
	    	$this->redirect(array('action' => 'index'));
	    }
	}

    /**
    * Restoring an item will take an item in the trash and reset the delete time
    *
    * This makes it live wherever applicable
    *
    * @param id ID of database entry, redirect if no permissions
    * @param title Title of this entry, used for flash message
    * @return redirect
    */
    public function admin_restore($id = null, $title = null)
    {
        $this->Template->id = $id;

        if ($this->Template->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The template `'.$title.'` has been restored.', 'flash_success');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The template `'.$title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }

	public function ajax_theme_update()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->RequestHandler->isAjax()) {
    		if (!empty($this->request->data['Setting']['id']) &&
    			!empty($this->request->data['Setting']['data'])) {
	    		
    			$this->loadModel('SettingValue');

    			$this->SettingValue->id = $this->request->data['Setting']['id'];

    			if ($this->SettingValue->saveField('data', $this->request->data['Setting']['data'])) {
    				return '
    				<div id="theme-update-div" class="alert alert-success">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Success</strong> The Default Theme has been set to 
    					`'.$this->request->data['Setting']['title'].'`.
    				</div>';
    			} else {
    				return '
    				<div id="theme-update-div" class="alert alert-error">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Error</strong> The Default Theme could not be updated.
    				</div>';
    			}
	    	}
    	}
	}

	/**
	* AJAX Function used on the main templates admin page, allowing a user to get instant results
	* of templates based on their search.
	*
	* @return json_encode array of templates
	*/
	public function admin_ajax_quick_search()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->request->is('ajax')) {
			$conditions = array(
           		'conditions' => array(
                	'OR' => array(
                		'Template.location LIKE' => '%'.$this->request->data['search'].'%',
                		'Template.label LIKE' => '%'.$this->request->data['search'].'%'
                	),
                	'Template.deleted_time' => '0000-00-00 00:00:00'
           		),
                'contain' => array(
                	'Theme'
                ),
                'fields' => array(
                	'id', 'label', 'location', 'Theme.title'
                )
        	);

    		if (!empty($this->request->data['theme']))
    		{
    			$conditions['conditions']['Template.theme_id'] = $this->request->data['theme'];			
    		}

            $results = $this->Template->find('all', $conditions);

            $data = array();
            foreach($results as $result)
            {
            	if (!empty($this->request->data['element']) && 
            		strstr($result['Template']['location'], "Elements/") || empty($this->request->data['element']))
            	{
	                $data[] = array(
	                	'id' =>$result['Template']['id'],
	                	'title' => $result['Template']['label'],
	                	'location' => 
	                		' ('.$result['Template']['location'].') - <i>'.$result['Theme']['title'].' Theme</i>'
	                );
	        	}
            }

            return json_encode($data);
    	}
	}

	/**
	* Function will go through all view files in specified theme and ensure
	* that all are loaded up on the database. (in case of a new file being added via, say, FTP)
	*
	* @return flash message on success or failure
	*/
	public function ajax_theme_refresh()
	{
		$this->layout = 'ajax';
		$this->autoRender = false;

		if ($this->RequestHandler->isAJax())
		{
			if (!empty($this->request->data['Theme']['name']))
			{
				$files = $this->Template->folderAndFilesList($this->request->data['Theme']['name']);
			} else {
				$files = $this->Template->folderAndFilesList();
			}

			if (!empty($files))
			{
				$data = $this->Template->find("all", array(
					'conditions' => array(
						'Template.theme_id' => $this->request->data['Theme']['id']
					),
					'fields' => array(
						'Template.location'
					)
				));
				echo '<div id="theme-update-div" class="alert alert-success">
	    					<button class="close" data-dismiss="alert">×</button>
	    					<strong>Success</strong> The theme has been refreshed.<br />';

	    		$key = 0;
	    		$templates = array();

	    		$this->Template->create();

				foreach($files as $file)
				{
					if (!empty($data) && !$this->Template->searchArray($data, $file) || empty($data))
					{
						$title = explode('/', $file);

						$templates[$key]['Template']['title'] = end($title);
						$templates[$key]['Template']['label'] = 
							str_replace('Plugin View', 'Plugin', str_replace('.ctp','', Inflector::humanize(str_replace("/"," ", $file))));
						$templates[$key]['Template']['location'] = $file;
						$templates[$key]['Template']['theme_id'] = $this->request->data['Theme']['id'];
						$templates[$key]['Template']['created'] = $this->Template->dateTime();
						$templates[$key]['Template']['nowrite'] = true;

						$key++;
					}
				}

				$this->Template->saveAll($templates);
				echo "</div>";
			} else {
    				return '
    				<div id="theme-update-div" class="alert alert-error">
    					<button class="close" data-dismiss="alert">×</button>
    					<strong>Error</strong> The Theme could not be refreshed.
    				</div>';				
			}
		}
	}

	/**
	* Function that gets all folders and returns them as options for a select
	*
	* @return array of folders
	*/
	public function ajax_template_locations()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->RequestHandler->isAjax())
    	{
    		if ($this->request->data['Theme']['id'] == 1)
    		{
    			$folders = $this->Template->folderFullList();
    		} else {
    			$folders = $this->Template->folderFullList($this->request->data['Theme']['title']);
    		}

    		foreach($folders as $key => $row)
    		{
    			$list .= "<option value='".$key."'>".$row."</option>";
    		}

    		return $list;
    	}
	}

	/**
	* The function gets parameters needed on the view such as status of the Theme
	*
	* @param path to themes
	* @return array of theme data
	*/
	private function getThemes($path)
	{
		$themes = array();
		$api_lookup = array();
		$exclude = array('empty');

		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".") {
                	$json = $path . DS . $file . DS . 'theme.json';

					if (file_exists($json) && is_readable($json)) {
		 				$handle = fopen($json, "r");
		 				$json_file = fread($handle, filesize($json));

		 				$themes[$file] = json_decode($json_file, true);

		 				if (!empty($themes[$file]['api_id'])) {
		 					$api_lookup[] = $themes[$file]['api_id'];
						}
		 			} else {
		 				$themes[$file]['title'] = $file;
		 			}

		 			$upgrade = $path . DS . $file . DS . 'Install' . DS . 'upgrade.json';

		 			if (file_exists($upgrade) && is_readable($upgrade))
		 			{
		 				$themes[$file]['upgrade_status'] = 1;
		 			} else {
		 				$themes[$file]['upgrade_status'] = 0;
		 			}

		 			if (strstr($path, 'Old')) {
		 				$themes[$file]['status'] = 0;
		 			} else {
		 				$themes[$file]['status'] = 1;
		 			}
                }
            }
		}

		return array(
			'themes' => $themes,
			'api_lookup' => $api_lookup
		);
	}
}