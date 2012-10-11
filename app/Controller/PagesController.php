<?php

class PagesController extends AppController 
{
	public $name = 'Pages';

	public function admin_index()
	{
		if (!isset($this->params->named['trash'])) {
	        $this->paginate = array(
	            'order' => 'Page.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Page.deleted_time' => '0000-00-00 00:00:00'
	            )
	        );
	    } else {
	        $this->paginate = array(
	            'order' => 'Page.created DESC',
	            'limit' => $this->pageLimit,
	            'conditions' => array(
	            	'Page.deleted_time !=' => '0000-00-00 00:00:00'
	            )
	        );
        }

        $this->request->data = $this->paginate('Page');
	}

	public function admin_add()
	{
        if ($this->request->is('post')) {
        		$this->request->data['Page']['slug'] = $this->slug($this->request->data['Page']['title']);

	        	$fh = fopen(VIEW_PATH."Pages/".$this->request->data['Page']['slug'].".ctp", 'w') or die("can't open file");
				fwrite($fh, $this->request->data['Page']['content']);
				fclose($fh);

            if ($this->Page->save($this->request->data)) {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your page has been added.', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to add your page.', 'default', array('class' => 'alert alert-error'));
            }
        } 
	}

	public function admin_edit($id = null)
	{

      	$this->Page->id = $id;

	    if ($this->request->is('get')) {
	        $this->request->data = $this->Page->read();
	    } else {
	    	$this->request->data['Page']['slug'] = $this->slug($this->request->data['Page']['title']);
        	
        	$fh = fopen(VIEW_PATH."Pages/".$this->request->data['Page']['slug'].".ctp", 'w') or die("can't open file");
			fwrite($fh, $this->request->data['Page']['content']);
			fclose($fh);

			if ($this->request->data['Page']['title'] != $this->request->data['Page']['old_title']) {
				unlink(VIEW_PATH."Pages/".$this->slug($this->request->data['Page']['old_title']).".ctp");
			}

	        if ($this->Page->save($this->request->data)) {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> Your page has been updated.', 'default', array('class' => 'alert alert-success'));
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> Unable to update your page.', 'default', array('class' => 'alert alert-error'));
	        }
	    }

	}

	public function admin_delete($id = null, $title = null, $permanent = null)
	{

		if ($this->request->is('post')) {
	        throw new MethodNotAllowedException();
	    }

	    $this->Page->id = $id;

        if (!empty($permanent)) {
            $delete = $this->Page->delete($id);
		    if (is_readable(VIEW_PATH.'Pages/'.$this->slug($title).'.ctp')) {
		    	unlink(VIEW_PATH.'Pages/'.$this->slug($title).'.ctp');
		    }
        } else {
            $delete = $this->Page->saveField('deleted_time', $this->Page->dateTime());
        }

	    if ($delete) {
	        $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The page `'.$title.'` has been deleted.', 'default', array('class' => 'alert alert-success'));
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The page `'.$title.'` has NOT been deleted.', 'default', array('class' => 'alert alert-error'));
	        $this->redirect(array('action' => 'index'));
	    }
	}

    public function admin_restore($id = null, $title = null)
    {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Page->id = $id;

        if ($this->Page->saveField('deleted_time', '0000-00-00 00:00:00')) {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Success</strong> The page `'.$title.'` has been restored.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(Configure::read('alert_btn').'<strong>Error</strong> The page `'.$title.'` has NOT been restored.', 'default', array('class' => 'alert alert-error'));
            $this->redirect(array('action' => 'index'));
        }
    }

	public function display() {
		$path = func_get_args();

		if ($path[0] == 'home') {
			$this->loadModel('Article');
			$this->loadModel('SettingValue');

			$setting1 = $this->SettingValue->findByTitle('Number of Articles on Homepage');
			$setting2 = $this->SettingValue->findByTitle('Categories of Articles to show on homepage');

			if (empty($setting1)) {
				$setting1['SettingValue']['data'] = 5;
			}

			if (!empty($setting2)) {
				$categories = array_map('strtolower',
					array_map('trim', 
						explode(
							",",
							$setting2['SettingValue']['data']
						)
					)
				);
			} else {
				$this->loadModel('Category');

				$category = $this->Category->find('first');
				$categories = $category['Category']['slug'];
			}

			$this->paginate = array(
				'contain' => array(
					'Category',
					'User',
					'ArticleValue' => array(
						'Field'
					)
				),
				'limit' => $setting1['SettingValue']['data'],
				'conditions' => array(
					'Article.status' => 1,
					'Article.deleted_time' => '0000-00-00 00:00:00',
					'Category.slug' => $categories
				),
				'order' => 'Article.created DESC'
			);

	        $this->request->data = $this->Article->getAllRelatedArticles(
	        	$this->paginate('Article')
	        );
		}

		if ($path[0] == 'home' or $path[0] == 'denied') {
			$count = count($path);
			if (!$count) {
				$this->redirect('/');
			}
			$page = $subpage = $title_for_layout = null;

			if (!empty($path[0])) {
				$page = $path[0];
			}
			if (!empty($path[1])) {
				$subpage = $path[1];
			}
			if (!empty($path[$count - 1])) {
				$title_for_layout = Inflector::humanize($path[$count - 1]);
			}
			$this->set(compact('page', 'subpage', 'title_for_layout'));
			$this->render(implode('/', $path));
		} else {
			$this->request->data = $this->Page->findBySlug($path[0]);

			if (!empty($this->request->data)) {
				$this->set('title_for_layout', $this->request->data['Page']['title']);
			}

			$this->render(implode('/', $path));
		}
	}
}
