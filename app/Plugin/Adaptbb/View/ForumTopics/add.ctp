<?php $this->TinyMce->editor(array('simple' => true)) ?>

<script>
$(document).ready(function(){
	$("#ForumTopicAddForm").validate();
});
</script>

<?php $this->set('title_for_layout', 'Forums - ' . $forum['title'] . ' :: Add Topic') ?>

<?php $this->Html->addCrumb('Forums', array('action' => 'index', 'controller' => 'forums')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', array(
	'action' => 'view', 
	'controller' => 'forums', 
	'slug' => $forum['slug']
)) ?>
<?php $this->Html->addCrumb('New Topic', null) ?>

<h2>
	<?= $forum['title'] ?> Forum - Add Topic
</h2>

<?= $this->Form->create('ForumTopic', array('class' => 'well admin-validate')) ?>
	
	<?= $this->Form->input('subject', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('content', array(
		'type' => 'textarea',
		'class' => 'required span8',
		'style' => 'height: 100%'
	)) ?>

    <!--nocache-->
    <?php if (!empty($topic_type)): ?>
        <?= $this->Form->input('topic_type', array(
            'options' => $topic_type
        )) ?>
    <?php else: ?>
        <?= $this->Form->hidden('topic_type', array('value' => 'topic' )) ?>
    <?php endif ?>
    <!--/nocache-->

	<?= $this->Form->hidden('forum_id', array('value' => $forum['id'] )) ?>

	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit Topic',
	'class' => 'btn btn-primary'
)) ?>