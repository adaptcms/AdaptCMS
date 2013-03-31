<?php

class ForumPost extends AdaptbbAppModel
{
    /**
    * Name of our Model
    */
	public $name = 'PluginForumPost';

    /**
    * Incase there are numberous Forum Plugin scripts, we append the name of the plugin.
    *
    * Traditionally the table name would just be 'plugin_forum_posts'
    */
	public $useTable = 'plugin_adaptbb_forum_posts';

    /**
    * Relationship to 'User', having a many to one relationship and 'Forum'.
    */
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'ForumTopic' => array(
            'className' => 'Adaptbb.ForumTopic',
            'foreignKey' => 'topic_id'
        )
	);

    /**
    * Our validate rules. The post content must not be empty.
    */
    public $validate = array(
        'content' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Subject cannot be empty'
            )
        )
    );

    /**
    * Function that json decodes user settings
    *
    * @param results of post data
    * @return results
    */
    public function afterFind($results)
    {
        foreach($results as $key => $result)
        {
            if (!empty($result['User']))
            {
                $results[$key]['User']['settings'] = json_decode(
                    $result['User']['settings'], 
                    true
                );
            }
        }

        return $results;
    }
}