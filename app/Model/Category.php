<?php

class Category extends AppModel {
	
	public $name = 'Category';
	public $hasMany = array(
        'Field', 
        'Article'
    );
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Category title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Category title has already been used'
			)
        )
    );

    public function getModuleData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Category.deleted_time' => '0000-00-00 00:00:00'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Category.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Category.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}