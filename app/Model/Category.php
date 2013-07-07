<?php

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
            'conditions' => array(
                'Category.deleted_time' => '0000-00-00 00:00:00'
            )
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
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['Category']['title']))
            $this->data['Category']['slug'] = $this->slug($this->data['Category']['title']);

        return true;
    }

    /**
     * If a new category, creates article and category template.
     * If modified and new title, renames template files.
     *
     * @param $model
     * @param $created
     * @return true
     */
    public function afterSave($model, $created)
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
        return VIEW_PATH . 'Categories' . DS . $slug . '.ctp';
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getArticlesPath($slug)
    {
        return VIEW_PATH . 'Articles' . DS . $slug . '.ctp';
    }

    /**
     * @return bool
     */
    public function beforeDelete()
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
}