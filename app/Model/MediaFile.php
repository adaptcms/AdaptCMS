<?php
/**
 * Class MediaFile
 *
 * @property Media $Media
 * @property File $File
 */
class MediaFile extends AppModel
{
	/**
	 * @var array
	 */
	public $belongsTo = array(
		'Media' => array(
			'className'    => 'Media',
			'foreignKey'   => 'media_id',
			'conditions' => array(
				'Media.deleted_time' => '0000-00-00 00:00:00'
			)
		),
		'File' => array(
			'className' => 'File',
			'foreignKey' => 'file_id',
			'conditions' => array(
				'File.deleted_time' => '0000-00-00 00:00:00'
			),
			'type' => 'inner'
		)
	);
}