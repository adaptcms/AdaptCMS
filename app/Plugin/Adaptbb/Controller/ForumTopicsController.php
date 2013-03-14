<?php

class ForumTopicsController extends AdaptbbAppController
{
    /**
    * Name of the Controller, 'ForumTopics'
    */
	public $name = 'ForumTopics';

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
	
		$this->permissions = $this->getPermissions();
	}

    /**
    * Returns a paginated list of topics along with forum data
    *
    * @param slug of Forum
    * @return associative array of topic data
    */
    public function view($slug)
    {
        $topic = $this->ForumTopic->find('first', array(
            'conditions' => array(
                'ForumTopic.deleted_time' => '0000-00-00 00:00:00',
                'ForumTopic.slug' => $slug
            ),
            'contain' => array(
                'User',
                'Forum'
            )
        ));

        if (empty($topic['ForumTopic']))
        {
            $this->Session->setFlash('The Topic `' . $slug . '` could not be found.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $this->ForumTopic->id = $topic['ForumTopic']['id'];

        $this->paginate = array(
            'conditions' => array(
                'ForumTopic.slug' => $slug,
                'ForumTopic.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'ForumTopic'
            ),
            'order' => 'ForumPost.created ASC'
        );

        $this->set('forum', $topic['Forum']);
        $this->set('topic', $topic['ForumTopic']);
        $this->set('posts', $this->paginate('ForumPost'));
    }

    /**
    * Returns a forum
    *
    * @param slug of Forum
    * @return associative array of topic data
    */
    public function add($slug)
    {
        $forum = $this->ForumTopic->Forum->findBySlug($slug);

        $this->set('forum', $forum['Forum']);

        if (empty($forum['Forum']))
        {
            $this->Session->setFlash('Forum `' . $slug . '` could not be found.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->request->data))
        {
            $this->ForumTopic->create();

            $this->request->data['ForumTopic']['user_id'] = $this->Auth->user('id');

            if ($this->ForumTopic->save($this->request->data))
            {
                $this->Session->setFlash('Your topic has been posted.', 'flash_success');
                $this->redirect(array('action' => 'view', $this->request->data['ForumTopic']['slug'] ));
            } else {
                $this->Session->setFlash('Unable to add your topic.', 'flash_error');
            }
        }
    }
}