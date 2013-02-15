<?php
class Field extends AppModel {

    public $name = "Field";
    public $belongsTo = array(
        'Category' => array(
            'className'    => 'Category',
            'foreignKey'   => 'category_id',
            'fields' => 'slug'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    public $hasMany = array(
        'ArticleValue' => array(
            'className' => 'ArticleValue',
            'foreignKey' => 'field_id'
            )
    );
    public $validate = array(
        'title' => array(
            'rule' => array('notEmpty')
        ),
        'field_type' => array(
            'rule' => array('notEmpty')
        ),
        'field_order' => array(
            'rule' => array('notEmpty')
        )
    );

    public $field_types = array(
        'text' => 'Text Input', 
        'textarea' => 'Text Box', 
        'dropdown' => 'Dropdown Selector', 
        'multi-dropdown' => 'Dropdown Selector Multiple', 
        'radio' => 'Radio', 
        'check' => 'Checkbox', 
        'file' => 'File', 
        'img' => 'Image', 
        'url' => 'Website URL', 
        'num' => 'Number', 
        'email' => 'Email', 
        'date' => 'Date'
    );

    public function afterFind($results)
    {
        if (empty($results))
        {
            return;
        }

        foreach($results as $key => $result)
        {
            if (!empty($result['Field']['field_options']))
            {
                $results[$key]['Field']['field_options'] = json_decode($result['Field']['field_options'], true);
            }
        }

        return $results;
    }
}