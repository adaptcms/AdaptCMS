<?php
App::uses('PollsAppController', 'Polls.Controller');
/**
 * Class PollsController
 * @property Poll $Poll
 */
class PollsController extends PollsAppController
{
	/**
	 * Name of the Controller, 'Polls'
	 */
	public $name = 'Polls';

	/**
	 * array of permissions for this page
	 */
	private $permissions;

	/**
	 * In this beforeFilter we will get the permissions to be used in the view files
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();

		if ($this->request->action == 'admin_add' || $this->request->action == 'admin_edit')
			$this->set('articles', $this->Poll->Article->find('list'));

		$this->permissions = $this->getPermissions();
	}

	/**
	 * Returns a paginated index of Polls
	 *
	 * @return array of polls data
	 */
	public function admin_index()
	{
		$conditions = array();

		if (isset($this->request->named['trash']))
			$conditions['Poll.only_deleted'] = true;

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'User'
			)
		);

		$this->request->data = $this->Paginator->paginate('Poll');
	}

	/**
	 * Returns nothing before post
	 *
	 * On POST, returns error flash or success flash and redirect to index on success
	 *
	 * @return mixed
	 */
	public function admin_add()
	{
		if (!empty($this->request->data)) {
			$this->Poll->create();

			$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

			if ($this->Poll->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your poll has been added.', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your poll.', 'error');
			}
		}
	}

	/**
	 * Before POST, sets request data to form
	 *
	 * After POST, flash error or flash success and redirect to index
	 *
	 * @param integer $id of the database entry, redirect to index if no permissions
	 * @return array of poll data
	 */
	public function admin_edit($id)
	{
		$this->Poll->id = $id;

		if (!empty($this->request->data)) {
			foreach ($this->request->data['PollValue'] as $key => $row) {
				if (!empty($row['delete']) && !empty($row['id'])) {
					unset($this->request->data['PollValue'][$key]);
					$this->Poll->PollValue->delete($row['id']);
				}
			}

			$this->request->data['Poll']['user_id'] = $this->Auth->user('id');

			if ($this->Poll->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your poll has been updated.', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update your poll.', 'error');
			}
		}

		$this->request->data = $this->Poll->find('first', array(
			'conditions' => array(
				'Poll.id' => $id
			),
			'contain' => array(
				'PollValue' => array(
					'PollVotingValue' => array(
						'User'
					)
				),
				'User'
			)
		));
		$this->hasAccessToItem($this->request->data);
	}

	/**
	 * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
	 *
	 * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
	 *
	 * @param integer $id of the database entry, redirect to index if no permissions
	 * @param string $title of this entry, used for flash message
	 * @return void
	 */
	public function admin_delete($id, $title = null)
	{
		$this->Poll->id = $id;

		$data = $this->Poll->findById($id);
		$this->hasAccessToItem($data);

		$permanent = $this->Poll->remove($data);

		$this->Session->setFlash('The poll `' . $title . '` has been deleted.', 'success');

		if ($permanent) {
			$this->redirect(array('action' => 'index', 'trash' => 1));
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}

	/**
	 * Restoring an item will take an item in the trash and reset the delete time
	 *
	 * This makes it live wherever applicable
	 *
	 * @param integer $id of database entry, redirect if no permissions
	 * @param string $title of this entry, used for flash message
	 * @return void
	 */
	public function admin_restore($id, $title = null)
	{
		$this->Poll->id = $id;

		$data = $this->Poll->findById($id);
		$this->hasAccessToItem($data);

		if ($this->Poll->restore()) {
			$this->Session->setFlash('Your poll `' . $title . '` has been restored.', 'success');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('Unable to restore your poll.', 'error');
		}
	}

	/**
	 * Action to vote on a poll
	 *
	 * @return CakeResponse
	 */
	public function vote()
	{
		$id = $this->request->data['Poll']['id'];

		$conditions = array(
			'conditions' => array(
				'PollVotingValue.poll_id' => $id
			)
		);

		if ($this->Auth->user('id')) {
			$conditions['conditions']['OR'] = array(
				'PollVotingValue.user_id' => $this->Auth->user('id'),
				'PollVotingValue.user_ip' => $_SERVER['REMOTE_ADDR']
			);
		} else {
			$conditions['conditions']['PollVotingValue.user_ip'] = $_SERVER['REMOTE_ADDR'];
		}

		$count = $this->Poll->PollVotingValue->find('count', $conditions);

		$msg = array(
			'type' => 'success',
			'message' => 'Your vote has been made'
		);
		if ($count == 0) {
			$this->Poll->id = $id;

			$data = array(
				'PollVotingValue' => array(
					'poll_id' => $id,
					'value_id' => $this->request->data['Poll']['value'],
					'user_id' => $this->Auth->user('id'),
					'user_ip' => $_SERVER['REMOTE_ADDR']
				)
			);

			$this->Poll->PollVotingValue->create();
			if ($this->Poll->PollVotingValue->save($data)) {
				$find = $this->Poll->find('first', array(
					'conditions' => array(
						'Poll.id' => $id
					),
					'contain' => array(
						'PollValue'
					)
				));

				$total_votes = $find['Poll']['total_votes'] + 1;
				$find['Poll']['total_votes'] = 0;
				foreach ($find['PollValue'] as $key => $row) {
					if ($row['id'] == $this->request->data['Poll']['value']) {
						$find['PollValue'][$key]['votes'] = $row['votes'] + 1;
						$find['PollValue'][$key]['percent'] = round($find['PollValue'][$key]['votes'] / $total_votes * 100);

						$row['votes'] = $find['PollValue'][$key]['votes'];

						$this->Poll->PollValue->id = $row['id'];
						$this->Poll->PollValue->saveField('votes', $row['votes']);
					}

					$find['Poll']['total_votes'] = $find['Poll']['total_votes'] + $row['votes'];
				}
			}
		} else {
			$msg = array(
				'type' => 'error',
				'message' => 'You have already voted on this poll'
			);
		}

		if (empty($find))
			$find = $this->Poll->find('first', array(
				'conditions' => array(
					'Poll.id' => $id
				),
				'contain' => array(
					'PollValue'
				)
			));

		$this->set('data', $find);

		return $this->_ajaxResponse('Polls.poll_vote_results', array(
			'data' => $find,
			'msg' => $msg
		));
	}

	/**
	 * Passed poll data and renders vote results element
	 *
	 * @return CakeResponse
	 */
	public function ajax_results()
	{
		$find = $this->Poll->find('first', array(
			'conditions' => array(
				'Poll.id' => $this->request->data['Poll']['id']
			),
			'contain' => array(
				'PollValue'
			)
		));

		if (!empty($find['Poll']['id']) && !empty($this->permissions['related']['polls']['vote']))
			$find = $this->Poll->canVote($find, $this->Auth->user('id'));

		$data = $this->Poll->totalVotes($find);

		if (!empty($this->request->data['Block']['title']))
			$data['Block']['title'] = $this->request->data['Block']['title'];

		return $this->_ajaxResponse('Polls.poll_vote_results', array(
			'data' => $data
		));
	}

	/**
	 * View poll
	 *
	 * @return CakeResponse
	 */
	public function ajax_view_poll()
	{
		$conditions = array();

		$conditions['Poll.id'] = $this->request->data['Poll']['id'];

		if ($this->permissions['any'] == 0)
			$conditions['Poll.user_id'] = $this->Auth->user('id');

		$find = $this->Poll->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'PollValue'
			)
		));

		if (!empty($find['Poll']['id']) && !empty($this->permissions['related']['polls']['vote']))
			$find = $this->Poll->canVote($find, $this->Auth->user('id'));

		foreach ($find['PollValue'] as $option) {
			$find['options'][$option['id']] = $option['title'];
		}

		$data = $this->Poll->totalVotes($find);

		if (!empty($this->request->data['Block']['title']))
			$data['Block']['title'] = $this->request->data['Block']['title'];

		return $this->_ajaxResponse('Polls.poll_vote', array(
			'data' => $data
		));
	}

	/**
	 * All Method
	 * Returns a paginated list of polls
	 *
	 * @return CakeResponse
	 */
	public function all()
	{
		$conditions = array();

		$schema = $this->Poll->schema();
		if (!empty($schema['start_date']) && !empty($schema['end_date'])) {
			$conditions['Poll.start_date <='] = date('Y-m-d');
			$conditions['Poll.end_date >='] = date('Y-m-d');
		}

		if ($this->permissions['any'] == 0)
			$conditions['User.id'] = $this->Auth->user('id');

		$this->Paginator->settings = array(
			'order' => 'Poll.created DESC',
			'conditions' => $conditions,
			'contain' => array(
				'User',
				'PollValue'
			)
		);

		$polls = $this->Poll->canVote($this->Paginator->paginate('Poll'), $this->Auth->user('id'));

		$this->set(compact('polls'));

		$this->view = 'list';
	}
}