<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array(
	'action' => 'index',
	'controller' => 'templates'
)) ?>
<?php $this->Html->addCrumb('Add Theme', null) ?>

<?= $this->Form->create('Theme', array('class' => 'well admin-validate')) ?>
	<h2>Add Theme</h2>

	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	
	<?= $this->Form->hidden('created', array(
		'value' => $this->Admin->datetime()
	)) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>