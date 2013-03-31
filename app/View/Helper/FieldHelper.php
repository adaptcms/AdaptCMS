<?php

class FieldHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Field';

    /**
     * Looks for an textarea field in an array, returns the content or false
     * 
     * @param array $data
     * @return string
     */
    public function getTextAreaData($data)
    {
        if (empty($data)) {
            return false;
        }

        if (!empty($data['ArticleValue'])) {
            foreach($data['ArticleValue'] as $value) {
                if ($value['Field']['field_type'] == 'textarea') {
                    return $value['data'];
                }
            }
        }
        
        return;
    }

    /**
     * Looks for an image field in an array, returns the file name or false
     * 
     * @param array $data
     * @return string
     */
    public function getImage($data)
    {
        if (empty($data)) {
            return false;
        }

        if (!empty($data['ArticleValue'])) {
            foreach($data['ArticleValue'] as $value) {
                if ($value['Field']['field_type'] == 'img') {
                    return $value['File']['filename'];
                }
            }
        }
        
        return;
    }
}