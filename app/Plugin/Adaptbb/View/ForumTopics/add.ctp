<?php $this->TinyMce->editor() ?>

<script>
$(document).ready(function(){
	$("#ForumTopicAddForm").validate();
});
</script>

<?php $this->Html->addCrumb('Forums', array('action' => 'index', 'controller' => 'forums')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', array(
	'action' => 'view', 
	'controller' => 'forums', 
	$forum['slug']
)) ?>
<?php $this->Html->addCrumb('New Topic', null) ?>

<h2>
	<?= $forum['title'] ?> Forum - Add Topic
</h2>

<?= $this->Form->create('ForumTopic', array('class' => 'well', 'id' => 'ForumTopicAddForm')) ?>
	
	<?= $this->Form->input('subject', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('content', array(
		'type' => 'textarea',
		'class' => 'required span8',
		'style' => 'height: 100%'
	)) ?>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
	<?= $this->Form->hidden('forum_id', array('value' => $forum['id'] )) ?>

	<div class="clearfix"></div>

<?= $this->Form->end('Submit Topic', array('class' => 'btn btn-primary')) ?>