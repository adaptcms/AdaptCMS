<?php
/**
 * Class Category
 *
 * @property Field $Field
 * @property Article $Article
 * @property User $User
 */
class Category extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_categories'
    */
    public $name = 'Category';

    /**
    * Relationship to 'Field' and 'Article', with both models having many items with the same category
    */
    public $hasMany = array(
        'Field' => array(
            'dependent' => true
        ),
        'Article' => array(
            'dependent' => true
        )
    );

    /**
    * And every category belongs to a user. This is when a category is created.
    */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
    * Our validate rules. The Category title must not be empty and must be unique.
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Category title cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Category title has already been used'
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
     * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
     * created the block. This is customizable so you can do a contain of related data if you wish.
     *
     * @param $data
     * @param $user_id
     * @return array
     */
    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array()
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Category.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Category.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    /**
     * If a new category, creates article and category template.
     * If modified and new title, renames template files.
     *
     * @param $created
     * @return boolean
     */
    public function afterSave($created)
    {
        if (!empty($this->data['Category']['title']))
        {
            if (!empty($this->data['Category']['old_title']) && $this->data['Category']['title'] != $this->data['Category']['old_title'])
            {
                $old_slug = $this->slug($this->data['Category']['old_title']);

                rename(
                    $this->_getArticlesPath($old_slug),
                    $this->_getArticlesPath($this->data['Category']['slug'])
                );
                rename(
                    $this->_getCategoriesPath($old_slug),
                    $this->_getCategoriesPath($this->data['Category']['slug'])
                );
            }
            elseif (empty($this->data['Category']['old_title']))
            {
                copy(
                    $this->_getArticlesPath('view'),
                    $this->_getArticlesPath($this->data['Category']['slug'])
                );
                copy(
                    $this->_getCategoriesPath('view'),
                    $this->_getCategoriesPath($this->data['Category']['slug'])
                );
            }
        }

        return true;
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getCategoriesPath($slug)
    {
        return FRONTEND_VIEW_PATH . 'Categories' . DS . $slug . '.ctp';
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getArticlesPath($slug)
    {
        return FRONTEND_VIEW_PATH . 'Articles' . DS . $slug . '.ctp';
    }

    /**
    * Before Delete
    *
    * @param boolean $cascade
    *
    * @return bool
    */
    public function beforeDelete($cascade = true)
    {
        $row = $this->findById($this->id);

        if (!empty($row['Category']['slug']))
        {
            $categories_path = $this->_getCategoriesPath($row['Category']['slug']);

            if (file_exists($categories_path))
                unlink($categories_path);

            $articles_path = $this->_getArticlesPath($row['Category']['slug']);

            if (file_exists($articles_path))
                unlink($articles_path);
        }

        return true;
    }

	/**
	 * Before Save
	 *
	 * @param array $options
	 * @return bool
	 */
	public function beforeSave($options = array())
	{
		if (!empty($this->data['Category']['settings']))
			$this->data['Category']['settings'] = json_encode($this->data['Category']['settings']);

		return true;
	}

	public function afterFind($results, $primary = false)
	{
		if (!empty($results)) {
			foreach($results as $key => $result)
			{
				if (!empty($result['Category']['settings']))
					$results[$key]['Category']['settings'] = json_decode($result['Category']['settings'], true);
			}
		}

		return $results;
	}

	/**
	 * Get categories
	 *
	 * @param array $results
	 *
	 * @return array
	 */
	public function getCategories($results = array())
	{
		if (!empty($results))
		{
			$categories_list = array_unique(Set::extract('{n}.category_id', $results));
			$find = $this->find('all', array(
				'conditions' => array(
					'Category.id' => $categories_list
				)
			));

			$categories = array();
			foreach($find as $category)
			{
				$categories[$category['Category']['id']] = $category;
			}

			foreach($results as $key => $row)
			{
				$results[$key]['Category'] = $categories[$row['category_id']]['Category'];
			}
		}

		return $results;
	}

	/**
	 * Has Category Permission Access
	 *
	 * @param $role
	 * @param $action
	 * @param $category
	 * @param null $user_id
	 * @param null $article_user_id
	 * @return bool
	 */
	public function hasPermissionAccess($role, $action, $category, $user_id = null, $article_user_id = null)
	{
		if (empty($category['Category']['settings']) || !isset($category['Category']['settings']['permissions'][$role][$action]['any'])) {
			return true;
		} elseif (!empty($user_id) && !empty($article_user_id) && $user_id == $article_user_id && isset($category['Category']['settings']['permissions'][$role][$action]['own']) &&
			$category['Category']['settings']['permissions'][$role][$action]['own'] != 2 && $category['Category']['settings']['permissions'][$role][$action]['any'] != 1) {
			return $category['Category']['settings']['permissions'][$role][$action]['own'];
		} elseif ($category['Category']['settings']['permissions'][$role][$action]['any'] == 2 || !empty($category['Category']['settings']['permissions'][$role][$action]['own']) &&
			$category['Category']['settings']['permissions'][$role][$action]['own'] == 2) {
			return false;
		} else {
			return $category['Category']['settings']['permissions'][$role][$action]['any'];
		}
	}

	public function getPermissionAccess($role, $action, $category)
	{
		if (empty($category['Category']['settings']) || !isset($category['Category']['settings']['permissions'][$role][$action]['any'])) {
			return true;
		} elseif ($category['Category']['settings']['permissions'][$role][$action]['any'] == 0 && !empty($category['Category']['settings']['permissions'][$role][$action]['own'])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Has Any Category Permission Access
	 *
	 * @param $role
	 * @param $action
	 * @param $category
	 * @return bool
	 */
	public function hasAnyPermissionAccess($role, $action, $category)
	{
		if (empty($category['Category']['settings']) || !isset($category['Category']['settings']['permissions'][$role][$action])) {
			return true;
		} elseif (isset($category['Category']['settings']['permissions'][$role][$action]['own']) && $category['Category']['settings']['permissions'][$role][$action]['own'] == 0 &&
			isset($category['Category']['settings']['permissions'][$role][$action]['any']) && $category['Category']['settings']['permissions'][$role][$action]['any'] == 0) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get List Conditions
	 *
	 * @param $conditions
	 * @param $categories
	 * @param $role
	 * @param $action
	 * @param null $user_id
	 * @return mixed
	 */
	public function getListConditions($conditions, $categories, $role, $action, $user_id = null)
	{
		if (!empty($categories)) {
			$conditions['OR'] = array();

			foreach($categories as $category) {
				$settings = $category['Category']['settings'];

				if ((!empty($settings['permissions'][$role][$action]['own']) && empty($settings['permissions'][$role][$action]['any']) && !empty($user_id)) || isset($conditions['User.id'])) {
					$conditions['OR'][]['AND'] = array(
						'Article.category_id' => $category['Category']['id'],
						'Article.user_id' => $user_id
					);

					if (isset($conditions['User.id']))
						unset($conditions['User.id']);
				} elseif ((empty($settings) || !empty($settings['permissions'][$role][$action]['any'])) && !isset($conditions['User.id'])) {
					$conditions['OR'][]['Article.category_id'] = $category['Category']['id'];
				}
			}
		}

		return $conditions;
	}
}