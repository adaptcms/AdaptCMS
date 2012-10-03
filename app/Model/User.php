<?php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {

	public $name = 'User';
	public $hasMany = array('Article');
	public $belongsTo = array('Role');

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
            'rule' => 'notEmpty',
            'message' => 'Password cannot be empty'
          ),
          array(
            'rule' => array('minLength', 4),
            'message' => 'Must be at least 4 characters'
          ),
          array(
            'rule' => array('passCompare'),
            'message' => 'The passwords do not match'
          )
      )
    );

    public $recursive = -1;

    public function passCompare() {
        return ($this->data[$this->alias]['password'] === $this->data[$this->alias]['password_confirm']);
    }
 
    public function beforeSave() {
        if (!empty($this->data['User']['password'])) {
          $this->data['User']['password'] = AuthComponent::password(
            $this->data['User']['password']
          );
        }
        return true;
    }
	
}