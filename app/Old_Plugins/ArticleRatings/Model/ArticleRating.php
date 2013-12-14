<?php
App::uses('ArticleRatingsAppModel', 'ArticleRatings.Model');
/**
 * Class ArticleRating
 *
 * @property Article $Article
 * @property User $User
 */
class ArticleRating extends ArticleRatingsAppModel
{
	public $name = 'PluginArticleRating';

    /**
     * @var array
     */
    public $belongsTo = array(
	    'Article' => array(
		    'className'    => 'Article',
		    'foreignKey'   => 'article_id'
	    ),
	    'User' => array(
		    'className'    => 'User',
		    'foreignKey'   => 'user_id'
	    )
    );

    /**
     * Our validation rules
     */
    public $validate = array(
        'rating' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter in a rating'
            )
        )
    );

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
                'Article.deleted_time' => '0000-00-00 00:00:00'
            ),
	        'contain' => array(
		        'Article'
	        )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

	        $cond['fields'] = array('Article.*', 'AVG(ArticleRating.score) AS score', 'SUM(ArticleRating.score) as total', 'COUNT(ArticleRating.id) as count');
	        $cond['group'] = 'ArticleRating.article_id';
            $cond['order'] = $data['order_by'] . ' ' . $data['order_dir'];
        }

	    if (!empty($data['category_id']))
		    $cond['conditions']['Article.category_id'] = $data['category_id'];

        $results = $this->find('all', $cond);

	    if (!empty($results)) {
		    foreach($results as $key => $row) {
			    $avg = 0;
			    $score = 0;
			    $total = 0;
			    if (!empty($row[0]['total']) && !empty($row[0]['count'])) {
				    $total = $row[0]['count'];
				    $avg = round($row[0]['total'] / $total, 1);
				    $score = round(floor($avg * 100) / 100, 2);

				    unset($results[$key][0]);
			    }

			    $results[$key]['ArticleRating']['total'] = $total;
			    $results[$key]['ArticleRating']['avg'] = $avg;
			    $results[$key]['ArticleRating']['score'] = $score;
		    }
	    }

	    return $this->Article->getAllRelatedArticles($results);
    }

	/**
	 * Get Block Order By Options
	 *
	 * @return array
	 */
	public function getBlockOrderByOptions()
	{
		return array(
			'score' => 'Avg Rating',
			'total' => 'Total Rating',
			'count' => 'Number of Ratings'
		);
	}

	/**
	 * For block support, articles allow filtering by category. To enable this we call the view and pass a list of
	 * categories to this element and get the resulting code, passing it back to blocks. It's not proper MVC, but
	 * I don't know another way around it.
	 *
	 * @param data
	 * @return string containing HTML to display
	 */
	public function getBlockCustomOptions($data)
	{
		$view = new AdaptcmsView();
		$categories = $this->Article->Category->find('list');

		$data = $view->element('article_custom_options', array(
			'categories' => $categories,
			'id' => (!empty($data['category_id']) ? $data['category_id'] : '')
		));

		return $data;
	}

	/**
	 * Record Rating
	 *
	 * @param $user_id
	 * @param $article_id
	 * @param $score
	 * @return array
	 */
	public function recordRating($user_id, $article_id, $score)
	{
		$find = $this->hasVoted($user_id, $article_id);

		if (empty($find)) {
			$this->create();

			$data['ArticleRating']['article_id'] = $article_id;
			$data['ArticleRating']['user_id'] = $user_id;
			$data['ArticleRating']['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['ArticleRating']['score'] = $score;

			$this->save($data);
		} else {
			$find['ArticleRating']['score'] = $score;

			$this->save($find);
		}

		return $this->getCalculations($article_id, $score);
	}

	/**
	 * Has Voted
	 *
	 * @param $user_id
	 * @param $article_id
	 * @param bool $return_score
	 * @return array
	 */
	public function hasVoted($user_id, $article_id, $return_score = false)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		$conditions['conditions']['ArticleRating.article_id'] = $article_id;
		if (!empty($user_id)) {
			$conditions['conditions']['OR'] = array(
				'ArticleRating.user_id' => $user_id,
				'ArticleRating.ip' => $ip
			);
		} else {
			$conditions['conditions']['ArticleRating.ip'] = $ip;
		}

		if (!empty($return_score)) {
			$conditions['fields'] = 'ArticleRating.score';
		}

		$find = $this->find('first', $conditions);

		if (!empty($return_score)) {
			return (!empty($find['ArticleRating']['score']) ? (float) $find['ArticleRating']['score'] : 0);
		} else {
			return $find;
		}
	}

	/**
	 * Get Calculations
	 *
	 * @param $article_id
	 * @param int|null $user_score
	 * @return array
	 */
	public function getCalculations($article_id, $user_score = 0)
	{
		$data = $this->find('all', array(
			'conditions' => array(
				'ArticleRating.article_id' => $article_id
			),
			'fields' => array(
				'SUM(ArticleRating.score) as total,COUNT(ArticleRating.id) as count'
			)
		));

		$total = 0;
		$avg = 0;
		$score = 0;
		if (!empty($data) && !empty($data[0][0])) {
			$total = $data[0][0]['count'];

			if (!empty($data[0][0]['total']) && !empty($total)) {
				$avg = round($data[0][0]['total'] / $total, 1);
				$score = round(floor($avg * 100) / 100, 2);
			}
		}

		return array(
			'score' => $score,
			'avg' => $avg,
			'total' => $total,
			'user' => (float) $user_score,
			'status' => true
		);
	}
}