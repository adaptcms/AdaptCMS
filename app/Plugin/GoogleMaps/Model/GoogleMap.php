<?php
/**
 * Class GoogleMap
 */
class GoogleMap extends AppModel
{
    /**
     * Name of our Model, table will look like 'adaptcms_plugin_google_maps'
     */
    public $name = 'PluginGoogleMap';

    /**
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * Our validation rules, name of map.
     */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please name this map'
            )
        )
    );

    /**
     * @var array
     */
    public $map_types = array(
        'static' => 'Static',
        'basic' => 'Basic',
        'basic-route' => 'Basic with A-B Route',
        'basic-route-directions' => 'Basic with A-B Route and Directions'
    );

    public $map_defaults = array(
        'zoom' => 10,
        'color' => 'blue',
        'size' => 'normal',
        'address' => '1600 Pennsylvania Ave Nw, Washington',
        'latitude' => '38.897096',
        'longitude' => '-77.036545',
        'width' => 610,
        'height' => 300,
        'travel-type' => 'driving'
    );

    /**
     * @return array
     */
    public function getZoomNumbers()
    {
        $data = array();
        for($i = 0; $i <= 21; $i++)
        {
            $data[$i] = $i;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getMarkerSizes()
    {
        return array(
            'normal' => 'Normal',
            'small' => 'Small',
            'tiny' => 'Tiny'
        );
    }

    /**
     * @return array
     */
    public function getMarkerColors()
    {
        return array(
            'blue' => 'Blue',
            'green' => 'Green',
            'orange' => 'Orange',
            'purple' => 'Purple',
            'red' => 'Red',
            'yellow' => 'Yellow'
        );
    }

    /**
     * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
     * created the block. This is customizable so you can do a contain of related data if you wish.
     *
     * @param $data
     * @param $user_id
     * @return array
     */
    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'GoogleMap.deleted_time' => '0000-00-00 00:00:00'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'GoogleMap.' . $data['order_by'] . ' ' . $data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['GoogleMap.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    /**
     * @return boolean
     */
    public function beforeSave()
    {
        if (!empty($this->data['GoogleMap']['title']))
            $this->data['GoogleMap']['slug'] = $this->slug($this->data['GoogleMap']['title']);

        if (!empty($this->data['GoogleMap']['map_type']))
        {
            if ($this->data['GoogleMap']['map_type'] == 'basic' || $this->data['GoogleMap']['map_type'] == 'static')
            {
                unset($this->data['GoogleMap']['locations']['from'], $this->data['GoogleMap']['locations']['to'], $this->data['GoogleMap']['locations']['type']);
            }
        }

        if (!empty($this->data['GoogleMap']['locations']))
        {
            $locations = array();
            foreach($this->data['GoogleMap']['locations'] as $key => $location)
            {
                if (!empty($location['latitude']))
                    $locations[$key] = $location;
            }

            $this->data['GoogleMap']['locations'] = $locations;
        }

        if (!empty($this->data['GoogleMap']['old_title']) && $this->data['GoogleMap']['title'] != $this->data['GoogleMap']['old_title'])
        {
            $old_slug = $this->slug($this->data['GoogleMap']['old_title']);

            if (file_exists($this->_getPath($old_slug)))
            {
                rename(
                    $this->_getPath($old_slug),
                    $this->_getPath($this->data['GoogleMap']['slug'])
                );
            }
        }
        elseif(!empty($this->data['GoogleMap']['slug']))
        {
            $content = $this->_generateMap($this->data['GoogleMap']);

            $fh = fopen($this->_getPath($this->data['GoogleMap']['slug']), 'w');
            if ($fh)
            {
                fwrite($fh, $content);
                fclose($fh);
            }
        }

        if (!empty($this->data['GoogleMap']['options']))
            $this->data['GoogleMap']['options'] = json_encode($this->data['GoogleMap']['options']);

        if (!empty($this->data['GoogleMap']['locations']))
            $this->data['GoogleMap']['locations'] = json_encode($this->data['GoogleMap']['locations']);

        return true;
    }

    public function afterFind($results = array())
    {
        if (!empty($results))
        {
            foreach($results as $key => $result)
            {
                if (!empty($result['GoogleMap']['options']))
                    $results[$key]['GoogleMap']['options'] = json_decode($result['GoogleMap']['options'], true);
        
                if (!empty($result['GoogleMap']['locations']))
                    $results[$key]['GoogleMap']['locations'] = json_decode($result['GoogleMap']['locations'], true);
            }
        }

        return $results;
    }

    /**
     * @param array $data
     * @return string
     */
    public function _generateMap($data = array())
    {
        $view = new View();

        return $view->element('GoogleMaps.render_map', array('data' => $data));
    }

    /**
     * @param $slug
     * @return string
     */
    public function _getPath($slug)
    {
        return APP . DS . 'Plugin' . DS . 'GoogleMaps' . DS . 'View' . DS . 'Elements' . DS . 'Maps' . DS . $slug . '.ctp';
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $row = $this->findById($this->id);

        if (!empty($row['GoogleMap']['slug']))
        {
            $path = $this->_getPath($row['GoogleMap']['slug']);

            if (file_exists($path))
                unlink($path);
        }

        return true;
    }
}