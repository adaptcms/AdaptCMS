<?php

class Forum extends AdaptbbAppModel
{
    /**
    * Name of our Model
    */
	public $name = 'PluginForum';

    /**
    * Incase there are numberous Forum Plugin scripts, we append the name of the plugin.
    *
    * Traditionally the table name would just be 'plugin_forums'
    */
	public $useTable = 'plugin_adaptbb_forums';

    /**
    * Relationship to 'User' and 'ForumCategory', having a many to one relationship for both Models.
    */
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'ForumCategory' => array(
            'className' => 'Adaptbb.ForumCategory',
            'foreignKey' => 'category_id'
        )
	);

    /**
    * There are many Topics (potentially) that use a certain forum.
    */
    public $hasMany = array(
        'ForumTopic' => array(
            'className' => 'Adaptbb.ForumTopic',
            'foreignKey' => 'forum_id'
        )
    );

    /**
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['Forum']['title']))
        {
            $this->data['Forum']['slug'] = $this->slug($this->data['Forum']['title']);
        }

        return true;
    }

    /**
    * Gets amount of topics, posts, users and newest user for forum index
    *
    * @param data
    * @return data new data
    */
    public function getIndexStats($data)
    {
        $stats = array();

        $stats['posts'] = 0;
        $stats['topics'] = 0;

        $stats['users'] = $this->User->find('count', array(
            'conditions' => array(
                'User.deleted_time' => '0000-00-00 00:00:00',
                'User.status' => 1
            )
        ));

        $stats['newest_user'] = $this->User->find('first', array(
            'conditions' => array(
                'User.deleted_time' => '0000-00-00 00:00:00',
                'User.status' => 1
            ),
            'order' => 'User.created DESC'
        ));

        foreach($data as $key => $row)
        {
            if (!empty($row['Forum']))
            {
                foreach($row['Forum'] as $i => $forum)
                {
                    $stats['topics'] = $stats['topics'] + $forum['num_topics'];
                    $stats['posts'] = $stats['posts'] + $forum['num_posts'];

                    $find = $this->ForumTopic->ForumPost->find('first', array(
                        'conditions' => array(
                            'ForumTopic.forum_id' => $forum['id'],
                            'ForumTopic.status' => 1,
                            'ForumTopic.deleted_time' => '0000-00-00 00:00:00'
                        ),
                        'contain' => array(
                            'ForumTopic',
                            'User'
                        ),
                        'order' => 'ForumPost.created DESC'
                    ));

                    if (!empty($find))
                    {
                        $data[$key]['Forum'][$i]['NewestPost'] = $find;
                    }
                }
            }
        }

        $data['Stats'] = $stats;

        return $data;
    }
}