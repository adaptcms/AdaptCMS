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

		if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit')
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

		if (isset($this->request->named['trash']))
			$conditions['Cron.only_deleted'] = true;

		$this->Paginator->settings = array(
            'conditions' => $conditions
        );
        
		$this->request->data = $this->Paginator->paginate('Cron');
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
	        $this->Cron->create();

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
	public function admin_edit($id)
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
     * @return void
     */
	public function admin_delete($id, $title = null)
	{
	    $this->Cron->id = $id;

		$data = $this->Cron->findById($id);

		$permanent = $this->Cron->remove($data);

		$this->Session->setFlash('The cron job `'.$title.'` has been deleted.', 'flash_success');

		if ($permanent)
		{
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
    * @param integer $id of database job, redirect if no permissions
    * @param string $title of this job, used for flash message
    * @return void
    */
	public function admin_restore($id, $title = null)
	{
	    $this->Cron->id = $id;

	    if ($this->Cron->restore())
	    {
	        $this->Session->setFlash('The cron job `'.$title.'` has been restored.', 'flash_success');
	    } else {
	    	$this->Session->setFlash('The cron job `'.$title.'` has NOT been restored.', 'flash_error');
	    }

        $this->redirect(array('action' => 'index'));
	}

    /**
     * Admin Test
     * This method will call the runCron method in the appController and attempt to run this specific cron.
     * If it cannot find this ID, it returns false or if the method fails. It returns true otherwise.
     *
     * @param integer $id
     *
     * @return void
     */
    public function admin_test($id)
    {
        $test_cron = $this->runCron($id);

        if ($test_cron)
        {
            $this->Session->setFlash('The cron job has run successfully.', 'flash_success');
        }
        else
        {
            $this->Session->setFlash('The cron job did not run successfully.', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }
}