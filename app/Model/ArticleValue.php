<?php

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
     * @return boolean
     */
    public function beforeSave()
    {
        if (!empty($this->data['ArticleValue']))
        {
            $row = $this->data['ArticleValue'];
            
            if (!empty($row['delete']) && $row['delete'] == 1 && !empty($row['id']))
            {
                $this->data['ArticleValue']['data'] = $row['filename'];
//                $this->delete($row['id']);
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
                        debug($fileUpload);
                        debug($this->data['ArticleValue']);
                    }
                }
                elseif (isset($row['data']['error']) && $row['data']['error'] == 4 && empty($row['data']['tmp_name']))
                {
                    $this->data['ModuleValue']['data'] = $this->data['ModuleValue']['filename'];
                }
                else
                {
                    $this->data['ArticleValue']['data'] = json_encode($row['data']);
                }
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
}