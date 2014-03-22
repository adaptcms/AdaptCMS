<?php
/**
 * Class ForumCategory
 *
 * @property Forum $Forum
 */
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
            'foreignKey' => 'category_id',
	        'dependent' => true
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
     * @var array
     */
    public $actsAs = array(
	    'Slug',
	    'Delete'
    );

	/**
	 * Save Category Order
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function saveCategoryOrder($data = array())
	{
		$ids = explode(',', $data['ForumCategory']['order']);

		$data = array();
		$i = 0;
		foreach($ids as $key => $field)
		{
			if (!empty($field) && $field > 0)
			{
				$data[$i]['id'] = $field;
				$data[$i]['ord'] = $key;

				$i++;
			}
		}

		return $this->saveMany($data);
	}

	public function getIndexList()
	{
		$results = $this->find('all', array(
			'order' => 'ForumCategory.ord ASC'
		));

		if (!empty($results)) {
			foreach($results as $key => $row) {
				$forums = $this->Forum->find('all', array(
					'conditions' => array(
						'Forum.category_id' => $row['ForumCategory']['id']
					),
					'order' => 'Forum.ord ASC'
				));

				if (!empty($forums)) {
					foreach($forums as $i => $forum) {
						$results[$key]['Forum'][$i] = $forum['Forum'];
					}
				}
			}
		}

		return $results;
	}
}