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

    private $topic_type = array();

    /**
    * In this beforeFilter we will get the permissions to be used in the view files
    */
	public function beforeFilter()
	{
		parent::beforeFilter();
	
		$this->permissions = $this->getPermissions();

        if ($this->request->action == 'add' || $this->request->action == 'edit')
        {
            if ($this->hasPermission($this->permissions['related']['forum_topics']['add_sticky']))
                $this->topic_type['sticky'] = 'Sticky';


            if ($this->hasPermission($this->permissions['related']['forum_topics']['add_announcement']))
                $this->topic_type['announcement'] = 'Announcement';

            if (!empty($this->topic_type))
            {
                $this->topic_type['topic'] = 'Topic';
                $this->topic_type = array_reverse($this->topic_type);
            }

            $this->set('topic_type', $this->topic_type);
        }
	}

    /**
    * Returns a paginated list of topics along with forum data
    *
    * @param string $slug of Forum
     *
    * @return array of topic data
    */
    public function view($slug = null)
    {
        if (empty($slug) && !empty($this->params['slug']))
        {
            $slug = $this->params['slug'];
        }

        $topic = $this->ForumTopic->find('first', array(
            'conditions' => array(
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
            $this->redirect(array('action' => 'index', 'controller' => 'forums'));
        }

        $this->ForumTopic->id = $topic['ForumTopic']['id'];

        if (!$this->request->is('ajax'))
        {
            $data['ForumTopic']['id'] = $topic['ForumTopic']['id'];
            $data['ForumTopic']['num_views'] = $topic['ForumTopic']['num_views'] + 1;

            $this->ForumTopic->save($data);
        }

        $this->Paginator->settings = array(
            'conditions' => array(
                'ForumTopic.slug' => $slug
            ),
            'contain' => array(
                'ForumTopic',
                'User'
            ),
            'order' => 'ForumPost.created ASC',
            'limit' => Configure::read('Adaptbb.num_posts_per_page_topic')
        );

        $posts = $this->Paginator->paginate('ForumPost');

        if (empty($this->params['named']['page']) || $this->params['named']['page'] == 1)
        {
            $posts = array_merge(
                array(
                    array(
                        'ForumPost' => $topic['ForumTopic'], 
                        'User' => $topic['User'],
                        'type' => 'topic'
                    )
                ),
                $posts
            );
        }

        $field = $this->ForumTopic->User->Field->findByTitle('Signature');

        $posts = $this->ForumTopic->User->getModuleData($posts, $field);

        $this->set('forum', $topic['Forum']);
        $this->set('topic', $topic['ForumTopic']);
        $this->set(compact('posts'));
    }

    /**
    * Adding a topic
    *
    * @param string $slug of Forum
     *
    * @return void
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
            $this->request->data['ForumTopic']['status'] = 1;

            if ($html_tags = Configure::read('Adaptbb.html_tags_allowed'))
            {
                $this->request->data['ForumTopic']['content'] = strip_tags(
                    $this->request->data['ForumTopic']['content'],
                    $html_tags . ',<blockquote>,<small>'
                );
            }

            if ($this->request->data['ForumTopic']['topic_type'] != 'topic' && empty($this->topic_type))
                $this->request->data['ForumTopic']['topic_type'] = 'topic';

            if ($this->ForumTopic->save($this->request->data))
            {
                $this->ForumTopic->Forum->id = $forum['Forum']['id'];

                $data['Forum']['id'] = $forum['Forum']['id'];
                $data['Forum']['num_topics'] = $forum['Forum']['num_topics'] + 1;

                $this->ForumTopic->Forum->save($data);

                $this->Session->setFlash('Your topic has been posted.', 'flash_success');
                $this->redirect(array('action' => 'view', $this->ForumTopic->slug($this->request->data['ForumTopic']['subject']) ));
            } else {
                $this->Session->setFlash('Unable to add your topic.', 'flash_error');
            }
        }
    }

	/**
	 * Edit
	 *
	 * @param $id
	 */
	public function edit($id)
    {
        $topic = $this->ForumTopic->find('first', array(
            'conditions' => array(
                'ForumTopic.id' => $id
            ),
            'contain' => array(
                'User',
                'Forum'
            )
        ));

        if (!empty($this->request->data))
        {
            $this->ForumTopic->id = $id;

            if ($topic['User']['id'] != $this->Auth->user('id') && $this->permissions['related']['forum_topics']['change_status']['any'] == 0 ||
                $this->permissions['related']['forum_topics']['change_status']['own'] == 0)
            {
                $this->request->data['ForumTopic']['status'] = $topic['ForumTopic']['status'];
            }

            if ($html_tags = Configure::read('Adaptbb.html_tags_allowed'))
            {
                $this->request->data['ForumTopic']['content'] = strip_tags(
                    $this->request->data['ForumTopic']['content'],
                    $html_tags . ',<blockquote>,<small>'
                );
            }

            if ($this->request->data['ForumTopic']['topic_type'] != 'topic' && empty($this->topic_type))
                $this->request->data['ForumTopic']['topic_type'] = 'topic';

            if ($this->ForumTopic->save($this->request->data))
            {
                $this->Session->setFlash('The topic has been updated.', 'flash_success');
                $this->redirect(array('action' => 'view', $this->ForumTopic->slug($this->request->data['ForumTopic']['subject']) ));
            } else {
                $this->Session->setFlash('Unable to update the topic.', 'flash_error');
            }
        }

        $this->request->data = $topic;

        $this->set('topic', $topic);

        if (empty($topic['ForumTopic']))
        {
            $this->Session->setFlash('Topic could not be found.', 'flash_error');
            $this->redirect(array('action' => 'index', 'controller' => 'forums'));
        }

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'index'));                
        }
    }

    public function delete($id)
    {
        $topic = $this->ForumTopic->find('first', array(
            'conditions' => array(
                'ForumTopic.id' => $id
            ),
            'contain' => array(
                'User',
                'Forum'
            )
        ));

        if (empty($topic['ForumTopic']))
        {
            $this->Session->setFlash('Topic could not be found.', 'flash_error');
            $this->redirect(array('action' => 'view', $this->ForumTopic->slug($topic['ForumTopic']['subject']) ));
        }

        if ($topic['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'view', $this->ForumTopic->slug($topic['ForumTopic']['subject']) ));                
        }

        $this->ForumTopic->id = $id;

        if ($this->ForumTopic->remove($topic) )
        {
            $this->ForumTopic->Forum->id = $topic['Forum']['id'];

            $data['Forum']['id'] = $topic['Forum']['id'];
            $data['Forum']['num_topics'] = $topic['Forum']['num_topics'] - 1;
            $data['Forum']['num_posts'] = $topic['Forum']['num_posts'] - $topic['ForumTopic']['num_posts'];

            $this->ForumTopic->Forum->save($data);

            $this->Session->setFlash('The topic has been deleted.', 'flash_success');
            $this->redirect(array(
                'controller' => 'forums', 
                'action' => 'view', 
                $this->ForumTopic->slug($topic['Forum']['title']) 
            ));
        } else {
            $this->Session->setFlash('Unable to delete the topic.', 'flash_error');
        }
    }

    public function change_status($id)
    {
        $topic = $this->ForumTopic->find('first', array(
            'conditions' => array(
                'ForumTopic.id' => $id
            ),
            'contain' => array(
                'User',
                'Forum'
            )
        ));

        if (empty($topic['ForumTopic']))
        {
            $this->Session->setFlash('Topic could not be found.', 'flash_error');
            $this->redirect(array('action' => 'view', $this->ForumTopic->slug($topic['ForumTopic']['subject']) ));
        }

        if ($topic['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array('action' => 'view', $this->ForumTopic->slug($topic['ForumTopic']['subject']) ));                
        }

        $data = array();

        $data['ForumTopic']['id'] = $id;
        $data['ForumTopic']['status'] = ($topic['ForumTopic']['status'] == 0 ? 1 : 0);

        if ($this->ForumTopic->save($data))
        {
            $this->Session->setFlash('The topic has been ' . ($data['ForumTopic']['status'] == 0 ? 'Closed' : 'Opened') . '.', 'flash_success');
            $this->redirect(array('action' => 'view', $this->ForumTopic->slug($topic['ForumTopic']['subject']) ));
        } else {
            $this->Session->setFlash('Unable to update the topic.', 'flash_error');
        }
    }
}