<?php
App::uses('CakeEventListener', 'Event');

/**
 * Class ArticleRatingsEventListener
 *
 * @property ArticleRating $ArticleRating
 * @property Permission $Permission
 */
class ArticleRatingsEventListener implements CakeEventListener
{
	protected $ArticleRating;

	/**
	 * Implemented Events
	 *
	 * @return array
	 */
	public function implementedEvents() {
		return array(
			'Model.Article.afterFind' => 'getRatings',
		);
	}

	/**
	 * Get Ratings
	 *
	 * @param $event
	 * @return array
	 */
	public function getRatings($event)
	{
		$this->ArticleRating = ClassRegistry::init('ArticleRatings.ArticleRating');

		$role = Configure::read('User.role');
		$permission = Configure::read('User.permission.article_ratings');

		if (empty($permission) && !empty($role)) {
			$this->Permission = ClassRegistry::init('Permission');
			$permission = $this->Permission->find('first', array(
				'conditions' => array(
					'Permission.role_id' => $role,
					'Permission.action' => 'rate',
					'Permission.controller' => 'article_ratings',
					'Permission.plugin' => 'article_ratings',
					'Permission.status' => 1
				)
			));
			Configure::write('User.permission.article_ratings', array('result' => $permission));
		} elseif(!empty($permission)) {
			$permission = $permission['result'];
		}

		$results = $event->data['results'];

		if (!empty($event->result['results'])) {
			$results = array_merge_recursive($results, $event->result['results']);
		}

		if (!empty($results)) {
			foreach($results as $key => $result) {
				if (!empty($result['Article']['id'])) {
					$calc = $this->ArticleRating->getCalculations($result['Article']['id']);

					$results[$key]['ArticleRating']['total'] = $calc['total'];
					$results[$key]['ArticleRating']['avg'] = $calc['avg'];
					$results[$key]['ArticleRating']['score'] = $calc['score'];
					$results[$key]['ArticleRating']['user'] = $this->ArticleRating->hasVoted(Configure::read('User.id'), $result['Article']['id'], true);
					$results[$key]['ArticleRating']['can_rate'] = (empty($permission) ? false : true);
				}
			}
		}

		return $results;
	}
}