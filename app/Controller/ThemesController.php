<?php

class ThemesController extends AppController{
	public $name = 'Themes';

	public function admin_add()
	{
        if ($this->request->is('post')) {
        	$this->loadModel('Template');	

        	$this->request->data['Theme']['title'] = $this->slug($this->request->data['Theme']['title']);
    		$this->request->data['Theme']['title'] = $this->Theme->camelCase($this->request->data['Theme']['title']);

    		mkdir(VIEW_PATH.'Themed/'.$this->request->data['Theme']['title']);

            mkdir(WWW_ROOT.'themes/'.$this->request->data['Theme']['title']);
            mkdir(WWW_ROOT.'themes/'.$this->request->data['Theme']['title'].'/css');
            mkdir(WWW_ROOT.'themes/'.$this->request->data['Theme']['title'].'/js');
            mkdir(WWW_ROOT.'themes/'.$this->request->data['Theme']['title'].'/img');
    		// chmod(VIEW_PATH.'Themed/'.$this->request->data['Theme']['title'], 0777);
    		
    		foreach($this->Template->folderList() as $folder) {
    			mkdir(VIEW_PATH.'Themed/'.$this->request->data['Theme']['title'].'/'.$folder);
    		}

            if ($this->Theme->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your theme has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'templates', 'action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your theme.', 'default', array('class' => 'alert alert-error'));
            }
        }
	}

	public function admin_edit($id = null)
	{
        if (!is_numeric($id)) {
            $findId = $this->Theme->findByTitle($id);
            $id = $findId['Theme']['id'];
        }

        $this->Theme->id = $id;
        
	    if ($this->request->is('get')) {
	        $this->request->data = $this->Theme->read();

            if ($id == 1) {
                $this->set('assets_list', $this->Theme->assetsList());
                $this->set('assets_list_path', WWW_ROOT);
                $this->set('webroot_path', $this->webroot);
            } else {
                $this->set('assets_list', $this->Theme->assetsList($id, $this->request->data['Theme']['title']));
                $this->set('assets_list_path', WWW_ROOT.'themes/'.$this->request->data['Theme']['title'].'/');
                $this->set('webroot_path', $this->webroot.'themes/'.$this->request->data['Theme']['title'].'/');
            }
	    } else {
        	$this->loadModel('Template');	
        	$this->request->data['Theme']['title'] = $this->slug($this->request->data['Theme']['title']);
    		$this->request->data['Theme']['title'] = $this->Theme->camelCase($this->request->data['Theme']['title']);

    		if ($this->request->data['Theme']['title'] != $this->request->data['Theme']['old_title']) {
    			if (file_exists(VIEW_PATH.'Themed/'.$this->request->data['Theme']['old_title'])) {
    				rename(
    					VIEW_PATH.'Themed/'.$this->request->data['Theme']['old_title'],
    					VIEW_PATH.'Themed/'.$this->request->data['Theme']['title']
    				);
                    rename(
                        WWW_ROOT.'themes/'.$this->request->data['Theme']['old_title'],
                        WWW_ROOT.'themes/'.$this->request->data['Theme']['title']
                    );
    			} else {
					$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your theme.', 'default', array('class' => 'alert alert-error'));	
    				$fail = 1;
    			}
    		}

	        if ($this->Theme->save($this->request->data) && !isset($fail)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your theme has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your theme.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{

		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

        $this->Theme->id = $id;

        if (!empty($permanent)) {
        	$this->Theme->Template->deleteAll(array('Template.theme_id' => $id));
            $delete = $this->Theme->delete($id);

            $this->rrmdir(VIEW_PATH.'Themed/'.$title);
            $this->rrmdir(WWW_ROOT.'themes/'.$title);
        } else {
        	$this->Theme->Template->updateAll(
        		array('Template.deleted_time' => $this->Theme->dateTime()),
        		array('Template.theme_id' => $id)
        	);
            $delete = $this->Theme->saveField('deleted_time', $this->Theme->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The theme `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('controller' => 'templates', 'action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The theme `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('controller' => 'templates', 'action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Theme->id = $id;

		$this->Theme->Template->updateAll(
			array('Template.deleted_time' => '0000-00-00 00:00:00'),
			array('Template.theme_id' => $id)
		);

        if ($this->Theme->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The theme `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The theme `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array('controller' => 'templates', 'action' => 'index'));
        }
    }

	public function rrmdir($dir) {
		/** 
		 * Source: Anonymous
		 * http://us2.php.net/manual/en/function.rmdir.php#107233
		**/
	   if (is_dir($dir)) {
	     $objects = scandir($dir);
	     foreach ($objects as $object) {
	       if ($object != "." && $object != "..") {
	         if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
	       }
	     }
	     reset($objects);
	     rmdir($dir);
	   }
	 }
}