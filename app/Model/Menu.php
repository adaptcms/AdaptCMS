<?php
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
    * Sets the slug
    *
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['Menu']['title']))
            $this->data['Menu']['slug'] = $this->slug($this->data['Menu']['title']);

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
                }
            }
        }

        return true;
    }

    /**
     * json_decodes json fields
     *
     * @param array $results
     * @return array|mixed
     */
    public function afterFind($results = array())
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
        $view = new View();

        return $view->element('view_menu', array('data' => $data));
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getPath($slug)
    {
        return APP . DS . 'View' . DS . 'Elements' . DS . 'Menus' . DS . $slug . '.ctp';
    }

    /**
     * @return bool
     */
    public function beforeDelete()
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
}