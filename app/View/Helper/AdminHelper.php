<?php

class AdminHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Admin';
    
    /**
     * List of helpers that these functions use
     * 
     * @var array 
     */
    public $helpers = array(
        'Html',
        'Session',
        'Time'
    );

    /**
     * Returns current datetime
     * 
     * @return datetime
     */
    public function datetime()
    {
        return $this->Time->format('Y-m-d H:i:s', time());
    }

    /**
     * Generates edit link for admin
     * 
     * @param integer $id
     * @param string $controller
     * @param string $title
     * @param string $action
     * @param string $param
     * @return string HTML Link with icon/text
     */
    public function edit($id, $controller = null, $title = null, $action = null, $param = null)
    {
        if (empty($title))
        {
            $title = '<i class="icon-pencil"></i> Edit';
        }

        return $this->Html->link($title, array(
                'controller' => (!empty($controller) ? $controller : $this->params->controller),
                'action' => (!empty($action) ? $action : 'edit'),
                $id,
                (!empty($param) ? $param : '')
            ),
            array(
                'escape' => false
            )
        );
    }

    /**
     * Generates delete link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $text
     * @param string $controller
     * @param string $action
     * @return string HTML Link with icon/text
     */
    public function delete($id, $title, $text, $controller = null, $action = null)
    {
        return $this->Html->link('<i class="icon-trash"></i> Delete', array(
                'action' => (!empty($action) ? $action : 'delete'),
                'controller' => (!empty($controller) ? $controller : $this->params->controller),
                $id, 
                $title
            ),
            array(
                'escape' => false, 
                'onclick' => 
                    "return confirm('Are you sure you want to delete this " . $text . "?')"
            )
        );
    }

    /**
     * Generates restore link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $controller
     * @return string HTML Link with icon/text
     */
    public function restore($id, $title, $controller = null)
    {
        return $this->Html->link('<i class="icon-share-alt"></i> Restore', array(
                'action' => 'restore', 
                'controller' => (!empty($controller) ? $controller : $this->params->controller),
                $id, 
                $title
            ),
            array(
                'escape' => false
            )
        );
    }

    /**
     * Generates delete link for admin
     * 
     * @param integer $id
     * @param string $title
     * @param string $text
     * @param string $controller
     * @return string HTML Link with icon/text
     */
    public function delete_perm($id, $title, $text, $controller = null)
    {
        return $this->Html->link('<i class="icon-trash"></i> Delete Forever', array(
                'action' => 'delete',
                'controller' => (!empty($controller) ? $controller : $this->params->controller), 
                $id, 
                $title, 
                1
            ),
            array(
                'escape' => false, 
                'onclick' => "return confirm('Are you sure you want to delete this " . $text . "? This is permanent.')"
            )
        );
    }

    /**
     * Generates view link for admin
     * 
     * @param integer $id
     * @param string $action
     * @param string $controller
     * @return string HTML Link with icon/text
     */
    public function view($id, $action = 'view', $controller = null)
    {
        return $this->Html->link('<i class="icon-picture"></i> View', array(
                'action' => $action,
                'admin' => false,
                'controller' => (!empty($controller) ? $controller : $this->params->controller),
                $id
            ),
            array(
                'escape' => false
            )
        );
    }

    /**
     * Checks session to see if user is logged in. Returns true or false.
     * 
     * @return boolean
     */
    public function isLoggedIn()
    {
        if ($this->Session->check('Auth.User.id'))
        {
            return true;
        } 
        else
        {
            return false;
        }
    }

    /**
     * If user is logged in and has timezone, uses that to calculate time. Otherwise returns it based on
     * server time.
     * 
     * @param integer $time
     * @param string $format
     * @return string
     */
    public function time($time, $format = 'F jS, Y h:i A')
    {
        if ($this->isLoggedIn())
        {
            $settings = json_decode(
                $this->Session->read('Auth.User.settings'),
                true
            );

            if (!empty($settings['time_zone']))
            {
                $timezone = $settings['time_zone'];
            }
        }

        if ($format == 'words')
        {
            return $this->Time->timeAgoInWords(
                $time,
                array(
                    'format' => 'F jS, Y',
                    'timezone' => (!empty($timezone) ? $timezone : null),
                    'end' => '+1 month' 
                )
            );
        } else {
            return $this->Time->format(
                $format, 
                $time,
                false,
                (!empty($timezone) ? $timezone : null)
            );
        }
    }

    /**
     * Convinience function that based on permissions array/user_id, checks to see if user
     * has access to item. Returns true or false.
     * 
     * @param array $permission
     * @param integer $user_id
     * @return boolean
     */
    public function hasPermission($permission = null, $user_id = null)
    {
        if (!empty($permission) && !empty($user_id) && $user_id == $this->Session->read('Auth.User.id') || 
            !empty($permission) && $permission['any'] > 0 ||
            !empty($permission) && $permission['action'] == 'admin_add' && $permission['any'] == 0 ||
            !empty($permission) && $permission['any'] == 2)
        {
            return true;
        } else {
            return false;
        }
    }
}