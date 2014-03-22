<?php
App::uses('ModelBehavior', 'Model');
/**
 * Delete Behavior File
 *
 * PHP version 5
 *
 * @category App
 * @package  Behavior
 * @author   Charlie Page <charliepage88@gmail.com>
 * @license  Simplified BSD License (http://www.adaptcms.com/pages/license-info)
 * @link     http://www.adaptcms.com
 */
class DeleteBehavior extends ModelBehavior
{
	/**
	 * @var array
	 */
	public $_defaults = array(
		'cascade' => false
	);

    /**
     * @var string
     */
    public $name = 'Delete';

    /**
     * Setup
     *
     * @param Model $Model
     * @param array $settings
     *
     * @return void
     */
    public function setup(Model $Model, $settings = array())
    {
	    $this->_defaults[$Model->alias]['cascade'] = isset($settings['cascade']) ? $settings['cascade'] : $this->_defaults['cascade'];

        if (!$Model->hasField('deleted_time'))
        {
            $Model->Behaviors->disable($this->name);
        }
    }

	/**
	 * Before Find
	 *
	 * @param Model $Model
	 * @param array $queryData
	 *
	 * @return array
	 */
    public function beforeFind(Model $Model, $queryData)
    {
	    if (get_class($Model) == 'Comment' && !empty($queryData['fields'][0]) && $queryData['fields'][0] == '`Comment`.`lft`') {
		    return $queryData;
	    }

        return $this->updateQueryData($Model, $queryData);
    }

	/**
	 * Remove
	 *
	 * @param Model $Model
	 * @param array $data
	 * @param bool $saveModel
	 *
	 * @return boolean|array
	 */
    public function remove(Model $Model, $data = array(), $saveModel = true)
    {
        if (empty($data[$Model->alias]['deleted_time']) || $data[$Model->alias]['deleted_time'] == '0000-00-00 00:00:00')
        {
	        if (!$saveModel) {
		        $data[$Model->alias]['deleted_time'] = $Model->dateTime();

		        return $data[$Model->alias];
	        }
	        else
	        {
                $Model->saveField('deleted_time', $Model->dateTime());
	        }
            $permanent = false;

	        if ($this->_defaults[$Model->alias]['cascade'] && !empty($Model->hasMany)) {
		        foreach($Model->hasMany as $row) {
			        if (!empty($row['foreignKey']) && !empty($row['className']) && $row['dependent']) {
				        $class = $Model->$row['className'];

				        $class->updateAll(
							array(
								$row['className'] . '.deleted_time' => '"' . $Model->dateTime() . '"'
							),
							array(
								$row['className'] . '.' . $row['foreignKey'] => $Model->id
							)
						);
			        }
		        }
	        }
        }
        else
        {
	        try {
	            $Model->delete();
	            $permanent = true;
	        } catch(Exception $e) {
		        debug($e->getMessage());
	        }
        }

        return $permanent;
    }

	/**
	 * Restore
	 *
	 * @param Model $Model
	 *
	 * @return bool
	 */
	public function restore(Model $Model)
    {
	    if ($this->_defaults[$Model->alias]['cascade'] && !empty($Model->hasMany)) {
		    foreach($Model->hasMany as $row) {
			    if (!empty($row['foreignKey']) && !empty($row['className']) && $row['dependent']) {
				    $class = $Model->$row['className'];

				    $class->updateAll(
					    array(
						    $row['className'] . '.deleted_time' => '"0000-00-00 00:00:00"'
					    ),
					    array(
						    $row['className'] . '.' . $row['foreignKey'] => $Model->id
					    )
				    );
			    }
		    }
	    }

        return $Model->saveField('deleted_time', '0000-00-00 00:00:00');
    }

	/**
	 * Find By Id
	 *
	 * @param Model $Model
	 * @param $id
	 *
	 * @return array
	 */
	public function findById(Model $Model, $id)
	{
		$belongs = (!empty($Model->belongsTo) ? array_keys($Model->belongsTo) : array());

		$conditions = array(
			'conditions' => array(
				$Model->alias . '.id' => $id,
				$Model->alias . '.deleted' => true
			)
		);

		if (in_array('User', $belongs))
		{
			$conditions['contain'] = array('User');
		}

		return $Model->find('first', $conditions);
	}

	/**
	 * Update Query Data
	 *
	 * @param Model $Model
	 * @param $queryData
	 * @return mixed
	 */
	public function updateQueryData(Model $Model, $queryData)
	{
		if (isset($queryData['conditions'][$Model->alias . '.only_deleted'])) {
			unset($queryData['conditions'][$Model->alias . '.only_deleted']);
			$queryData['conditions'][$Model->alias . '.deleted_time !='] = '0000-00-00 00:00:00';
		}
		elseif (isset($queryData['conditions'][$Model->alias . '.deleted']))
		{
			unset($queryData['conditions'][$Model->alias . '.deleted']);
		}
		elseif (!isset($queryData['conditions'][$Model->alias . '.deleted_time !=']))
		{
			$queryData['conditions'][$Model->alias . '.deleted_time'] = '0000-00-00 00:00:00';
		}

		if (!isset($queryData['order']) || empty($queryData['order'][0]))
			$queryData['order'] = $Model->alias . '.created DESC';

		if (!empty($queryData['contain'])) {
			foreach($queryData['contain'] as $key => $row) {
				if (is_numeric($key) && is_string($row) && !empty($Model->$row->actsAs) && in_array('Delete', $Model->$row->actsAs)) {
					$queryData['contain'][$row]['conditions'][$row . '.deleted_time'] = '0000-00-00 00:00:00';
				} elseif (!is_numeric($key)) {
					if (isset($row['conditions'][$key . '.only_deleted'])) {
						unset($queryData['contain'][$key]['conditions'][$key . '.only_deleted']);
						$queryData['contain'][$key]['conditions'][$key . '.deleted_time !='] = '0000-00-00 00:00:00';
					} elseif (!isset($queryData['contain'][$key]['conditions'][$key . '.deleted_time !='])) {
						$queryData['contain'][$key]['conditions'][$key . '.deleted_time'] = '0000-00-00 00:00:00';
					}
				}
			}
		}

		return $queryData;
	}
}