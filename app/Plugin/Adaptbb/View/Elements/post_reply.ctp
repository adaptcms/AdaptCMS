<?= $this->Form->create('ForumPost', array(
	'id' => 'AddPost',
	'url' => array(
		'ajax' => true,
		'controller' => 'forum_posts',
		'action' => 'post'
	)
)) ?>

	<?= $this->Form->input('content', array(
		'class' => 'span7',
		'style' => 'height: 100%',
		'label' => 'Message',
		'required' => false
	)) ?>

	<?= $this->Form->hidden('topic_id', array(
		'value' => $topic_id
	)) ?>
	<?= $this->Form->hidden('forum_id', array(
		'value' => $forum_id
	)) ?>

	<div id="reload_url" class="hidden"><?= $this->here ?></div>

	<?= $this->Form->button('Post Reply <i class="icon-ok"></i>', array(
		'type' => 'submit',
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px;margin-bottom: 10px',
		'escape' => false
	)) ?>
<?= $this->Form->end() ?>