<?php

class PermissionsInstall
{    
    private $admin_data = array(
      array(
          'controller' => 'google_analytics',
          'action' => 'admin_index'
      )
    );
    
    /**
     * 
     * 
     * @param type $roles
     * @param type $module_id
     * @return array
     */
    public function generate($roles = array(), $module_id = 0)
    {
        $data = array();
        
        if (!empty($roles))
        {
            foreach($roles as $role)
            {
                $role_id = $role['Role']['id'];
                    
                if ($role['Role']['defaults'] == 'default-admin')
                {
                    foreach($this->admin_data as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'google_analytics',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => 1,
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => 1,
                            'any' => 1
                        );
                    }
                }
                else
                {
                    foreach($this->admin_data as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'google_analytics',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => strstr($permission['action'], 'admin') ? 0 : 1,
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => 0,
                            'any' => 0
                        );
                    }
                }
            }
        }
        
        return $data;
    }
}