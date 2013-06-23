<?php

class PermissionsInstall
{
    private $guest_data = array(
      array(
          'controller' => 'forums',
          'action' => 'index',
          'status' => 1,
          'related' => '[{"action":["profile"],"controller":["users"],"plugin":null}]'
      ),
      array(
          'controller' => 'forums',
          'action' => 'view',
          'status' => 1,
          'related' => '[{"action":["add"],"controller":["forum_topics"]},{"action":["view"],"controller":["forum_topics"]}]'
      ),
      array(
          'controller' => 'forum_topics',
          'action' => 'view',
          'status' => 1,
          'related' => '[{"action":["change_status"]},{"action":["edit"]},{"action":["delete"]},{"action":["ajax_edit"],"controller":["forum_posts"]},{"action":["delete"],"controller":["forum_posts"]},{"action":["ajax_post"],"controller":["forum_posts"]}]'
      )  
    );
    
    private $member_data = array(
      array(
          'controller' => 'forum_topics',
          'action' => 'add',
          'status' => 1
      ),
      array(
          'controller' => 'forum_topics',
          'action' => 'edit',
          'status' => 1,
          'related' => '[{"action":["change_status"]}]',
          'any' => 0,
          'own' => 1
      ),
      array(
          'controller' => 'forum_topics',
          'action' => 'delete',
          'status' => 1,
          'any' => 0,
          'own' => 1
      ),
      array(
          'controller' => 'forum_posts',
          'action' => 'ajax_post',
          'status' => 1
      ),
      array(
          'controller' => 'forum_posts',
          'action' => 'ajax_edit',
          'status' => 1,
          'any' => 0,
          'own' => 1
      ),
      array(
          'controller' => 'forum_posts',
          'action' => 'delete',
          'status' => 1,
          'any' => 0,
          'own' => 1
      )
    );
    
    private $admin_data = array(
      array(
          'controller' => 'forum_categories',
          'action' => 'admin_index',
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_index"],"controller":["forums"]},{"action":["profile"],"controller":["users"]}]'
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
          'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_index"],{"controller":["forum_categories"],"action":["admin_edit"]},"controller":["forum_categories"]},{"action":["profile"],"controller":["users"]}]'
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
      ),
      array(
          'controller' => 'forum_topics',
          'action' => 'change_status'
      )
    );

    /**
     * @param array $roles
     * @param int $module_id
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
                            'plugin' => 'adaptbb',
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
                              'plugin' => 'adaptbb',
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
                              'plugin' => 'adaptbb',
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