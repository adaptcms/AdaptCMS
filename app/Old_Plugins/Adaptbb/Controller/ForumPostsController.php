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
    * @return mixed
    */
    public function ajax_post()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

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

        if (!empty($this->request->data))
        {
            $replies_num = $this->ForumPost->ForumTopic->findById($topic_id);

            if ($replies_num['ForumTopic']['status'] == 0)
            {
                return json_encode(array(
                    'status' => false,
                    'message' => 'This topic is closed'
                ));
            }

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

                return json_encode(array(
                    'status' => true,
                    'message' => 'Your post has been made'
                ));
            } else {
                return json_encode(array(
                    'status' => false,
                    'message' => 'Your post could not be made'
                ));
            }
        }
    }
}