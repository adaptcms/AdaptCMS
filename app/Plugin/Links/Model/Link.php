<?php

class Link extends LinksAppModel
{
	public $name = 'PluginLink';

	public $belongsTo = array(
    	'File' => array(
        	'className'    => 'File',
        	'foreignKey'   => 'file_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
	);

	public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Link.deleted_time' => '0000-00-00 00:00:00'
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

            $cond['order'] = 'Link.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Link.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    public function getSearchParams( $q )
    {
        return array(
            true
        );
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