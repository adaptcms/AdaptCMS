{{ form.create('ForumPost', array(
	'id' => empty($post_id) ? 'AddPost' : 'edit_post_' . $post['id'],
	'class' => !empty($post_id) ? 'EditPost' : 'visible',
	'url' => array(
		'ajax' => true,
		'controller' => 'forum_posts',
		'action' => empty($post_id) ? 'post' : 'ajax_edit',
		empty($post_id) ? '' : $post_id
	)
)) }}
	
	{% if not empty(post_id) %}
		<h3>Edit Post</h3>
	{% endif %}

	{{ form.input( (!empty($post['id']) ? $post['id'] . '.' : '') . 'content', array(
		'class' => 'span7' . !empty($post_id) ? ' post-content' : '',
		'style' => 'height: 100%',
		'label' => 'Message',
		'required' => false,
		'value' => empty($post_id) ? '' : $post['content']
	)) }}

	{{ form.hidden( (!empty($post['id']) ? $post['id'] . '.' : '') . 'topic_id', array(
		'value' => $topic_id,
		'class' => 'topic_id'
	)) }}
	{{ form.hidden( (!empty($post['id']) ? $post['id'] . '.' : '') . 'forum_id', array(
		'value' => $forum_id,
		'class' => 'forum_id'
	)) }}

	{% if not empty(post_id) %}
		{{ form.hidden( (!empty($post['id']) ? $post['id'] . '.' : '') . 'post_id', array(
			'value' => $post_id,
			'class' => 'post_id'
		)) }}
	{% endif %}

	<div class="reload_url hidden">{{ this->here }}</div>


	{{ form.button('Cancel <i class="icon-remove"></i>', array(
		'type' => 'button',
		'class' => 'btn btn-warning cancel-edit-post',
		'style' => 'margin-top: 10px;margin-bottom: 10px',
		'escape' => false
	)) }}
	{{ form.button('Post Reply <i class="icon-ok"></i>', array(
		'type' => 'submit',
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px;margin-bottom: 10px',
		'escape' => false
	)) }}
{{ form.end() }}