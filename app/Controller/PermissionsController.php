<?php
App::uses('AppController', 'Controller');

/**
 * Class PermissionsController
 */
class PermissionsController extends AppController
{
    /**
    * Name of the Controller, 'Permissions'
    */
	public $name = 'Permissions';

    /**
    * On POST, returns error flash or success flash and redirect back to roles
    * edit page on success
    *
    * @return redirect
    */
	public function admin_add()
	{
        if (!empty($this->request->data))
        {
    		if ($this->Permission->save($this->request->data))
            {
                $this->Session->setFlash('Permissions have been saved.', 'flash_success');
                $this->redirect(array(
                	'controller' => 'roles', 
                	'action' => 'admin_edit', 
                	$this->request->data['Permission']['role_id']
                ));
            } else {
                $this->Session->setFlash('Permissions could not be saved.', 'flash_error');
                $this->redirect(array(
                	'controller' => 'roles', 
                	'action' => 'admin_edit', 
                	$this->request->data['Permission']['role_id']
                ));
            }
	    }
    }

    /**
    * Returns a list of locations based on permissions
    *
    * @return json_encode associative array
    */
    public function admin_ajax_location_list()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        if ($this->request->data['Block']['get'] == "controllers")
        {
            $group = "Permission.controller";

            $conditions = array(
                'Permission.controller !=' => 'install',
                'Permission.action NOT LIKE' => '%admin%',
                'Permission.role_id' => 1
            );
        } else {
            $conditions = array(
                'Permission.controller !=' => 'install',
                // 'Permission.action NOT LIKE' => '%admin%',
                'Permission.role_id' => 1,
                'OR' => array(
                    'Permission.controller' => $this->request->data['Block']['controller'],
                    'Permission.plugin' => $this->request->data['Block']['controller']
                )
            );
            $group = '';
        }

        $data = $this->Permission->find('all', array(
            'conditions' => $conditions,
            'group' => $group
        ));

        foreach($data as $key => $row)
        {
            $new_data[$key]['controller'] = Inflector::humanize($row['Permission']['controller']);
            $new_data[$key]['controller_id'] = $new_data[$key]['controller'];
            $new_data[$key]['action_id'] = $row['Permission']['action'];
            $new_data[$key]['action'] = Inflector::humanize($row['Permission']['action']);
            if (!empty($row['Permission']['plugin'])) {
                $new_data[$key]['plugin'] = Inflector::humanize($row['Permission']['plugin']);
                $new_data[$key]['plugin_id'] = $new_data[$key]['plugin'];
            }
        }

        return json_encode($new_data);
    }
}