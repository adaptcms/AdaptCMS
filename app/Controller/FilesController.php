<?php

class FilesController extends AppController {
	public $name = 'Files';
	public $helpers = array('Number');
	public $file_types_editable = array(
		'txt',
		'php',
		'html',
		'css',
		'js',
		'phps',
		'htm'
	);

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
	        $this->paginate = array(
	            'order' => 'File.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'File.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
	    } else {
	        $this->paginate = array(
	            'order' => 'File.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'File.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }
        
        $this->request->data = $this->paginate('File');
	}

	public function admin_add()
	{
		$this->set('file_types', $this->file_types_editable);
		
        if ($this->request->is('post')) {
            if ($this->File->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your file has been upload.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to upload your file.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      $this->File->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->File->read();

	        $file = WWW_ROOT.
	        		$this->request->data['File']['dir'].
	        		$this->request->data['File']['filename'];

    		if (is_readable($file)) {
		        $ext = pathinfo(
		        		$file, 
		        		PATHINFO_EXTENSION
		        );

		        if (in_array($ext, $this->file_types_editable)) {
			 		$handle = fopen($file, "r");

			 		if (filesize($file) > 0) {
						$this->set('file_contents', fread($handle, filesize($file)));
		    		}
		        }
	        }
	    } else {
	    	if (!empty($this->request->data['File']['content'])) {
	        	$fh = fopen(WWW_ROOT.$this->request->data['File']['dir'].$this->request->data['File']['old_filename'], 'w') or die("can't open file");
				fwrite($fh, $this->request->data['File']['content']);
				fclose($fh);
			}

	        if ($this->File->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your file has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your file.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $permanent)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $file = $this->File->find('first', array(
	    	'conditions' => array(
	    		'File.id' => $id
	    		),
	    	'fields' => array(
	    		'filename'
	    		)
	    	)
	    );

	    $this->File->id = $id;

        if (!empty($permanent)) {
            $delete = $this->File->delete($id);
		    if (file_exists(WWW_ROOT.'uploads/'.$file['File']['filename']) && 
		    	is_file(WWW_ROOT.'uploads/'.$file['File']['filename'])) {
		    		unlink(WWW_ROOT.'uploads/'.$file['File']['filename']);

		    	if (file_exists(WWW_ROOT.'uploads/thumb/'.$file['File']['filename']) && 
		    		is_file(WWW_ROOT.'uploads/thumb/'.$file['File']['filename'])) {
		    			unlink(WWW_ROOT.'uploads/thumb/'.$file['File']['filename']);
		    	}
		    }
        } else {
            $delete = $this->File->saveField('deleted_time', $this->File->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The file `'.$file['File']['filename'].'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The file `'.$file['File']['filename'].'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->File->id = $id;

        if ($this->File->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The file `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The file `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array('action' => 'index'));
        }
    }
}