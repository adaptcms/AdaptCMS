<?php

class PermissionsInstall
{
    private $guest_data = array(
      array(
          'controller' => 'forums',
          'action' => 'index',
          'status' => 1
      ),
      array(
          'controller' => 'forums',
          'action' => 'view',
          'status' => 1
      ),
      array(
          'controller' => 'forum_topics',
          'action' => 'view',
          'status' => 1
      )  
    );
    
    private $member_data = array(
      array(
          'controller' => 'forum_topics',
          'action' => 'add',
          'status' => 1
      ),
      array(
          'controller' => 'forum_posts',
          'action' => 'ajax_post',
          'status' => 1
      )
    );
    
    private $admin_data = array(
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_index',
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_index"],"controller":["forums"]}]'
      ),
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_add'
      ),
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_edit'
      ),
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_delete'
      ),
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_restore'
      ),
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_ajax_order'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_index',
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_index"],"controller":["forum_categories"]}]'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_add'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_edit'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_delete'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_restore'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_ajax_forums'
      ),
      array(
          'controller' => 'forums',
          'action' => 'admin_ajax_order'
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
                            'plugin' => 'adaptbb',
                            'controller' => $permission['controller'],
                            'action' => $permission['action'],
                            'status' => $permission['status'],
                            'related' => !empty($permission['related']) ? $permission['related'] : '',
                            'own' => 2,
                            'any' => 2
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
                            'plugin' => 'adaptbb',
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
                    $permissions = array_merge($this->guest_data, $this->member_data);
                    $permissions = array_merge($permissions, $this->admin_data);
                    
                    if ($role['Role']['defaults'] == 'default-admin')
                    {
                        foreach($permissions as $permission)
                        {
                            $data[]['Permission'] = array(
                                'module_id' => $module_id,
                                'role_id' => $role_id,
                                'plugin' => 'adaptbb',
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
                                'plugin' => 'adaptbb',
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