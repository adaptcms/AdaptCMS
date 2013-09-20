<?php
App::uses('Sanitize', 'Utility');
/**
 * Class ForumPost
 *
 * @property ForumTopic $ForumTopic
 */
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

	public $actsAs = array('Delete');

    /**
    * Cleans out user input, html is allowed per setting and removed in controller
    */
    public function beforeSave()
    {
        $this->data = Sanitize::clean($this->data, array(
            'encode' => false,
            'remove_html' => false
        ));

        if (!empty($this->data['ForumPost']['content']))
            $this->data['ForumPost']['content'] = stripslashes(str_replace('\n', '', $this->data['ForumPost']['content']));

        return true;
    }

    /**
    * Function that json decodes user settings
    *
    * @param array $results of post data
    * @return array
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

            $results[$key]['type'] = 'post';
        }

        return $results;
    }
}