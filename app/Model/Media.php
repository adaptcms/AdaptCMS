<?php

class Media extends AppModel
{
	public $name = 'Media';
	
    public $hasAndBelongsToMany = array(
    	'File' => array(
    		'className' => 'File',
    		'joinTable' => 'media_files',
    		'unique' => 'keepExisting'
		)
    );
    public $validate = array(
    	'title' => array(
            'rule' => array(
            	'notEmpty'
            )
        )
    );

    public function getModuleData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Media.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'File'
            ),
            'limit' => $data['limit']
        );

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Media.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Media.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}