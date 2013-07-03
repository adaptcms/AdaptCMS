<?php
App::uses('AppController', 'Controller');

/**
 * Class CronController
 */
class CronController extends AppController
{
    /**
    * Name of the Controller, 'Cron'
    */
	public $name = 'Cron';

    /**
    * array of permissions for this page
    */
	private $permissions;

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    *
    * We also get a modules list that will be used on edit and add, along with period amount.
    */
	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->params->action == 'admin_add' || $this->params->action == 'admin_edit')
		{
			$modules_list = $this->Cron->Module->find('all', array(
				'order' => 'is_plugin ASC, title ASC'
			));

			foreach($modules_list as $module)
			{
				$id = $module['Module']['id'];

				if ($module['Module']['is_plugin'] == 1)
				{
					$modules[$id] = 'Plugin - ' . $module['Module']['title'];
				}
                else
                {
					$modules[$id] = $module['Module']['title'];
				}
			}

			$period_amount = array();
			for($i = 1; $i <= 24; $i++)
			{
				$period_amount[$i] = $i;
			}

			$this->set(compact('modules', 'period_amount'));
		}

		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated index of Cron Jobs
    *
    * @return array of cron data
    */
	public function admin_index()
	{
		$conditions = array();

		if (!isset($this->params->named['trash'])) {
			$conditions['Cron.deleted_time'] = '0000-00-00 00:00:00';
		} else {
			$conditions['Cron.deleted_time !='] = '0000-00-00 00:00:00';
        }

		$this->paginate = array(
            'order' => 'Cron.created DESC',
            'limit' => $this->pageLimit,
            'conditions' => $conditions
        );
        
		$this->request->data = $this->paginate('Cron');
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
            if ($this->Cron->save($this->request->data))
            {
                $this->Session->setFlash('Your cron job has been added.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your cron job.', 'flash_error');
            }
        } 
	}

    /**
    * Before POST, sets request data to form
    *
    * After POST, flash error or flash success and redirect to index
    *
    * @param integer $id of the database job
    * @return array of cron data
    */
	public function admin_edit($id = null)
	{
      	$this->Cron->id = $id;

		if (!empty($this->request->data))
		{        	
	        if ($this->Cron->save($this->request->data))
	        {
	            $this->Session->setFlash('Your cron job has been updated.', 'flash_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Unable to update your cron job.', 'flash_error');
	        }
	    }

	    $this->request->data = $this->Cron->read();
	}

    /**
     * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
     *
     * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
     *
     * @param integer $id of the database job, redirect to index if no permissions
     * @param string $title of this job, used for flash message
     * @param boolean $permanent
     * @internal $permanent not NULL, this means the item is in the trash so deletion will now be permanent
     * @return redirect
     */
	public function admin_delete($id = null, $title = null, $permanent = null)
	{
	    $this->Cron->id = $id;

	    if (!empty($permanent))
	    {
	    	$delete = $this->Cron->delete($id);
	    } else {
	    	$delete = $this->Cron->saveField('deleted_time', $this->Cron->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash('The cron job `'.$title.'` has been deleted.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The cron job `'.$title.'` has NOT been deleted.', 'flash_error');
	    }
	    
	    if (!empty($permanent))
	    {
	    	$count = $this->Cron->find('count', array(
	    		'conditions' => array(
	    			'Cron.deleted_time !=' => '0000-00-00 00:00:00'
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
    * @param integer $id of database job, redirect if no permissions
    * @param string $title of this job, used for flash message
    * @return redirect
    */
	public function admin_restore($id = null, $title = null)
	{
	    $this->Cron->id = $id;

	    if ($this->Cron->saveField('deleted_time', '0000-00-00 00:00:00'))
	    {
	        $this->Session->setFlash('The cron job `'.$title.'` has been restored.', 'flash_success');
	        $this->redirect(array('action' => 'index'));
	    } else {
	    	$this->Session->setFlash('The cron job `'.$title.'` has NOT been restored.', 'flash_error');
	        $this->redirect(array('action' => 'index'));
	    }
	}
}