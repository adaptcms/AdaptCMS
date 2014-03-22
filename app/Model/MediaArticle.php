<?php
/**
 * Class MediaArticle
 *
 * @property Media $Media
 * @property Article $Article
 */
class MediaArticle extends AppModel
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
		'Article' => array(
			'className' => 'Article',
			'foreignKey' => 'article_id',
			'conditions' => array(
				'Article.deleted_time' => '0000-00-00 00:00:00'
			)
		)
	);
}