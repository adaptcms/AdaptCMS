<?php
App::uses('Sanitize', 'Utility');
/**
 * Class ForumTopic
 *
 * @property User $User
 * @property ForumPost $ForumPost
 * @property Forum $Forum
 */
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
     * @var array
     */
    public $actsAs = array(
	    'Slug' => array(
	        'field' => 'subject'
	    ),
	    'Delete'
    );

    /**
    * Sets the slug
    *
    * @return boolean
    */
    public function beforeSave()
    {
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        if (!empty($this->data['ForumTopic']['content']))
            $this->data['ForumTopic']['content'] = stripslashes(str_replace('\n', '', $this->data['ForumTopic']['content']));

        if (!empty($this->data['ForumTopic']['subject']))
            $this->data['ForumTopic']['subject'] = stripslashes(str_replace('\n', '', $this->data['ForumTopic']['subject']));
        
        return true;
    }

    /**
    * Function that json decodes user settings
    *
    * @param array $results of topic data
    * @return array
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
    * @param array $data
    * @return array new data
    */
    public function getStats($data)
    {
        foreach($data as $key => $row)
        {
            $find = $this->ForumPost->find('first', array(
                'conditions' => array(
                    'ForumPost.topic_id' => $row['ForumTopic']['id']
                ),
                'contain' => array(
                    'User'
                )
            ));

            if (!empty($find))
            {
                $data[$key]['NewestPost'] = $find;
            }
        }

        return $data;
    }
}