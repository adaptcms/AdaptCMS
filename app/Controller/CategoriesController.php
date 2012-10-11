<?php

class CategoriesController extends AppController {
	public $name = 'Categories';
	public $uses = array('Category');

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
			$this->paginate = array(
	            'order' => 'Category.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Category.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
		} else {
			$this->paginate = array(
	            'order' => 'Category.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Category.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }
        
		$this->request->data = $this->paginate('Category');
	}

	public function admin_add()
	{

        if ($this->request->is('post')) {
        		$this->request->data['Category']['slug'] = $this->slug($this->request->data['Category']['title']);
            if ($this->Category->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your category has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your category.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      $this->Category->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Category->read();
	    } else {
	    	$this->request->data['Category']['slug'] = $this->slug($this->request->data['Category']['title']);

	        if ($this->Category->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your category has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your category.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Category->id = $id;

	    if (!empty($permanent)) {
	    	$delete = $this->Category->delete($id);
	    } else {
	    	$delete = $this->Category->saveField('deleted_time', $this->Category->dateTime());
	    }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The category `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The category `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function admin_restore($id = null, $title = null)
	{
		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Category->id = $id;

	    if ($this->Category->saveField('deleted_time', '0000-00-00 00:00:00')) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The category `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The category `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

	public function view($slug = null)
	{
		$this->loadModel('SettingValue');
		
		if ($limit = $this->SettingValue->findByTitle('Number of Articles to list on Category Page')) {
			$limit = $limit['SettingValue']['data'];
		} else {
			$limit = 10;
		}

		$this->paginate = array(
			'order' => 'Article.created DESC',
			'conditions' => array(
				'Category.slug' => $slug,
				'Article.status' => 1,
				'Article.deleted_time' => '0000-00-00 00:00:00'
			),
			'contain' => array(
				'Category',
				'ArticleValue' => array(
					'Field'
				)
				
			),
			'limit' => $limit
		);

		$this->request->data = $this->Category->Article->getAllRelatedArticles(
			$this->paginate('Article')
		);

		$this->set('title_for_layout', ucfirst($slug));

		if ($this->theme != "Default" && 
			file_exists(VIEW_PATH.'Themed/'.$this->theme.'/Categories/'.$slug.'.ctp') ||\
			file_exists(VIEW_PATH.'Categories/'.$slug.'.ctp')) {
			$this->render(implode('/', array($slug)));
		}
	}
}