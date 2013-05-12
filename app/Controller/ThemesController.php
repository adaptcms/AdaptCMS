<?php

class ThemesController extends AppController
{
    /**
    * Name of the Controller, 'Themes'
    */
	public $name = 'Themes';

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
            if ($this->Theme->save($this->request->data))
            {
                $this->Session->setFlash('Your theme has been added.', 'flash_success');
                $this->redirect(array('controller' => 'templates', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your theme.', 'flash_error');
            }
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
        if (!is_numeric($id))
        {
            $findId = $this->Theme->findByTitle($id);
            $id = $findId['Theme']['id'];
        }

        $this->Theme->id = $id;

        if (!empty($this->request->data))
        {
	        if ($this->Theme->save($this->request->data)) {
	            $this->Session->setFlash('Your theme has been updated.', 'flash_success');
	            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your theme.', 'flash_error');
	        }
	    }

        $this->request->data = $this->Theme->read();

        if ($id == 1)
        {
            $this->set('assets_list', $this->Theme->assetsList());
            $this->set('assets_list_path', WWW_ROOT);
            $this->set('webroot_path', $this->webroot);
        } else {
            $config_new = $this->getConfig($this->request->data['Theme']['title']);
            $config_old = $this->getConfig($this->request->data['Theme']['title'], 'Old_Themed');

            $this->set('assets_list', $this->Theme->assetsList($this->request->data['Theme']['title']));
            $this->set('assets_list_path', WWW_ROOT.'themes/' . $this->request->data['Theme']['title'].'/');
            $this->set('webroot_path', $this->webroot.'themes/' . $this->request->data['Theme']['title'].'/');
            $this->set('config', array_merge($config_new, $config_old));
        }
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
        $this->Theme->id = $id;

        if (!empty($permanent))
        {
        	// $this->Theme->Template->deleteAll(array('Template.theme_id' => $id));
            $delete = $this->Theme->delete($id);

            $this->rrmdir(VIEW_PATH . 'Themed/' . $title);
            $this->rrmdir(WWW_ROOT . 'themes/' . $title);
        } else {
        	$this->Theme->Template->updateAll(
        		array(
                    'Template.deleted_time' => "'" . $this->Theme->dateTime() . "'"
                ),
        		array(
                    'Template.theme_id' => $id
                )
        	);
            $delete = $this->Theme->saveField('deleted_time', $this->Theme->dateTime());
        }

	    if ($delete)
        {
	        $this->Session->setFlash('The theme `' . $title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The theme `' . $title.'` has NOT been deleted.', 'flash_error');
	    }

        if (!empty($permanent))
        {
            $count = $this->Theme->find('count', array(
                'conditions' => array(
                    'Theme.deleted_time !=' => '0000-00-00 00:00:00'
                )
            ));

            $params = array(
                'controller' => 'templates',
                'action' => 'index'
            );

            if ($count > 0)
            {
                $params['trash'] = 1;
            }

            $this->redirect($params);
        } else {
            $this->redirect(array(
                'controller' => 'templates',
                'action' => 'index'
            ));
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
        $this->Theme->id = $id;

		$this->Theme->Template->updateAll(
			array('Template.deleted_time' => "'0000-00-00 00:00:00'"),
			array('Template.theme_id' => $id)
		);

        if ($this->Theme->saveField('deleted_time', '0000-00-00 00:00:00'))
        {
            $this->Session->setFlash('The theme `' . $title.'` has been restored.', 'flash_success');
            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
        } else {
            $this->Session->setFlash('The theme `' . $title.'` has NOT been restored.', 'flash_error');
            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
        }
    }

    /**
    * Function allows user to add a web asset to a default/non-default theme or plugin
    *
    * Before POST, displays two-step add form and after POST redirects to appropiate page with flash msg.
    *
    * @param theme name of theme
    * @return mixed
    */
    public function admin_asset_add($theme)
    {
        if ($theme == 'Default')
        {
            $path = WWW_ROOT;
        } else {
            $path = WWW_ROOT.'themes/' . $theme.'/';
        }

        if (strstr($theme, 'Plugin'))
        {
            $plugin = str_replace('Plugin', '', $theme);

            $path = APP . 'Plugin' . DS . $plugin . DS . 'webroot' . DS;

            $this->set(compact('plugin'));
        }

        $file_types = array_combine($this->Theme->file_types_editable, $this->Theme->file_types_editable);

        $this->set(compact('theme', 'path', 'file_types'));

        if (!empty($this->request->data))
        {
            $path = $path . $this->request->data['Asset']['folder'];

            if ($this->request->data['Asset']['filename']['error'] == 4)
            {
                $filename = $this->request->data['Asset']['file_name'] . '.' . $this->request->data['Asset']['file_extension'];
            } else {
                $tmp = $this->request->data['Asset']['filename']['tmp_name'];
                $filename = $this->request->data['Asset']['filename']['name'];

                if (!move_uploaded_file($tmp, $path . $filename))
                {
                    $save = 1;
                }
            }

            if (!empty($this->request->data['Asset']['content']))
            {
                $fh = fopen($path . $filename, 'w') or die("can't open file");
                if (!fwrite($fh, $this->request->data['Asset']['content']))
                {
                    $save = 1;
                }
                fclose($fh);
            }

            if (!empty($plugin))
            {
                $redirect = array('action' => 'assets', 'controller' => 'plugins', $plugin);
            } else {
                $redirect = array('action' => 'edit', $theme, '#' => 'assets');
            }

            if (empty($save))
            {
                $this->Session->setFlash('The asset `' . $filename.'` has been added.', 'flash_success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('The asset `' . $filename.'` has NOT been added.', 'flash_error');
                $this->redirect($redirect);
            }
        }
    }

    /**
    * Function allows user to edit a web asset for a default/non-default theme or plugin
    *
    * Before POST, displays edit form to alter content and after POST redirects to appropiate page with flash msg.
    *
    * @param file path and name of file
    * @param theme name of theme
    * @return mixed
    */
    public function admin_asset_edit($file, $theme)
    {
        $file = str_replace('__', '/', str_replace('&', '.', $file) );

        if ($theme == 'Default')
        {
            $path = WWW_ROOT;
        } else {
            $path = WWW_ROOT.'themes/' . $theme.'/';
        }

        if (strstr($theme, 'Plugin'))
        {
            $plugin = str_replace('Plugin', '', $theme);
            $file = str_replace('/Plugin', '', $file);

            $path = APP . 'Plugin';

            $this->set(compact('plugin'));
        }

        if (!empty($file) && is_readable($path . $file))
        {
            $ext = pathinfo(
                    $path . $file
            );

            if (in_array($ext['extension'], $this->Theme->file_types_editable))
            {
                $handle = fopen($path . $file, "r");

                if ($size = filesize($path . $file)) {
                    $this->set('file_contents', fread($handle, $size));
                }
            }

            $this->set(compact('ext'));
        }

        $dir = $ext['dirname'];

        $this->set(compact('theme', 'dir'));

        if (!empty($this->request->data))
        {
            $path = $this->request->data['Asset']['dir'] . DS;
            $filename = $this->request->data['Asset']['filename'];

            if ($filename != $this->request->data['Asset']['old_filename'])
            {
                rename(
                    $path . $this->request->data['Asset']['old_filename'],
                    $path . $filename
                );
            }

            if (isset($this->request->data['Asset']['content']) && file_exists($path . $filename))
            {
                $fh = fopen($path . $filename, 'w') or die("can't open file");
                if (!fwrite($fh, $this->request->data['Asset']['content']))
                {
                    $save = 1;
                }
                fclose($fh);
            }

            if (!empty($plugin))
            {
                $redirect = array('action' => 'assets', 'controller' => 'plugins', $plugin);
            } else {
                $redirect = array('action' => 'edit', $theme, '#' => 'assets');
            }

            if (empty($save))
            {
                $this->Session->setFlash('The asset `' . $filename.'` has been saved.', 'flash_success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('The asset `' . $filename.'` has NOT been saved.', 'flash_error');
                $this->redirect($redirect);
            }
        }
    }

    /**
    * Function allows user to delete a web asset for a default/non-default theme or plugin
    *
    * Redirects to appropiate page with flash msg after attempt to delete.
    *
    * @param file path and name of file
    * @param theme name of theme
    * @return mixed
    */
    public function admin_asset_delete($file, $theme)
    {
        $file = str_replace('__', '/', str_replace('&', '.', $file) );

        if ($theme == 'Default')
        {
            $path = WWW_ROOT;
        } else {
            $path = WWW_ROOT.'themes/' . $theme.'/';
        }

        if (strstr($theme, 'Plugin'))
        {
            $plugin = str_replace('Plugin', '', $theme);

            $path = APP;
        }

        if (!empty($file) && is_readable($path . $file))
        {
            $ext = pathinfo(
                $path . $file
            );

            if (!unlink($path . $file))
            {
                $save = 1;
            }
        }

        if (!empty($plugin))
        {
            $redirect = array('action' => 'assets', 'controller' => 'plugins', $plugin);
        } else {
            $redirect = array('action' => 'edit', $theme, '#' => 'assets');
        }

        if (empty($save))
        {
            $this->Session->setFlash('The asset `' . $ext['basename'].'` has been deleted.', 'flash_success');
            $this->redirect($redirect);
        } else {
            $this->Session->setFlash('The asset `' . $ext['basename'].'` has NOT been deleted.', 'flash_error');
            $this->redirect($redirect);
        }
    }

    /**
    * Removes files inside of a folder
    *
    * @param dir path to folder to loop through
    * @return null
    */
	public function rrmdir($dir)
    {
		/** 
		 * Source: Anonymous
		 * http://us2.php.net/manual/en/function.rmdir.php#107233
		**/
        if (is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir . "/" . $object) == "dir")
                    {
                        rmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
	}

    /**
    * Gets config file for a theme with a JSON file
    *
    * @return json_encode of configuration, blank array on false
    */
    public function getConfig($name, $folder = 'Themed')
    {
        $json = VIEW_PATH . DS . $folder . DS . $name . DS . 'theme.json';

        if (file_exists($json) && is_readable($json)) {
            $handle = fopen($json, "r");
            $json_file = fread($handle, filesize($json));

            return json_decode($json_file, true);
        }

        return array();
    }
}