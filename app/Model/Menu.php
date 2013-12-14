<?php
/**
 * Class Menu
 *
 * @property User $User
 */
class Menu extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_menus'
    */
    public $name = 'Menu';

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
    * Our validate rules. The Menu title must not be empty and must be unique.
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Menu title cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Menu title has already been used'
            )
        )
    );

    /**
     * @var array
     */
    public $actsAs = array(
	    'Slug',
	    'Delete'
    );

    /**
    * Sets the slug
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        parent::beforeSave();

        if (!empty($this->data['Menu']['settings']))
        {
            $settings = $this->data['Menu']['settings'];
            $this->data['Menu']['settings'] = json_encode($settings);
        }

        if (!empty($this->data['Menu']['items']))
        {
            App::import('Model','Page');
            App::import('Model','Category');

            $this->Page = new Page();
            $this->Category = new Category();

            $items = array();
            $i = 0;
            foreach($this->data['Menu']['items'] as $key => $item)
            {
                $items[$i] = $item;

                if (!empty($item['page_id']) && empty($item['page_slug']))
                {
                    $page = $this->Page->findById($item['page_id']);

                    if (!empty($page))
                    {
                        $items[$i]['page_slug'] = $page['Page']['slug'];
                    }
                } elseif (!empty($item['category_id']) && empty($item['category_slug']))
                {
                    $category = $this->Category->findById($item['category_id']);

                    if (!empty($category))
                    {
                        $items[$i]['category_slug'] = $category['Category']['slug'];
                    }
                }

                $i++;
            }

            $this->data['Menu']['menu_items'] = json_encode( $items );

            if (!empty($this->data['Menu']['old_title']) && $this->data['Menu']['title'] != $this->data['Menu']['old_title'])
            {
                $old_slug = $this->slug($this->data['Menu']['old_title']);

                if (file_exists($this->_getPath($old_slug)))
                {
                    rename(
                        $this->_getPath($old_slug),
                        $this->_getPath($this->data['Menu']['slug'])
                    );
                }
            }
            elseif(!empty($this->data['Menu']['slug']) && !empty($settings))
            {
                $data = array_merge($this->data['Menu'], $settings);
                $data['items'] = $items;

                $content = $this->_generateMenuHtml($data);

                $fh = fopen($this->_getPath($this->data['Menu']['slug']), 'w');
                if ($fh)
                {
                    fwrite($fh, $content);
                    fclose($fh);
                    chmod($this->_getPath($this->data['Menu']['slug']), 0755);
                }
            }
        }

        return true;
    }

    /**
     * json_decodes json fields
     *
     * @param array $results
     * @param boolean $primary
     *
     * @return array
     */
    public function afterFind($results, $primary = false)
    {
        if (!empty($results))
        {
            foreach($results as $key => $result)
            {
                if (!empty($result['Menu']['menu_items']))
                    $results[$key]['Menu']['menu_items'] = json_decode($result['Menu']['menu_items'], true);

                if (!empty($result['Menu']['settings']))
                    $results[$key]['Menu']['settings'] = json_decode($result['Menu']['settings'], true);
            }
        }

        return $results;
    }

    /**
     * @param array $data
     * @return string
     */
    public function _generateMenuHtml($data = array())
    {
        $view = new AdaptcmsView();

        return $view->element('view_menu', array('data' => $data));
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getPath($slug)
    {
        return VIEW_PATH . 'Elements' . DS . 'Menus' . DS . $slug . '.ctp';
    }

    /**
    * Before Delete
    *
    * @param boolean $cascade
    *
    * @return bool
    */
    public function beforeDelete($cascade = true)
    {
        $row = $this->findById($this->id);

        if (!empty($row['Menu']['slug']))
        {
            $path = $this->_getPath($row['Menu']['slug']);

            if (file_exists($path))
                unlink($path);
        }

        return true;
    }

    public function getHeaderTypes()
    {
        return array(
            'h1' => 'Header1 <h1>',
            'h2' => 'Header2 <h2>',
            'h3' => 'Header3 <h3>',
            'h4' => 'Header4 <h4>',
            'strong' => 'Bold <strong>',
            'text' => 'Normal Text Style'
        );
    }

    public function getSeparatorTypes()
    {
        return array(
            'li' => 'li list <li>',
            'br' => 'Break Line <br />',
            'p' => 'Paragraph <p>'
        );
    }
}