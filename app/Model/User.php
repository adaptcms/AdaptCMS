<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('Sanitize', 'Utility');

class User extends AppModel
{
    public $name = 'User';
    public $hasMany = array(
        'Article' => array(
            'dependent' => true
        ),
        'Comment' => array(
            'dependent' => true
        ),
        'Message' => array(
            'dependent' => true
        ),
        'Block',
        'Category',
        'Field',
        'FieldType',
        'Page',
        'File',
        'Media'
    );
    public $belongsTo = array(
        'Role'
    );

    public $validate = array(
        'username' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Username cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'This username is already taken'
            )
        ),
        'email' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Email cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'This email is already taken'
            )
        ),
        'password' => array(
            array(
                'rule' => 'requirePassword',
                'message' => 'Password cannot be empty'
            ),
            array(
                'rule' => array(
                    'minLength', 
                    4
                ),
                'message' => 'Must be at least 4 characters'
            ),
            array(
                'rule' => array(
                    'passCompare'
                ),
                'message' => 'The passwords do not match'
            )
        )
    );

    public function passCompare() {
        return ($this->data[$this->alias]['password'] === $this->data[$this->alias]['password_confirm']);
    }

    public function requirePassword()
    {
        if (empty($this->data[$this->alias]['id']) && empty($this->data[$this->alias]['password']))
        {
            return false;
        }

        return true;
    }
 
    public function beforeSave() {
        $path = WWW_ROOT . 'uploads' . DS . 'avatars' . DS;

        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        if (!empty($this->data['User']['settings']['avatar']) && 
            is_array($this->data['User']['settings']['avatar']))
        {
            if (!empty($this->data['User']['id']))
            {
                $id = $this->data['User']['id'];
            } else {
                $id = time();
            }

            $filename = $id . '_' . $this->data['User']['settings']['avatar']['name'];

            if (move_uploaded_file(
                    $this->data['User']['settings']['avatar']['tmp_name'], 
                    $path . $filename
                ))
            {
                $this->data['User']['settings']['avatar'] = $filename;
            }
            else
            {
                unset($this->data['User']['settings']['avatar']);
            }
        }

        if (!empty($this->data['User']['settings']['old_avatar']))
        {
            $file = $this->data['User']['settings']['old_avatar'];

            if (file_exists($path . $file))
            {
                unlink($path . $file);
            }
        }

        if (!empty($this->data['Security']))
        {
            $this->data['User']['security_answers'] = json_encode($this->data['Security']);
        }
        
        if (!empty($this->data['User']['settings']))
        {
            $this->data['User']['settings'] = json_encode(
                $this->data['User']['settings']
            );
        }

        if (!empty($this->data['User']['password'])) {
          $this->data['User']['password'] = AuthComponent::password(
            $this->data['User']['password']
          );
        }
        else
        {
            unset($this->data['User']['password']);
        }

        return true;
    }

    public function getSecurityAnswers($data)
    {
        $results = array();
        if (!empty($data['User']['security_answers'])) {
            foreach(json_decode(stripslashes($data['User']['security_answers']), true) as $key => $row)
            {
                if (!empty($row['question']) && !empty($row['answer'])) {
                    $results[$key]['question'] = str_replace("'","",$row['question']);
                    $results[$key]['answer'] = $row['answer'];
                }

                if ($key == 'activate_code')
                {
                    $results['activate_code'] = $row;
                }
                elseif ($key == 'activate_time')
                {
                    $results['activate_time'] = $row;
                }
            }
        }

        return $results;
    }

    public function getSecurityOptions($data)
    {
        $results = array();
        if (!empty($data['SettingValue']['data_options']))
        {
            foreach($data['SettingValue']['data_options'] as $row)
            {
                $results[$row] = $row;
            }
        }

        return $results;
    }

    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'User.deleted_time' => '0000-00-00 00:00:00',
                'User.status !=' => 0
            ),
            'contain' => array(
                'Role',
                'Article'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'User.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['User.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    /**
     * @param array $data
     * @param array $field
     * @return array
     */
    public function getModuleData($data = array(), $field = array())
    {
        if (empty($data) || empty($field))
        {
            return $data;
        }

        $view = new View();
        $view->autoRender = false;

        $user_data = array();
        foreach($data as $key => $row)
        {
            if (!empty($row['User']))
            {
                if (empty($user_data[$row['User']['id']]))
                {
                    $value = $this->Field->ModuleValue->getValue($field, $row['User']['id'], $view);

                    $user_data[$row['User']['id']] = $value;
                    $data[$key]['User']['Data'][$field['Field']['title']] = $value;
                }
                else
                {
                    $data[$key]['User']['Data'][$field['Field']['title']] = $user_data[$row['User']['id']];
                }
            }
        }

        return $data;
    }
}