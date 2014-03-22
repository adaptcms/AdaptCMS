<?php
/**
 * Class ArticleRevision
 *
 * @property Article $Article
 */
class ArticleRevision extends AppModel
{
	/**
	 * Name of our Model, table will look like 'adaptcms_article_revisions'
	 */
	public $name = 'ArticleRevision';

	/**
	 * Several relationships. First is to an article, this is mandatory. The other is to a user, also mandatory.
	 */
	public $belongsTo = array(
		'Article' => array(
			'className'    => 'Article',
			'foreignKey'   => 'article_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	/**
	 * Get Types
	 *
	 * @return array
	 */
	public function getTypes()
	{
		return array(
			'revision' => 'Revision',
			'auto-save' => 'Auto-Save',
			'quick-save' => 'Quick Save'
		);
	}

	/**
	 * Restore Revision
	 *
	 * @param $id
	 * @param $data
	 * @return bool
	 */
	public function restore($id, $data)
	{
		$find = $this->findByActive(1);

		if (!empty($find)) {
			$this->id = $find['ArticleRevision']['id'];
			$this->saveField('active', 0);
		}

		$this->id = $id;
		$this->saveField('active', 1);

		$find = $this->findById($id);

		$new_data = json_decode($find['ArticleRevision']['data'], true);

		unset($new_data['Article']['publishing_date'], $new_data['Article']['publishing_time']);

		$this->saveAssociated($this->Article->ArticleValue->checkOnEdit($new_data));

		$orig = $data;

		$data['Article'] = $new_data['Article'];
		$data['Article']['related_articles'] = $orig['Article']['related_articles'];
		$data['Article']['publish_time'] = $orig['Article']['publish_time'];
		$data['Article']['last_saved'] = $this->getLastSavedDate($this->dateTime());

		$data['ArticleValue'] = $new_data['ArticleValue'];

		return $data;
	}

	/**
	 * Save Revision
	 *
	 * @param array $data
	 * @param $user_id
	 * @param string $type
	 * @return void
	 */
	public function saveRevision($data = array(), $user_id, $type = 'revision')
	{
		$article_id = $data['Article']['id'];
		$find = $this->findByArticleId($article_id);

		if (isset($data['key']))
			unset($data['key']);

		if (isset($data['_Token']))
			unset($data['_Token']);

		if (empty($find) && !empty($data['Article']['old_data']))
			$this->createRevision($data, $user_id, $type);

		if ($type == 'quick-save' || $type == 'auto-save') {
			$find = $this->find('first', array(
				'conditions' => array(
					'ArticleRevision.type' => $type,
					'ArticleRevision.article_id' => $article_id
				)
			));

			if (empty($find)) {
				$this->createRevision($data, $user_id, $type, 1);
			} else {
				$this->updateRevision($data, $find, $user_id, 1);
			}
		} else {
			$count = $this->find('count', array(
				'conditions' => array(
					'ArticleRevision.type' => $type,
					'ArticleRevision.article_id' => $article_id
				)
			));

			if ($count < 10) {
				$this->createRevision($data, $user_id, $type, 1);
			} else {
				$oldest = $this->find('first', array(
					'conditions' => array(
						'ArticleRevision.type' => $type,
						'ArticleRevision.article_id' => $article_id
					),
					'order' => 'ArticleRevision.created ASC'
				));

				$this->updateRevision($data, $oldest, $user_id, 1);
			}
		}
	}

	/**
	 * Create Revision
	 *
	 * @param array $data
	 * @param $user_id
	 * @param string $type
	 * @param int $active
	 * @return mixed
	 */
	public function createRevision($data = array(), $user_id, $type = 'revision', $active = 0)
	{
		if ($active == 1) {
			$find = $this->findByActive(1);

			if (!empty($find)) {
				$this->id = $find['ArticleRevision']['id'];
				$this->saveField('active', 0);
			}
		}

		$this->create();

		$revision = array();

		if (!empty($data['Article']['old_data']))
			unset($data['Article']['old_data']);

		$revision['ArticleRevision']['user_id'] = $user_id;
		$revision['ArticleRevision']['article_id'] = $data['Article']['id'];
		$revision['ArticleRevision']['type'] = $type;
		$revision['ArticleRevision']['active'] = $active;
		$revision['ArticleRevision']['data'] = json_encode($data);

		return $this->save($revision);
	}

	/**
	 * Update Revision
	 *
	 * @param $data
	 * @param $revision
	 * @param $user_id
	 * @param int $active
	 * @return mixed
	 */
	public function updateRevision($data, $revision, $user_id, $active = 0)
	{
		if ($active == 1) {
			$find = $this->findByActive(1);

			if (!empty($find)) {
				$this->id = $find['ArticleRevision']['id'];
				$this->saveField('active', 0);
			}
		}

		$revision['ArticleRevision']['user_id'] = $user_id;
		$revision['ArticleRevision']['created'] = $this->dateTime();
		$revision['ArticleRevision']['active'] = $active;
		$revision['ArticleRevision']['data'] = json_encode($data);

		return $this->save($revision);
	}
}