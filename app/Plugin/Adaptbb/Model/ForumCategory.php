<?php

class ForumCategory extends AdaptbbAppModel
{
    /**
    * Name of our Model
    */
	public $name = 'PluginForumCategory';

    /**
    * Incase there are numberous Forum Plugin scripts, we append the name of the plugin.
    *
    * Traditionally the table name would just be 'plugin_forum_categories'
    */
	public $useTable = 'plugin_adaptbb_forum_categories';

    /**
    * Relationship to 'User', having a many to one relationship.
    */
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
	);

    /**
    * There are many Forums (potentially) that use a certain category.
    */
    public $hasMany = array(
        'Forum' => array(
            'className' => 'Adaptbb.Forum',
            'foreignKey' => 'category_id'
        )
    );

    /**
    * Our validate rules. The Category title must not be empty and must be unique.
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Forum Category title cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Forum Category title has already been used'
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
        if (!empty($this->data['ForumCategory']['title']))
        {
            $this->data['ForumCategory']['slug'] = $this->slug($this->data['ForumCategory']['title']);
        }
        
        return true;
    }
}