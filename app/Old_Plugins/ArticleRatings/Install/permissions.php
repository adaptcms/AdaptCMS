<?php

class PermissionsInstall
{
    private $guest_data = array(
        array(
            'controller' => 'article_ratings',
            'action' => 'rate',
            'status' => 1
        )
    );

	/**
	 * Generate
	 *
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

                foreach($this->guest_data as $permission)
                {
                    $data[]['Permission'] = array(
                        'module_id' => $module_id,
                        'role_id' => $role_id,
                        'plugin' => 'article_ratings',
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

        return $data;
    }
}