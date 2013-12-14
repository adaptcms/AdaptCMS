<?php
App::uses('AppController', 'Controller');

/**
 * Class ArticleRatingsController
 * @property ArticleRating $ArticleRating
 */
class ArticleRatingsController extends AppController
{
    /**
     * Name of the Controller, 'ArticleRatings'
     */
    public $name = 'ArticleRatings';

    public $uses = array('ArticleRatings.ArticleRating');

    /**
     * array of permissions for this page
     */
    private $permissions;

    /**
     * In this beforeFilter we get the permissions
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->permissions = $this->getPermissions();
    }

	/**
	 * Rate Method
	 *
	 * @return void
	 */
	public function rate()
	{
		$data = array('status' => false);
		if (!empty($this->request->data['ArticleRating']['article_id']) && !empty($this->request->data['ArticleRating']['score']))
		{
			$article_id = $this->request->data['ArticleRating']['article_id'];
			$score = $this->request->data['ArticleRating']['score'];

			$data = $this->ArticleRating->recordRating($this->Auth->user('id'), $article_id, $score);
		}

		return $this->_ajaxResponse(array('body' => $data));
	}
}