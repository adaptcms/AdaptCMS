<?php $this->TinyMce->editor(array('simple' => true)) ?>

<script>
$(document).ready(function(){
	$("#ForumTopicAddForm").validate();
});
</script>

<?php $this->set('title_for_layout', 'Forums - ' . $topic['Forum']['title'] . ' :: Edit Topic') ?>

<?php $this->Html->addCrumb('Forums', array('action' => 'index', 'controller' => 'forums')) ?>
<?php $this->Html->addCrumb($topic['Forum']['title'] . ' Forum', array(
	'action' => 'view', 
	'controller' => 'forums', 
	'slug' => $topic['Forum']['slug']
)) ?>
<?php $this->Html->addCrumb($topic['ForumTopic']['subject'], array(
	'action' => 'view', 
	'controller' => 'forum_topics', 
	'forum_slug' => $topic['Forum']['slug'],
	'slug' => $topic['ForumTopic']['slug']
)) ?>
<?php $this->Html->addCrumb('Edit Topic', null) ?>

<h2>
	<?= $topic['Forum']['title'] ?> Forum - Edit Topic
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
	<?php if ($this->Admin->hasPermission($permissions['related']['forum_topics']['change_status'])): ?>
		<?= $this->Form->input('status', array(
			'options' => array(
				'Closed',
				'Open'
			)
		)) ?>
	<?php endif ?>
    <?php if (!empty($topic_type)): ?>
        <?= $this->Form->input('topic_type', array(
            'options' => $topic_type
        )) ?>
    <?php else: ?>
        <?= $this->Form->hidden('topic_type', array('value' => 'topic' )) ?>
    <?php endif ?>
    <!--/nocache-->

	<?= $this->Form->hidden('id', array('value' => $topic['ForumTopic']['id'] )) ?>

	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Update Topic',
	'class' => 'btn btn-primary'
)) ?>