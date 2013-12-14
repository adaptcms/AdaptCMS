<?php
/**
 * Class Field
 *
 * @property Module $Module
 * @property ModuleValue $ModuleValue
 * @property FieldType $FieldType
 * @property Category $Category
 *
 * @method findById(integer $id)
 */
class Field extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_fields'
    */
    public $name = "Field";

    /**
    * Every field belongs to a category, user, module and has a field type
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
        ),
        'FieldType' => array(
            'className' => 'FieldType',
            'foreignKey' => 'field_type_id'
        )
    );

    /**
    * All article/module values are related to a field
    */
    public $hasMany = array(
        'ArticleValue' => array(
            'className' => 'ArticleValue',
            'foreignKey' => 'field_id',
            'dependent' => true
        ),
        'ModuleValue' => array(
            'className' => 'ModuleValue',
            'foreignKey' => 'field_id',
            'dependent' => true
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
        'field_order' => array(
            'rule' => array(
                'notEmpty'
            )
        ),
	    'field_type_id' => array(
		    'rule' => array(
			    'notEmpty'
		    )
	    )
    );

    /**
     * @var array
     */
    public $actsAs = array(
        'Slug' => array(
            'slugField' => 'title'
        ),
        'Delete'
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
     * @param mixed $results
     * @param boolean $primary
     *
     * @return array of filtered field data
     */
    public function afterFind($results, $primary = false)
    {
        if (!empty($results))
        {
	        if (!empty($results['id']))
	        {
	            if (!empty($results['field_options']))
	                $results['field_options'] = json_decode($results['field_options'], true);

	            if (!empty($results['module_id']))
	                $results['category_id'] = 'module_' . $results['module_id'];
	        }
	        else
	        {
	            foreach($results as $key => $result)
	            {
	                if (!empty($result['Field']['field_options']))
	                    $results[$key]['Field']['field_options'] = json_decode($result['Field']['field_options'], true);

	                if (!empty($result['Field']['module_id']))
	                    $results[$key]['Field']['category_id'] = 'module_' . $result['Field']['module_id'];
	            }
	        }
        }

        return $results;
    }

    /**
    * Before Save
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        if (!empty($this->data['Field']['title']))
        {
            if (empty($this->data['Field']['label']))
                $this->data['Field']['label'] = $this->data['Field']['title'];

            if (!empty($this->data['FieldData']))
                $this->data['Field']['field_options'] = json_encode($this->data['FieldData']);

            if (!empty($this->data['Field']['category_id']) && !is_numeric($this->data['Field']['category_id']))
            {
                $this->data['Field']['module_id'] = str_replace('module_', '', $this->data['Field']['category_id']);
                $this->data['Field']['category_id'] = 0;
            }

            if (!empty($this->data['Field']['field_type_id']))
            {
                $field_type = $this->FieldType->findById($this->data['Field']['field_type_id']);

                if (!empty($field_type))
                    $this->data['Field']['field_type_slug'] = $field_type['FieldType']['slug'];
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

        $conditions['contain'][] = 'FieldType';

        $fields = $this->find('all', $conditions);

        return $fields;
    }

	/**
	 * Get Data
	 *
	 * @param $module
	 * @param $id
	 * @param array $fields
	 *
	 * @return array
	 */
	public function getData($module, $id, $fields = array())
    {
	    if (empty($fields))
	        $fields = $this->getFields($module, $id);

        $results = array();
        foreach($fields as $row)
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
            'field_data' => $fields,
            'data' => $results
        );
    }

	/**
	 * Get All Data
	 *
	 * @param string $module
	 * @param array $fields
	 * @param array $data
	 *
	 * @return array
	 */
	public function getAllModuleData($module, $fields = array(), $data = array())
	{
		if (!empty($fields) && !empty($data))
		{
			$fields_list = array();
			foreach($fields as $field)
			{
				$fields_list[$field['Field']['id']] = $field;
			}

			foreach($data as $key => $row)
			{
				if (!empty($row[$module]['id']))
				{
					$results = $this->ModuleValue->find('all', array(
						'conditions' => array(
							'ModuleValue.module_id' => $row[$module]['id']
						)
					));

					if (!empty($results))
					{
						$data[$key]['Data'] = $this->parseModuleData($results, $fields_list);
					}
				}

				if (!empty($row['children']))
				{
					foreach($row['children'] as $i => $level_2)
					{
						$val = $level_2[$module];

						$level_2_results = $this->ModuleValue->find('all', array(
							'conditions' => array(
								'ModuleValue.module_id' => $val['id']
							)
						));

						if (!empty($level_2_results))
						{
							$data[$key]['children'][$i]['Data'] = $this->parseModuleData($level_2_results, $fields_list);
						}

						if (!empty($level_2['children']))
						{
							foreach($level_2['children'] as $j => $level_3)
							{
								$var = $level_3[$module];

								$level_3_results = $this->ModuleValue->find('all', array(
									'conditions' => array(
										'ModuleValue.module_id' => $var['id']
									)
								));

								if (!empty($level_3_results))
								{
									$data[$key]['children'][$i]['children'][$j]['Data'] = $this->parseModuleData($level_3_results, $fields_list);
								}
							}
						}
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Parse Module Data
	 *
	 * @param array $data
	 * @param array $fields
	 *
	 * @return array
	 */
	public function parseModuleData($data = array(), $fields = array())
	{
		$view = new View();
		$view->autoRender = false;

		$data_path = VIEW_PATH . 'Elements' . DS . 'FieldTypesData' . DS;

		$value = array();
		if (!empty($data))
		{
			foreach($data as $row)
			{
				$field = $fields[$row['ModuleValue']['field_id']];
				$slug = $field['Field']['field_type_slug'];

				if (!empty($row['ModuleValue']['file_id']))
				{
					$row['ModuleValue'] = array_merge(
						$row['ModuleValue'],
						$this->ModuleValue->File->findById($row['ModuleValue']['file_id'])
					);
				}

				if (file_exists($data_path . $slug . '.ctp'))
				{
					$value[$field['Field']['title']] = $view->element('FieldTypesData/' . $slug, array('data' => $row['ModuleValue']));
				}
				else
				{
					$value[$field['Field']['title']] = $view->element('FieldTypesData/default', array('data' => $row['ModuleValue']));
				}
			}
		}

		return $value;
	}

	/**
	 * Save Field Order
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function saveFieldOrder($data = array())
	{
		$ids = explode(',', $data['Field']['order']);

		$data = array();
		$i = 0;
		foreach($ids as $key => $field)
		{
			if (!empty($field) && $field > 0)
			{
				$data[$i]['id'] = $field;
				$data[$i]['field_order'] = $key;

				$i++;
			}
		}

		return $this->saveMany($data);
	}
}