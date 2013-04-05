<?php

class ForumTopic extends AdaptbbAppModel
{
    /**
    * Name of our Model
    */
	public $name = 'PluginForumTopic';

    /**
    * Incase there are numberous Forum Plugin scripts, we append the name of the plugin.
    *
    * Traditionally the table name would just be 'plugin_forum_topics'
    */
	public $useTable = 'plugin_adaptbb_forum_topics';

    /**
    * Relationship to 'User', having a many to one relationship and 'Forum'.
    */
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Forum' => array(
            'className' => 'Adaptbb.Forum',
            'foreignKey' => 'forum_id'
        )
	);

    /**
    * There are many Posts (potentially) that use a certain topic.
    */
    public $hasMany = array(
        'ForumPost' => array(
            'className' => 'Adaptbb.ForumPost',
            'foreignKey' => 'topic_id'
        )
    );

    /**
    * Our validate rules. The Topic subject must not be empty as well as content.
    */
    public $validate = array(
        'subject' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Subject cannot be empty'
            )
        ),
        'content' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter in a topic message'
            )
        )
    );

    /**
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['ForumTopic']['subject']))
        {
            $this->data['ForumTopic']['slug'] = $this->slug($this->data['ForumTopic']['subject']);
        }
        
        return true;
    }

    /**
    * Function that json decodes user settings
    *
    * @param results of topic data
    * @return results
    */
    public function afterFind($results)
    {
        foreach($results as $key => $result)
        {
            if (!empty($result['User']) && isset($result['User']['settings']))
            {
                $results[$key]['User']['settings'] = json_decode(
                    $result['User']['settings'], 
                    true
                );
            }

            $results[$key]['type'] = 'topic';
        }

        return $results;
    }

    /**
    * Adds in newest post for a topic
    *
    *
    * @param data
    * @return data new data
    */
    public function getStats($data)
    {
        foreach($data as $key => $row)
        {
            $find = $this->ForumPost->find('first', array(
                'conditions' => array(
                    'ForumPost.topic_id' => $row['ForumTopic']['id'],
                    'ForumPost.deleted_time' => '0000-00-00 00:00:00'
                ),
                'contain' => array(
                    'User'
                ),
                'order' => 'ForumPost.created DESC'
            ));

            if (!empty($find))
            {
                $data[$key]['NewestPost'] = $find;
            }
        }

        return $data;
    }
}