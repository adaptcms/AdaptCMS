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
    public function view($slug = null)
    {
        if (empty($slug) && !empty($this->params['slug']))
        {
            $slug = $this->params['slug'];
        }

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

        if (empty($topic))
        {
            $this->Session->setFlash('The Topic `' . $slug . '` could not be found.', 'flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $this->ForumTopic->id = $topic['ForumTopic']['id'];

        if (!$this->RequestHandler->isAjax())
        {
            $data['ForumTopic']['id'] = $topic['ForumTopic']['id'];
            $data['ForumTopic']['num_views'] = $topic['ForumTopic']['num_views'] + 1;

            $this->ForumTopic->save($data);
        }

        $this->paginate = array(
            'conditions' => array(
                'ForumTopic.slug' => $slug,
                'ForumTopic.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'ForumTopic',
                'User'
            ),
            'order' => 'ForumPost.created ASC',
            'limit' => Configure::read('Adaptbb.num_posts_per_page_topic')
        );

        $posts = $this->paginate('ForumPost');

        if (empty($this->params['named']['page']) || $this->params['named']['page'] == 1)
        {
            $posts = array_merge(array(array('ForumPost' => $topic['ForumTopic'], 'User' => $topic['User'])), $posts);
        }

        $this->set('forum', $topic['Forum']);
        $this->set('topic', $topic['ForumTopic']);
        $this->set(compact('posts'));
    }

    /**
    * Returns a forum
    *
    * @param slug of Forum
    * @return associative array of topic data
    */
    public function add($slug = null)
    {
        if (empty($slug) && !empty($this->params['slug']))
        {
            $slug = $this->params['slug'];
        }

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

            if ($html_tags = Configure::read('Adaptbb.html_tags_allowed'))
            {
                $this->request->data['ForumTopic']['content'] = strip_tags(
                    $this->request->data['ForumTopic']['content'],
                    $html_tags . ',<blockquote>,<small>'
                );
            }

            if ($this->ForumTopic->save($this->request->data))
            {
                $this->ForumTopic->Forum->id = $forum['Forum']['id'];

                $data['Forum']['id'] = $forum['Forum']['id'];
                $data['Forum']['num_topics'] = $forum['Forum']['num_topics'] + 1;

                $this->ForumTopic->Forum->save($data);

                $this->Session->setFlash('Your topic has been posted.', 'flash_success');
                $this->redirect(array('action' => 'view', $this->slug($this->request->data['ForumTopic']['subject']) ));
            } else {
                $this->Session->setFlash('Unable to add your topic.', 'flash_error');
            }
        }
    }
}