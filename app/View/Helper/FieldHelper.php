<?php
/**
 * Class FieldHelper
 *
 * @property HtmlHelper $Html
 * @property TextHelper $Text
 */
class FieldHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Field';

    /**
    * To show links, we require the HTML helper
    */
    public $helpers = array(
        'Html'
    );

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
                if ($value['Field']['field_type_slug'] == 'textarea') {
                    return $value['data'];
                }
            }
        }
        
        return false;
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
                if ($value['Field']['field_type_slug'] == 'img') {
                    return $value['File']['filename'];
                }
            }
        }
        
        return false;
    }

    /**
     * Depending on type of data, returns it
     * 
     * @param array $data
     * @return string
     */
    public function getData($data)
    {
        if (!empty($data['ArticleValue']))
        {
            $row = $data['ArticleValue'];
        }
        elseif (!empty($data['ModuleValue']))
        {
            $row = $data['ModuleValue'][0];
        }

        if (!empty($row))
        {
            $multi = array(
                'dropdown',
                'check',
                'multi-dropdown',
                'radio'
            );

            if ($data['Field']['field_type_slug'] == 'file')
            {
                if (!empty($row['File']['filename']))
                {
                    return $this->Html->link($row['File']['filename'],
                        '/' . $row['File']['dir'] . $row['data'],
                        array('target' => '_blank')
                    );
                }
            } elseif (in_array($data['Field']['field_type_slug'], $multi))
            {
                if (!empty($row['data']) && is_array($row['data']))
                {
                    return implode(', ', $row['data']);
                }
            } elseif ($data['Field']['field_type_slug'] == 'url')
            {
                return $this->Html->link(
                    $row['data'],
                    $row['data']
                );
            }
            else
            {
                return $row['data'];
            }
        }

        return false;
    }

    public function getFirstParagraph($data, $length = 150)
    {
        if (strstr($data, '<p>'))
        {
            $start = strpos($data, '<p>');
            $end = strpos($data, '</p>', $start);
            return substr($data, $start, $end - $start + 4);
        }
        else
        {
            $this->helpers[] = 'Text';
            $this->Text->truncate($data, $length);
        }

        return $data;
    }
}