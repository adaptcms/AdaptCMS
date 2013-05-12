<?php

class ModuleValue extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_module_values'
    */
    public $name = 'ModuleValue';

    /**
    * This allows us to use the 'Upload' behavior. So when new files are uploaded, this behavior
    * handles the uploading of the file automatically.
    */
    public $actsAs = array(
        'Upload'
    );

    /**
    * Several relationships. First is to a module, this is mandatory. Second is to a field, also mandatory.
    */
    public $belongsTo = array(
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
        if (!empty($this->data['ModuleValue']))
        {
            $row = $this->data['ModuleValue'];

            if (!empty($row['delete']) && $row['delete'] == 1 && !empty($row['id']))
            {
                // $this->data['ModuleValue']['data'] = $row['filename'];
                $this->delete($row['id']);
            }
            elseif (isset($row['data']) && is_array($row['data']))
            {
                if (isset($row['data']['error']) && $row['data']['error'] == 0 && !empty($row['data']['tmp_name']))
                {
                    // file upload here
                    $fileUpload = $this->uploadFile(
                        $row['data'], 
                        $row['field_id'], 
                        $row['module_id'], 
                        "ModuleValue", 
                        "File",
                        (!empty($row['module_name']) ? $row['module_name'] : 'module')
                    );
                    
                    if (!empty($row['filename']) && $row['filename'] == $row['data']['name'] && !empty($row['file_id']))
                    {
                        $fileUpload['File']['id'] = $row['file_id'];
                    }

                    if ($this->File->save($fileUpload['File']))
                    {
                        $this->data['ModuleValue']['file_id'] = $this->File->id;
                        $this->data['ModuleValue']['data'] = $fileUpload['ModuleValue']['data'];
                    }
                }
                elseif (isset($row['data']['error']) && $row['data']['error'] == 4 && empty($row['data']['tmp_name']))
                {
                    if (!empty($this->data['ModuleValue']['filename']))
                    {
                        $this->data['ModuleValue']['data'] = $this->data['ModuleValue']['filename'];
                    }
                    else
                    {
                        $this->data['ModuleValue']['data'] = '';
                    }
                }
                else
                {
                    $this->data['ModuleValue']['data'] = json_encode($row['data']);
                }
            }
        }

        return true;
    }
    
    /**
     * With no way around this, we will loop through ModuleValue data and any with
     * a deleted flag (only scenario is for file type, to unlink a file) will be unset
     * and removed from the database.
     * 
     * @param array $data
     * @return array
     */
    public function checkOnEdit($data)
    {
        if (!empty($data['ModuleValue']))
        {
            foreach($data['ModuleValue'] as $key => $row)
            {
                if (!empty($row['delete']) && !empty($row['id']))
                {
                    unset($data['ModuleValue'][$key]);
                    
                    $this->delete($row['id']);
                }
            }
        }
                
        return $data;
    }
}