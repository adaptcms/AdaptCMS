<?php

class PermissionsInstall
{    
    private $member_data = array(
      array(
          'controller' => 'polls',
          'action' => 'vote',
          'status' => 1
      ),
      array(
          'controller' => 'polls',
          'action' => 'ajax_results',
          'related' => '[{"action":["vote"]}]',
          'status' => 1
      ),
      array(
          'controller' => 'polls',
          'action' => 'ajax_view_poll',
          'related' => '[{"action":["vote"]}]',
          'status' => 1
      )
    );
    
    private $admin_data = array(
      array(
          'controller' => 'polls',
          'action' => 'admin_index',
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]'
      ),
      array(
          'controller' => 'polls',
          'action' => 'admin_add'
      ),
      array(
          'controller' => 'polls',
          'action' => 'admin_edit'
      ),
      array(
          'controller' => 'polls',
          'action' => 'admin_delete'
      ),
      array(
          'controller' => 'polls',
          'action' => 'admin_restore'
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
                
                if ($role['Role']['defaults'] == 'default-guest')
                {
                    foreach($this->member_data as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'polls',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => 0,
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => 2,
                            'any' => 2
                        );
                    }
                }
                elseif ($role['Role']['defaults'] == 'default-member')
                {
                    foreach($this->member_data as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'polls',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => $permission['status'],
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => 2,
                            'any' => 2
                        );
                    }
                }
                else
                {
                    $permissions = array_merge($this->member_data, $this->admin_data);
                    
                    if ($role['Role']['defaults'] == 'default-admin')
                    {
                        foreach($permissions as $permission)
                        {
                            $data[]['Permission'] = array(
                                'module_id' => $module_id,
                                'role_id' => $role_id,
                                'plugin' => 'polls',
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
                        foreach($permissions as $permission)
                        {
                            $data[]['Permission'] = array(
                                'module_id' => $module_id,
                                'role_id' => $role_id,
                                'plugin' => 'polls',
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
        }
        
        return $data;
    }
}