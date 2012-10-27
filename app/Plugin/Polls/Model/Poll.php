<?php

class Poll extends PollsAppModel
{
	public $name = 'PluginPoll';
	public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
        )
	);
	public $hasMany = array(
		'PluginPollValue'
	);
	public $recursive = -1;

    public function getModuleData($data)
    {
        return $this->find('all', array(
            'conditions' => array(
                'Poll.deleted_time' => '0000-00-00 00:00:00'
            ),
            'limit' => $data['limit'],
            'contain' => array(
            	'PluginPollValue'
            )
        ));
    }
}