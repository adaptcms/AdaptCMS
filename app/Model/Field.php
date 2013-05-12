<?php
class Field extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_fields'
    */
    public $name = "Field";

    /**
    * Every field belongs to a category and a User
    */
    public $belongsTo = array(
        'Category' => array(
            'className'    => 'Category',
            'foreignKey'   => 'category_id',
            'fields' => array(
                'slug',
                'title'
            )
        ),
        'Module' => array(
            'className' => 'Module',
            'foreignKey' => 'module_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
    * All article values are related to a field
    */
    public $hasMany = array(
        'ArticleValue' => array(
            'className' => 'ArticleValue',
            'foreignKey' => 'field_id'
        ),
        'ModuleValue' => array(
            'className' => 'ModuleValue',
            'foreignKey' => 'field_id'
        )
    );

    /**
    * Validation Rules
    */
    public $validate = array(
        'title' => array(
            'rule' => array(
                'notEmpty'
            )
        ),
        'field_type' => array(
            'rule' => array(
                'notEmpty'
            )
        ),
        'field_order' => array(
            'rule' => array(
                'notEmpty'
            )
        )
    );

    /**
    * Static field types, this will be a manageable customizable list in the future
    */
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

    /**
    * This afterFind will simply, automatically, decode the field options json field
    *
    * @return array of filtered field data
    */
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

            if (!empty($result['Field']['module_id']))
            {
                $results[$key]['Field']['category_id'] = 'module_' . $result['Field']['module_id'];
            }
        }

        return $results;
    }

    public function beforeSave()
    {
        if (!empty($this->data['Field']['title']))
        {
            if (empty($this->data['Field']['label']))
            {
                $this->data['Field']['label'] = $this->data['Field']['title'];
            }

            $this->data['Field']['title'] = $this->slug($this->data['Field']['title']);

            if (!empty($this->data['FieldData']))
            {
                $this->data['Field']['field_options'] = 
                    json_encode($this->data['FieldData']);
            }

            if (!empty($this->data['Field']['category_id']) && !is_numeric($this->data['Field']['category_id']))
            {
                $this->data['Field']['module_id'] = str_replace('module_', '', $this->data['Field']['category_id']);
                $this->data['Field']['category_id'] = 0;
            }
        }

        return true;
    }

    /**
    * Small function that retrieves fields for a specified category
    *
    * @param category_id
    * @param article_id
    * @return array of fields
    */
    public function getFields($category_id, $article_id = null)
    {
        $conditions = array();

        if (is_numeric($category_id))
        {
            $conditions['Field.category_id'] = $category_id;
            $type = 'ArticleValue';
            $type_id = 'article_id';
        }
        else
        {
            $id = $this->Module->findByModelTitle($category_id);

            $conditions['Field.module_id'] = $id['Module']['id'];
            $type = 'ModuleValue';
            $type_id = 'module_id';
        }

        $conditions = array(
            'conditions' => $conditions,
            'order' => array(
                'Field.field_order ASC'
            )
        );
        
        if (!empty($article_id))
        {
            $conditions['contain'] = array(
              $type => array(
                  'conditions' => array(
                      $type . '.' . $type_id => $article_id
                  ),
                  'File'
              )
            );
        }
        
        $fields = $this->find('all', $conditions);

        return $fields;
    }

    public function getData($module, $id)
    {
        $data = $this->getFields($module, $id);

        $results = array();
        foreach($data as $row)
        {
            if (!empty($row['ModuleValue'][0]))
            {
                $values = '';

                if (!empty($row['ModuleValue'][0]['File']['id']))
                {
                    $values = $row['ModuleValue'][0]['File']['dir'] . 
                        $row['ModuleValue'][0]['File']['filename'];
                }
                elseif (!empty($row['ModuleValue'][0]['data']))
                {
                    $values = $row['ModuleValue'][0]['data'];
                }

                $results['Data'][$row['Field']['title']] = $values;
            }
        }

        return array(
            'field_data' => $data,
            'data' => $results
        );
    }
}