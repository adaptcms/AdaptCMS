<?php

class PermissionValuesController extends AppController {
	public $name = 'PermissionValues';

	public function admin_add()
	{
		if ($this->PermissionValue->save($this->request->data)) {
            $this->Session->setFlash('Permissions have been saved.', 'flash_success');
            $this->redirect(array(
            	'controller' => 'roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['PermissionValue']['role_id']));
        } else {
            $this->Session->setFlash('Permissions could not be saved.', 'flash_error');
            $this->redirect(array(
            	'controller' => 'roles', 
            	'action' => 'admin_edit', 
            	$this->request->data['PermissionValue']['role_id']));
        }
	}

    public function admin_ajax_location_list()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        if ($this->request->data['Module']['get'] == "controllers") {
            $group = "PermissionValue.controller";

            $conditions = array(
                'PermissionValue.pageAction NOT LIKE' => '%admin%',
                'PermissionValue.role_id' => 1
            );
        } else {
            $conditions = array(
                'PermissionValue.pageAction NOT LIKE' => '%admin%',
                'PermissionValue.role_id' => 1,
                'OR' => array(
                    'PermissionValue.controller' => $this->request->data['Module']['controller'],
                    'PermissionValue.plugin' => $this->request->data['Module']['controller']
                )
            );
            $group = '';
        }

        $data = $this->PermissionValue->find('all', array(
            'conditions' => $conditions,
            'group' => $group
        ));

        foreach($data as $key => $row) {
            $new_data[$key]['controller_id'] = $row['PermissionValue']['controller'];
            $new_data[$key]['controller'] = Inflector::humanize($row['PermissionValue']['controller']);
            $new_data[$key]['action_id'] = $row['PermissionValue']['pageAction'];
            $new_data[$key]['action'] = Inflector::humanize($row['PermissionValue']['pageAction']);
            if (!empty($row['PermissionValue']['plugin'])) {
                $new_data[$key]['plugin_id'] = $row['PermissionValue']['plugin'];
                $new_data[$key]['plugin'] = Inflector::humanize($row['PermissionValue']['plugin']);
            }
        }

        return json_encode($new_data);
    }
}