<?php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {

	public $name = 'User';
	public $hasMany = array(
    'Article',
    'Comment'
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
            'rule' => 'notEmpty',
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
 
    public function beforeSave() {
        if (!empty($this->data['User']['password'])) {
          $this->data['User']['password'] = AuthComponent::password(
            $this->data['User']['password']
          );
        }
        return true;
    }

    public function getSecurityAnswers($data)
    {
      if (!empty($data['User']['security_answers'])) {
        $results = array();

        foreach(json_decode($data['User']['security_answers']) as $key => $row) {
          if (!empty($row->question) && !empty($row->answer)) {
            $results[$key]['question'] = str_replace("'","",$row->question);
            $results[$key]['answer'] = $row->answer;
          }
        }

        return $results;
      }
    }
}