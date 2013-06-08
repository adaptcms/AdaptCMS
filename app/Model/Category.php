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
        'Field', 
        'Article'
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
    * @return associative array
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
    * @return true
    */
    public function afterSave($model, $created)
    {
        if (!empty($this->data['Category']['old_title']) && 
            $this->data['Category']['title'] != $this->data['Category']['old_title'])
        {
            $old_slug = $this->slug($this->data['Category']['old_title']);

            $old_article_file = VIEW_PATH . 'Articles' . DS . $old_slug . '.ctp';
            $new_article_file = VIEW_PATH . 'Articles' . DS . $this->data['Category']['slug'] . '.ctp';
            rename($old_article_file, $new_article_file);

            $old_category_file = VIEW_PATH . 'Categories' . DS . $old_slug . '.ctp';
            $new_category_file = VIEW_PATH . 'Categories' . DS . $this->data['Category']['slug'] . '.ctp';
            rename($old_category_file, $new_category_file);
        } elseif (empty($this->data['Category']['old_title']))
        {
            $old_article_file = VIEW_PATH . 'Articles' . DS . 'view.ctp';
            $new_article_file = VIEW_PATH . 'Articles' . DS . $this->data['Category']['slug'] . '.ctp';
            copy($old_article_file, $new_article_file);

            $old_category_file = VIEW_PATH . 'Categories' . DS . 'view.ctp';
            $new_category_file = VIEW_PATH . 'Categories' . DS . $this->data['Category']['slug'] . '.ctp';
            copy($old_category_file, $new_category_file);
        }

        return true;
    }
}