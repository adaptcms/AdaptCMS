<?php

class Module extends AppModel
{
	public $name = 'Module';

	public $belongsTo = array(
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id'
		),
		'Components' => array(
			'className' => 'Components',
			'foreignKey' => 'component_id'
		)
	);

    public $validate = array(
    	'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Module title cannot be empty.'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Module title is already in use.'
			)
        ),
        'component_id' => array(
        	array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select a Model'
        	)
        ),
        'location' => array(
        	array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select a location for this module'
        	)
        )
    );

    public function filterData($data)
    {
        $data['Module']['title'] = strtolower(Inflector::slug($data['Module']['title'], "-"));
        
        if ($data['Module']['location_type'] == "*") {
            $data['Module']['location'] = json_encode(array("*"));
        } else {
            foreach($data['LocationData'] as $row) {
                $location_data[] = str_replace('/', '|', $row);
            }

            $data['Module']['location'] = json_encode($location_data);
            unset($data['LocationData']);
        }

        if (!empty($data['Module']['data'])) {
            $data['Module']['settings']['data'] = $data['Module']['data'];
        }

        if (!empty($data['Module']['order_by'])) {
            $data['Module']['settings']['order_by'] = $data['Module']['order_by'];
        }

        if (!empty($data['Module']['order_dir'])) {
            $data['Module']['settings']['order_dir'] = $data['Module']['order_dir'];
        }

        if (!empty($data['Module']['settings'])) {
            $data['Module']['settings'] = json_encode($data['Module']['settings']);
        }

        if (strstr($data['Module']['model'], '.')) {
            $ex = explode('.', $data['Module']['model']);

            $get = $this->Components->find('first', array(
                'conditions' => array(
                    'Component.is_plugin' => 1,
                    'Component.title' => $ex[0],
                    'Component.model_title' => $ex[1]
                )
            ));
        } else {
            $get = $this->Components->findByModelTitle($data['Module']['model']);
        }

        $data['Module']['component_id'] = $get['Components']['id'];

        return $data;
    }

    public function preFilterdata($data)
    {
        if ($data['Components']['is_plugin'] == 1) {
            $data['Module']['model'] = 
                $data['Components']['title'] . '.' . $data['Components']['model_title'];
        } else {
            $data['Module']['model'] = $data['Components']['model_title'];
        }

        $type = json_decode($data['Module']['location']);

        if ($type[0] == "*") {
            $data['Module']['location_type'] = "*";
        } else {
            $data['Module']['location_type'] = "view";
        }
        
        $data['Module']['template'] = 
            $data['Template']['title'].' ('. $data['Template']['location'] . ')';

        if (!empty($data['Module']['settings'])) {
            $settings = json_decode($data['Module']['settings']);

            foreach($settings as $key => $row) {
                $data['Module'][$key] = $row;
            }
        }

        return $data;
    }
}