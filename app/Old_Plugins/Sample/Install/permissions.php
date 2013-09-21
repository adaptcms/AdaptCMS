<?php

class PermissionsInstall
{
    private $guest_data = array(
        array(
            'controller' => 'sample',
            'action' => 'index',
            'status' => 1
        ),
        array(
            'controller' => 'sample',
            'action' => 'view',
            'status' => 1
        )
    );

    private $admin_data = array(
        array(
            'controller' => 'sample',
            'action' => 'admin_index',
            'related' => '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]'
        ),
        array(
            'controller' => 'sample',
            'action' => 'admin_add'
        ),
        array(
            'controller' => 'sample',
            'action' => 'admin_edit'
        ),
        array(
            'controller' => 'sample',
            'action' => 'admin_delete'
        ),
        array(
            'controller' => 'sample',
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
            $this->admin_data = array_merge($this->admin_data, $this->guest_data);
            
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
                            'plugin' => 'sample',
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
                            'plugin' => 'sample',
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