<?php

class ForumPostsController extends AdaptbbAppController
{
    /**
    * Name of the Controller, 'ForumPosts'
    */
	public $name = 'ForumPosts';

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
    * Attemps to create a post record
    *
    * @return CakeResponse
    */
    public function ajax_post()
    {
        if ($html_tags = Configure::read('Adaptbb.html_tags_allowed'))
        {
            $this->request->data['ForumPost']['content'] = strip_tags(
                $this->request->data['ForumPost']['content'],
                $html_tags . ',<blockquote>,<small>'
            );
        }

        if ($user_id = $this->Auth->user('id'))
        {
            $this->request->data['ForumPost']['user_id'] = $user_id;
        }

        $forum_id = $this->request->data['ForumPost']['forum_id'];
        $topic_id = $this->request->data['ForumPost']['topic_id'];

	    $result = array();
        if (!empty($this->request->data))
        {
            $replies_num = $this->ForumPost->ForumTopic->findById($topic_id);

            if ($replies_num['ForumTopic']['status'] == 0)
            {
                $result = array(
                    'status' => false,
                    'message' => 'This topic is closed'
                );
            }
	        else
	        {

	            $data = array();

	            $data['ForumTopic']['id'] = $topic_id;
	            $data['ForumTopic']['num_posts'] = $replies_num['ForumTopic']['num_posts'] + 1;

	            $this->ForumPost->ForumTopic->save($data);

	            $this->ForumPost->create();

	            if ($this->ForumPost->save($this->request->data))
	            {
	                $posts_num = $this->ForumPost->ForumTopic->Forum->findById($forum_id);

	                $data = array();

	                $data['Forum']['id'] = $forum_id;
	                $data['Forum']['num_posts'] = $posts_num['Forum']['num_posts'] + 1;

	                $this->ForumPost->ForumTopic->Forum->save($data);

		            $result = array(
	                    'status' => true,
	                    'message' => 'Your post has been made'
	                );
	            } else {
		            $result = array(
	                    'status' => false,
	                    'message' => 'Your post could not be made'
	                );
	            }
	        }
        }

	    return $this->_ajaxResponse(array('body' => json_encode($result) ));
    }

	/**
	 * Ajax Edit
	 *
	 * @return CakeResponse
	 */
	public function ajax_edit()
    {
        $return = array(
            'status' => true,
            'message' => 'The post has been updated.'
        );

        $id = $this->request->data['ForumPost']['id'];

        $post = $this->ForumPost->find('first', array(
            'conditions' => array(
                'ForumPost.id' => $id
            ),
            'contain' => array(
                'User',
                'ForumTopic'
            )
        ));

        if ($this->request->data['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $return = array(
                'status' => false,
                'message' => 'You do not have access to edit other users posts.'
            );
        }

        if (empty($post['ForumPost']))
        {
            $return = array(
                'status' => false,
                'message' => 'Invalid post specified.'
            );
        }

        if (!empty($return['status']) && !empty($this->request->data))
        {
            $this->ForumPost->id = $id;

            if ($html_tags = Configure::read('Adaptbb.html_tags_allowed'))
            {
                $this->request->data['ForumPost']['content'] = strip_tags(
                    $this->request->data['ForumPost']['content'],
                    $html_tags . ',<blockquote>,<small>'
                );
            }

            if (!$this->ForumPost->save($this->request->data))
            {
                $return = array(
                    'status' => false,
                    'message' => 'Your post could not be updated.'
                );
            }
        }

	    return $this->_ajaxResponse(array('body' => json_encode($return) ));
    }

	/**
	 * Delete
	 *
	 * @param $id
	 *
	 * @return void
	 */
	public function delete($id)
    {
        $post = $this->ForumPost->find('first', array(
            'conditions' => array(
                'ForumPost.id' => $id
            ),
            'contain' => array(
                'User'
            )
        ));

		$topic = $this->ForumPost->ForumTopic->find('first', array(
			'conditions' => array(
				'ForumTopic.id' => $post['ForumPost']['topic_id']
			),
			'contain' => array(
				'Forum'
			)
		));

        if (empty($post['ForumPost']))
        {
            $this->Session->setFlash('Post could not be found.', 'flash_error');
            $this->redirect(array(
                'controller' => 'forum_topics',
                'action' => 'view', 
                'slug' => $topic['ForumTopic']['slug'],
				'forum_slug' => $topic['Forum']['slug']
            ));
        }

        if ($post['User']['id'] != $this->Auth->user('id') && $this->permissions['any'] == 0)
        {
            $this->Session->setFlash('You cannot access another users item.', 'flash_error');
            $this->redirect(array(
                'controller' => 'forum_topics',
                'action' => 'view', 
                $this->ForumPost->slug($topic['ForumTopic']['subject'])
            ));               
        }

        $this->ForumPost->id = $id;

        if ($this->ForumPost->saveField('deleted_time', $this->ForumPost->dateTime()) )
        {
            $this->ForumPost->ForumTopic->Forum->id = $topic['ForumTopic']['id'];

            $data = array();
            $data['Forum']['id'] = $topic['Forum']['id'];
            $data['Forum']['num_posts'] = $topic['Forum']['num_posts'] - 1;

            $this->ForumPost->ForumTopic->Forum->save($data);

            $this->ForumPost->ForumTopic->id = $topic['ForumTopic']['id'];

            $data = array();
            $data['ForumTopic']['id'] = $topic['ForumTopic']['id'];
            $data['ForumTopic']['num_posts'] = $topic['ForumTopic']['num_posts'] - 1;

            $this->ForumPost->ForumTopic->save($data);

            $this->Session->setFlash('The post has been deleted.', 'flash_success');
            $this->redirect(array(
                'controller' => 'forum_topics',
                'action' => 'view', 
                'slug' => $topic['ForumTopic']['slug'],
				'forum_slug' => $topic['Forum']['slug']
            )); 
        } else {
            $this->Session->setFlash('Unable to delete the post.', 'flash_error');
        }
    }
}