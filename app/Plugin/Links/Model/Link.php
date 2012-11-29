<?php

class Link extends LinksAppModel
{
	public $name = 'PluginLink';

	public $belongsTo = array(
    	'File' => array(
        	'className'    => 'File',
        	'foreignKey'   => 'file_id'
        )
	);

	public function getModuleData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Link.deleted_time' => '0000-00-00 00:00:00'
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

            $cond['order'] = 'Link.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Link.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}