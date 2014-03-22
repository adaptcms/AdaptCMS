<?php
/**
 * Class ArticleValue
 *
 * @property Field $Field
 * @property File $File
 */
class ArticleValue extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_article_values'
    */
    public $name = 'ArticleValue';

    /**
    * This allows us to use the 'Upload' behavior. So when new files are uploaded, this behavior
    * handles the uploading of the file automatically.
    */
    public $actsAs = array(
        'Upload'
    );
        
    /**
    * Several relationships. First is to an article, this is mandatory. Second is to a field, also mandatory.
    * The third one is to a file, this is only applicable if it is a file/image type field.
    */
    public $belongsTo = array(
        'Article' => array(
            'className'    => 'Article',
            'foreignKey'   => 'article_id'
        ),
        'Field' => array(
            'className' => 'Field',
            'foreignKey' => 'field_id'
        ),
        'File' => array(
            'className' => 'File',
            'foreignKey' => 'file_id'
        )
    );
    
    /**
    * The beforeSave manages file uploads/changes and json_encode type field data
    * 
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        if (!empty($this->data['ArticleValue']))
        {
            $row = $this->data['ArticleValue'];

	        $empty_value = false;
	        if (!empty($row['type']) && $row['type'] == 'img' && !isset($row['file_id']))
	        {
		        $this->data['ArticleValue']['file_id'] = '';
		        $empty_value = true;
		        $field = 'file_id';
	        }
            elseif (!empty($row['delete']) && $row['delete'] == 1 && !empty($row['id']))
            {
                $this->data['ArticleValue']['data'] = $row['filename'];
            }
            elseif (isset($row['data']) && is_array($row['data']))
            {
                if (isset($row['data']['error']) && $row['data']['error'] == 0 && !empty($row['data']['tmp_name']))
                {
                    // file upload here
                    $fileUpload = $this->uploadFile(
                        $row['data'], 
                        $row['field_id'], 
                        null, 
                        "ArticleValue", 
                        "File"
                    );
                    
                    if (!empty($row['filename']) && $row['filename'] == $row['data']['name'] && !empty($row['file_id']))
                    {
                        $fileUpload['File']['id'] = $row['file_id'];
                    }

                    if ($this->File->save($fileUpload['File']))
                    {
                        $this->data['ArticleValue']['file_id'] = $this->File->id;
                        $this->data['ArticleValue']['data'] = $fileUpload['ArticleValue']['data'];
                    }
                }
                elseif (isset($row['data']['error']) && $row['data']['error'] == 4 && empty($row['data']['tmp_name']))
                {
                    if (!empty($this->data['ArticleValue']['filename']))
                    {
                        $this->data['ArticleValue']['data'] = $this->data['ArticleValue']['filename'];
                    }
                    else
                    {
                        $this->data['ArticleValue']['data'] = '';

                        $empty_value = true;
                        $field = 'file_id';
                    }
                }
                else
                {
                    $this->data['ArticleValue']['data'] = json_encode($row['data']);
                }
            } elseif((empty($row['type']) || $row['type'] != 'img') && (empty($row['data']) || (isset($row['data']) && $row['data'] == '0') )) {
		        $empty_value = true;
		        $field = 'data';
	        }

	        if (!empty($row['required']) && !empty($empty_value)) {
		        $this->invalidate($field, 'This field is required');
		        return false;
	        }
        }

        return true;
    }
    
    /**
     * With no way around this, we will loop through ArticleValue data and any with
     * a deleted flag (only scenario is for file type, to unlink a file) will be unset
     * and removed from the database.
     * 
     * @param array $data
     * @return array
     */
    public function checkOnEdit($data)
    {
        if (!empty($data['ArticleValue']))
        {
            foreach($data['ArticleValue'] as $key => $row)
            {
                if (!empty($row['delete']) && !empty($row['id']))
                {
                    unset($data['ArticleValue'][$key]);
                    
                    $this->delete($row['id']);
                }
            }
        }
                
        return $data;
    }

	/**
	 * Get Fields
	 *
	 * @param $data
	 * @return array
	 */
	public function getFields($data)
	{
		if (!empty($data)) {
			foreach($data as $key => $row) {
				if (empty($row['Field'])) {
					$field = $this->Field->findById($row['field_id']);

					$data[$key]['Field'] = $field['Field'];
				}

				if (!empty($row['file_id'])) {
					$file = $this->File->findById($row['file_id']);

					$data[$key]['File'] = $file['File'];
				}

				if (!empty($row['data']) && !is_array($row['data'])) {
					$data[$key]['data'] = html_entity_decode($row['data']);
				}
			}
		}

		return $data;
	}

	/**
	 * After Find
	 * Attempts to json_decode json data automatically.
	 *
	 * @param mixed $results
	 * @return mixed
	 */
	public function afterFind($results)
	{
		if (!empty($results)) {
			foreach($results as $key => $row) {
				if (!empty($row['ArticleValue']['data'])) {
					$result = json_decode($row['ArticleValue']['data'], true);

					if (!empty($result))
						$results[$key]['ArticleValue']['data'] = $result;
				}
			}
		}

		return $results;
	}

	/**
	 * Article Add Adjust
	 *
	 * @param $fields
	 * @return mixed
	 */
	public function articleAddAdjust($fields)
	{
		if (!empty($fields)) {
			foreach($fields as $key => $field) {
				if (!empty($field['ArticleValue']['field_id'])) {
					unset($fields[$key]['ArticleValue']);

					$fields[$key]['ArticleValue'][0] = $field['ArticleValue'];
				}
			}
		}

		return $fields;
	}
}