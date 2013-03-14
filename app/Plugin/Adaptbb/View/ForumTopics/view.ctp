<?php $this->Html->addCrumb('Forums', array('action' => 'index', 'controller' => 'forums')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', array(
	'action' => 'view', 
	'controller' => 'forums', 
	$forum['slug']
)) ?>
<?php $this->Html->addCrumb('View Topic', null) ?>

<h2>
	Viewing Topic
</h2>

<?= $this->Html->link('New Reply', array(
	'controller' => 'forum_topics',
	'action' => 'add',
	$forum['slug']
), array('class' => 'btn btn-info pull-right', 'style' => 'margin-bottom: 10px')) ?>



<?php if (!empty($posts)): ?>
	<?php foreach($posts as $post): ?>

	<?php endforeach ?>
<?php endif ?>