<?php

class Media extends AppModel
{
	/**
	 * Name of our Model, table will look like 'adaptcms_media'
	 */
	public $name = 'Media';

	/**
	 * Media libraries may have multiple files related to it. Setting unique to 'keepExisting' means that if
	 * file #1 belongs to media #1 and then is added to media #2, cake will keep the first record
	 * and not delete/re-add it.
	 */
	public $hasAndBelongsToMany = array(
		'File' => array(
			'className' => 'File',
			'joinTable' => 'media_files',
			'unique' => 'keepExisting'
		)
	);

	/**
	 * All files belong to a user
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	/**
	 * Validation rules, title must have a value
	 */
	public $validate = array(
		'title' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'You must enter in a library title.'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'Media title already in use.'
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
	 * Get Block Data
	 *
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
			'conditions' => array(),
			'contain' => array(
				'File'
			)
		);

		if (!empty($data['limit'])) {
			$cond['limit'] = $data['limit'];
		}

		if (!empty($data['order_by'])) {
			if ($data['order_by'] == "rand") {
				$data['order_by'] = 'RAND()';
			}

			$cond['order'] = 'Media.' . $data['order_by'] . ' ' . $data['order_dir'];
		}

		if (!empty($data['data'])) {
			$cond['conditions']['Media.id'] = $data['data'];
		}

		return $this->find('all', $cond);
	}

	/**
	 * Before Save
	 * Formats data into correct format to save
	 *
	 * @param array $options
	 *
	 * @return boolean
	 */
	public function beforeSave($options = array())
	{
		if (!empty($this->data['File']) && !empty($this->data['Files'])) {
			$this->data['File'] = array_merge($this->data['File'], $this->data['Files']);
		} elseif (!empty($this->data['Files'])) {
			$this->data['File'] = $this->data['Files'];
		}

		if (!empty($this->data['Media']['title'])) {
			$this->data['Media']['title'] = strip_tags($this->data['Media']['title']);
		}

		return true;
	}

	/**
	 * Get Last File And Count
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getLastFileAndCount($data = array())
	{
		$prefix = ConnectionManager::enumConnectionObjects();

		foreach ($data as $key => $row) {
			$count = $this->query('SELECT COUNT(*) as count FROM ' . $prefix['default']['prefix'] . 'media_files WHERE media_id = ' . $row['Media']['id']);
			$data[$key]['File']['count'] = $count[0][0]['count'];

			$last_file = $this->find('first', array(
				'conditions' => array(
					'Media.id' => $row['Media']['id']
				),
				'contain' => array(
					'File' => array(
						'limit' => 1
					)
				)
			));

			if (!empty($last_file['File']))
				$data[$key]['File'] = array_merge($data[$key]['File'], $last_file['File'][0]);
		}

		return $data;
	}

	/**
	 * Get File Count
	 * Doing this because cakephp's support for hasAndBelongsToMany sucks
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getFileCount($data = array())
	{
		$prefix = ConnectionManager::enumConnectionObjects();

		if (!empty($data)) {
			foreach ($data as $key => $row) {
				$count = $this->query('SELECT COUNT(*) as count FROM ' . $prefix['default']['prefix'] . 'media_files WHERE media_id = ' . $row['Media']['id']);
				$data[$key]['Media']['file_count'] = $count[0][0]['count'];
			}
		}

		return $data;
	}
}