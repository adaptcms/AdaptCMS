<?php

class TemplatesController extends AppController{
	public $name = 'Templates';

	public function admin_index()
	{
		$this->loadModel('Theme');

		if (!isset($this->params->named['trash_temp'])) {
			$this->paginate = array(
	            'order' => 'Template.created DESC',
	            'contain' => array(
	            	'Theme'
	            ),
	            'conditions' => array(
	            	'Template.deleted_time' => '0000-00-00 00:00:00'
	            ),
	            'limit' => $this->pageLimit
	        );
		} else {
			$this->paginate = array(
	            'order' => 'Template.created DESC',
	            'contain' => array(
	            	'Theme'
	            ),
	            'conditions' => array(
	            	'Template.deleted_time !=' => '0000-00-00 00:00:00'
	            ),
	            'limit' => $this->pageLimit
	        );
	    }
        
		$this->request->data['Template'] = $this->paginate('Template');

		$this->request->data['Themes'] = $this->Theme->find('all', array(
                'order' => 'Theme.id ASC'
            )
        );

		foreach ($this->request->data['Themes'] as $key => $row) {
			if ($row['Theme']['deleted_time'] == "0000-00-00 00:00:00") {
				$themes[$row['Theme']['id']] = $row['Theme']['title'];
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
	}

	public function admin_add()
	{
		$this->loadModel('Theme');

		$themes = $this->Theme->find('list', array(
                'order' => 'Theme.id ASC',
                'conditions' => array(
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                	)
                )
        );

		$this->set(compact('themes'));

		if (empty($this->params->pass[0])) {
			$theme_id = 1;
		} else {
			$theme_id = $this->params->pass[0];
		}
	    
	    $this->set(compact('theme_id'));

	    if ($theme_id == 1) {
			$this->set('locations', $this->Template->folderFullList());
		} else {
			$this->set('locations', $this->Template->folderFullList($themes[$theme_id]));
		}

        if ($this->request->is('post')) {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->request->data['Template']['theme_id'],
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                ),
                'fields' => array(
                	'title'
                )
        	));

			$file = $this->slug($this->request->data['Template']['title'], 1);

			if ($theme['Theme']['title'] == 'Default') {
				$pre = null;
			} else {
				// $pre = 'Themed/'.$theme['Theme']['title'].'/';
				$pre = null;
			}

        	$this->request->data['Template']['location'] = $pre.$this->request->data['Template']['location'].'/'.$file.'.ctp';

        	$fh = fopen(VIEW_PATH.$this->request->data['Template']['location'], 'w') or die("can't open file");
			fwrite($fh, $this->request->data['Template']['template']);
			fclose($fh);

            if ($this->Template->save($this->request->data)) {
                $this->Session->setFlash('Your template has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your template.', 'flash_error');
            }
        } 
	}

	public function admin_edit($id = null)
	{

      	$this->Template->id = $id;
		$this->loadModel('Theme');

		$themes = $this->Theme->find('list', array(
                'order' => 'Theme.id ASC',
                'conditions' => array(
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                	)
                )
        );

		$this->set(compact('themes'));

	    if ($this->request->is('get')) {
	    	$this->request->data = $this->Template->read();

	        if (is_readable(VIEW_PATH.$this->request->data['Template']['location'])) {
		 		$handle = fopen(VIEW_PATH.$this->request->data['Template']['location'], "r");
		 		if (filesize(VIEW_PATH.$this->request->data['Template']['location']) > 0) {
					$template_contents = fread($handle, filesize(VIEW_PATH.$this->request->data['Template']['location']));
	    		} else {
	    			$template_contents = null;
	    		}
	    	} else {
	    		$template_contents = null;
	    	}

	    	$this->set(compact('template_contents'));

	    	$this->set('location', str_replace(
	    		"/".basename(
	    			$this->request->data['Template']['location']
	    		), 
	    		"",
	    		$this->request->data['Template']['location']
	    		)
	    	);
	    } else {
			$theme = $this->Theme->find('first', array(
                'conditions' => array(
                	'Theme.id' => $this->request->data['Template']['theme_id'],
                	'Theme.deleted_time' => '0000-00-00 00:00:00'
                ),
                'fields' => array(
                	'title'
                )
        	));

			$file = str_replace(
				"_".strtolower(basename($this->request->data['Template']['location'])), 
				"",
				$this->slug($this->request->data['Template']['title'], 1
			));

			if ($theme['Theme']['title'] == 'Default') {
				$pre = null;
			} else {
				// $pre = 'Themed/'.$theme['Theme']['title'].'/';
				$pre = null;
			}

        	$this->request->data['Template']['location'] = 
        		$pre.$this->request->data['Template']['location'].'/'.$file.'.ctp';

        	$fh = fopen(VIEW_PATH.$this->request->data['Template']['location'], 'w') or die("can't open file");
			fwrite($fh, $this->request->data['Template']['template']);
			fclose($fh);

			if ($this->request->data['Template']['location'] != $this->request->data['Template']['old_location']
				or $this->request->data['Template']['title'] != $this->request->data['Template']['old_title']
				or $this->request->data['Template']['theme_id'] != $this->request->data['Template']['old_theme']) {
				if (is_readable(VIEW_PATH.$this->request->data['Template']['old_location'])) {
					unlink(VIEW_PATH.$this->request->data['Template']['old_location']);
				}
			}

	        if ($this->Template->save($this->request->data)) {
	            $this->Session->setFlash('Your template has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your template.', 'flash_error');
	        }
	    }

	    if ($this->request->data['Template']['theme_id'] == 1) {
			$this->set('locations', $this->Template->folderFullList());
		} else {
			$this->set('locations', $this->Template->folderFullList($themes[$this->request->data['Template']['theme_id']]));
		}
	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{

		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Template->id = $id;

	    $location = $this->Template->find('first', array(
	    	'conditions' => array(
	    		'Template.id' => $id,
    		),
	    	'fields' => array(
	    		'location'
	    	)
	    ));

        if (!empty($permanent)) {
            $delete = $this->Template->delete($id);
		    if (is_readable(VIEW_PATH.$location['Template']['location'])) {
		    	unlink(VIEW_PATH.$location['Template']['location']);
		    }
        } else {
            $delete = $this->Template->saveField('deleted_time', $this->Template->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash('The template `'.$title.'` has been deleted.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The template `'.$title.'` has NOT been deleted.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Template->id = $id;

        if ($this->Template->saveField('deleted_time', '0000-00-00 00:00:00')) {
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

	public function admin_ajax_quick_search()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->request->is('ajax')) {
    		if (!empty($this->request->data['theme'])) {
    			$conditions = array(
               		'conditions' => array(
	                	'OR' => array(
	                		'Template.location LIKE' => '%'.$this->request->data['search'].'%',
	                		'Template.title LIKE' => '%'.$this->request->data['search'].'%'
	                	),
                		'Template.theme_id' => $this->request->data['theme'],
                		'Template.deleted_time' => '0000-00-00 00:00:00'
               		),
	                'contain' => array(
	                	'Theme'
	                ),
	                'fields' => array(
	                	'id', 'title', 'location', 'Theme.title'
	                )
            	);
    		} else {
    			$conditions = array(
               		'conditions' => array(
	                	'OR' => array(
	                		'Template.location LIKE' => '%'.$this->request->data['search'].'%',
	                		'Template.title LIKE' => '%'.$this->request->data['search'].'%'
	                	),
	                	'Template.deleted_time' => '0000-00-00 00:00:00'
               		),
	                'contain' => array(
	                	'Theme'
	                ),
	                'fields' => array(
	                	'id', 'title', 'location', 'Theme.title'
	                )
            	); 			
    		}

            $results = $this->Template->find('all', $conditions);

            foreach($results as $result) {
            	if (!empty($this->request->data['element']) && 
            		strstr($result['Template']['location'], "Elements/") || empty($this->request->data['element'])) {
	                $data[] = array(
	                	'id' =>$result['Template']['id'],
	                	'title' => $result['Template']['title'],
	                	'location' => 
	                		' ('.$result['Template']['location'].') - <i>'.$result['Theme']['title'].' Theme</i>'
	                );
	        	}
            }

            return json_encode($data);
    	}
	}

	public function ajax_theme_refresh()
	{
		$this->layout = 'ajax';
		$this->autoRender = false;

		if ($this->RequestHandler->isAJax()) {
			$this->loadModel('Theme');

			if (!empty($this->request->data['Theme']['name'])) {
				$files = $this->Template->folderAndFilesList($this->request->data['Theme']['name']);
			} else {
				$files = $this->Template->folderAndFilesList();
			}

			if (!empty($files)) {
				$data = $this->Template->find("all", array(
					'conditions' => array(
						'Template.theme_id' => $this->request->data['Theme']['id']
					),
					'fields' => array(
						'Template.location'
						)
					)
				);
				echo '<div id="theme-update-div" class="alert alert-success">
	    					<button class="close" data-dismiss="alert">×</button>
	    					<strong>Success</strong> The theme has been refreshed.<br />';

				foreach($files as $file) {
					if (!$this->Template->searchArray($data, $file)) {
						$this->Template->create();

						$title = $this->Theme->camelCase(
									str_replace(".ctp","",basename($file)),1
								);
						$title2 = explode("/", str_replace(basename($file),"",$file));
						end($title2);

						$template['Template']['title'] = $title.' '.prev($title2);
						$template['Template']['location'] = $file;
						$template['Template']['theme_id'] = $this->request->data['Theme']['id'];
						$template['Template']['created'] = date('Y-m-d H:i:s');

						$this->Template->save($template);
					}
				}
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

	public function ajax_template_locations()
	{
    	$this->layout = 'ajax';
    	$this->autoRender = false;

    	if ($this->RequestHandler->isAjax()) {
    		if ($this->request->data['Theme']['id'] == 1) {
    			$folders = $this->Template->folderFullList();
    		} else {
    			$folders = $this->Template->folderFullList($this->request->data['Theme']['title']);
    		}

    		foreach($folders as $key => $row) {
    			// $data[$key] = $row;
    			$list .= "<option value='".$key."'>".$row."</option>";
    		}

    		return $list;
    	}
	}
}