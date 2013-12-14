<?php
App::uses('CakeEventListener', 'Event');

/**
 * Class PollEventListener
 *
 * @property Poll $Poll
 * @property Permission $Permission
 * @property ArticlesController $Controller
 */
class PollsEventListener implements CakeEventListener
{
	protected $Poll;

	/**
	 * Implemented Events
	 *
	 * @return array
	 */
	public function implementedEvents() {
		return array(
			'Controller.Articles.view.beforeRender' => 'getPolls',
		);
	}

	/**
	 * Get Polls
	 *
	 * @param $event
	 * @return array
	 */
	public function getPolls($event)
	{
		$this->Poll = ClassRegistry::init('Polls.Poll');

		$user_id = Configure::read('User.id');
		$role = Configure::read('User.role');
		$permission = Configure::read('User.permission.polls');

		if (empty($permission) && !empty($role)) {
			$this->Permission = ClassRegistry::init('Permission');
			$permission = $this->Permission->find('first', array(
				'conditions' => array(
					'Permission.role_id' => $role,
					'Permission.action' => 'vote',
					'Permission.controller' => 'polls',
					'Permission.plugin' => 'polls',
					'Permission.status' => 1
				)
			));
			Configure::write('User.permission.polls', array('result' => $permission));
		} elseif(!empty($permission)) {
			$permission = $permission['result'];
		}

		$result = $event->data['data'];
		$Controller = $event->data['controller'];

		if (!empty($event->result['data'])) {
			$result = array_merge_recursive($result, $event->result['data']);
		}

		if (!empty($result)) {
			$options = array(
				'article_id' => $result['Article']['id']
			);
			$polls = $this->Poll->getBlockData($options, $user_id);

			if (!empty($polls) && !empty($polls['Poll']))
				$polls[0] = $polls;

			if (!empty($polls)) {
				foreach($polls as $key => $poll) {
					$slug = $this->Poll->slug($poll['Poll']['title']);

					$poll_data = $Controller->_getElement('Polls.show_poll', array(
						'data' => $poll,
						'permissions' => array(
							'related' => (!empty($permission['Permission']['status']) ? true : false)
						)
					));

					$result['Polls'][$slug] = $poll_data;
					$result['Polls'][$key] = $poll_data;
				}
			}
		}

		return $result;
	}
}