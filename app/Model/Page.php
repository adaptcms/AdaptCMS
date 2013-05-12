<?php

class Page extends AppModel {
    /**
    * Name of our Model, table will look like 'adaptcms_pages'
    */
	public $name = "Page";

    /**
    * Our validate rules. The Page title must not be empty and must be unique.
    * Content must not be empty either.
    */
    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Page title cannot be empty'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Page title has already been used'
			)
        ),
    	'content' => array(
            'rule' => array(
            	'notEmpty'
            )
        )
    );

    /**
    * And every page belongs to a user.
    */
    public $belongsTo = array(
    	'User' => array(
    		'className' => 'User',
    		'foreignKey' => 'user_id'
    	)
    );

    /**
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['Page']['title']))
        {
            $this->data['Page']['title'] = strip_tags($this->data['Page']['title']);
            $this->data['Page']['slug'] = $this->slug($this->data['Page']['title']);

            if (empty($this->data['Page']['id']))
            {
                $fh = fopen(VIEW_PATH."Pages/" . $this->data['Page']['slug'] . ".ctp", 'w') or die("can't open file");
                fwrite($fh, $this->data['Page']['content']);
                fclose($fh);
            } else {
                $fh = fopen(VIEW_PATH."Pages/" . $this->data['Page']['slug'] . ".ctp", 'w') or die("can't open file");
                fwrite($fh, $this->data['Page']['content']);
                fclose($fh);

                if ($this->data['Page']['title'] != $this->data['Page']['old_title']) {
                    unlink(VIEW_PATH."Pages/" . $this->slug($this->data['Page']['old_title']) . ".ctp");
                }
            }
        }

        return true;
    }
}