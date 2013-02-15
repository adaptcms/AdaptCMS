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
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    public $validate = array(
        'title' => array(
            'rule' => array(
                'notEmpty'
            )
        )
    );

    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Media.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'File'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

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

    public function beforeSave()
    {
        if (!empty($this->data['File']) && !empty($this->data['Files']))
        {
            $this->data['File'] = array_merge($this->data['File'], $this->data['Files']);
        } elseif (!empty($this->data['Files']))
        {
            $this->data['File'] = $this->data['Files'];
        }

        return true;
    }
}