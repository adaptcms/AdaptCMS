<?php

class PermissionsInstall
{
    private $guest_data = array(
      array(
          'controller' => 'tickets',
          'action' => 'index',
          'status' => 1,
          'related' => '[{"action":["view"]},{"action":["add"]}]'
      ),
      array(
          'controller' => 'tickets',
          'action' => 'view',
          'status' => 1,
          'related' => '[{"action":["add"]},{"action":["reply"]},{"action":["delete"]}]'
      ),
      array(
          'controller' => 'tickets',
          'action' => 'reply',
          'status' => 1
      ),
      array(
          'controller' => 'tickets',
          'action' => 'add',
          'status' => 1
      )  
    );

    private $member_data = array(
      array(
          'controller' => 'tickets',
          'action' => 'index',
          'status' => 1,
          'related' => '[{"action":["view"]},{"action":["add"]}]'
      ),
      array(
          'controller' => 'tickets',
          'action' => 'view',
          'status' => 1,
          'related' => '[{"action":["add"]},{"action":["reply"]},{"action":["delete"]}]',
          'own' => 1,
          'any' => 1
      ),
      array(
          'controller' => 'tickets',
          'action' => 'reply',
          'status' => 1,
          'own' => 1,
          'any' => 1
      )
    );
    
    private $admin_data = array(
      array(
          'controller' => 'tickets',
          'action' => 'delete'
      ),
      array(
          'controller' => 'ticket_categories',
          'action' => 'admin_index',
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]'
      ),
      array(
          'controller' => 'ticket_categories',
          'action' => 'admin_add'
      ),
      array(
          'controller' => 'ticket_categories',
          'action' => 'admin_edit'
      ),
      array(
          'controller' => 'ticket_categories',
          'action' => 'admin_delete'
      ),
      array(
          'controller' => 'ticket_categories',
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
                    foreach($this->guest_data as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'support_ticket',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => $permission['status'],
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => !empty($permission['own']) ? $permission['own'] : 2,
                            'any' => !empty($permission['any']) ? $permission['any'] : 2
                        );
                    }
                }
                elseif ($role['Role']['defaults'] == 'default-member')
                {
                    $permissions = array_merge($this->guest_data, $this->member_data);
                    
                    foreach($permissions as $permission)
                    {
                        $data[]['Permission'] = array(
                            'module_id' => $module_id,
                            'role_id' => $role_id,
                            'plugin' => 'support_ticket',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => $permission['status'],
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => !empty($permission['own']) ? $permission['own'] : 2,
                            'any' => !empty($permission['any']) ? $permission['any'] : 2
                        );
                    }
                }
                else
                {
                    $permissions = array_merge($this->guest_data, $this->member_data);
                    $permissions = array_merge($permissions, $this->admin_data);
                    
                    if ($role['Role']['defaults'] == 'default-admin')
                    {
                        foreach($permissions as $permission)
                        {
                            $data[]['Permission'] = array(
                              'module_id' => $module_id,
                              'role_id' => $role_id,
                              'plugin' => 'support_ticket',
                              'controller' => $permission['controller'],
                              'action' => $permission['action'],
                              'status' => 1,
                              'related' => !empty($permission['related']) ? $permission['related'] : '',
                              'own' => !empty($permission['own']) ? $permission['own'] : 1,
                              'any' => !empty($permission['any']) ? $permission['any'] : 1
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
                              'plugin' => 'support_ticket',
                              'controller' => $permission['controller'],
                              'action' => $permission['action'],
                              'status' => strstr($permission['action'], 'admin') ? 0 : 1,
                              'related' => !empty($permission['related']) ? $permission['related'] : '',
                              'own' => !empty($permission['own']) ? $permission['own'] : 0,
                              'any' => !empty($permission['any']) ? $permission['any'] : 0
                            );
                        }
                    }
                }
            }
        }
        
        return $data;
    }
}