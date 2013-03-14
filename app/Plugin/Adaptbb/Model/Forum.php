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
}