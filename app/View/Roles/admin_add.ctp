<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Roles', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Role', null) ?>

<?= $this->Form->create('Role', array('class' => 'well admin-validate')) ?>
	<h2>Add Role</h2>
	
	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->input('defaults', array('options' => array(
			'default-member' => 'Default Member', 
			'default-guest' => 'Default Guest'
			),
		'label' => 'Default Settings',
		'empty' => '- choose -'
	)) ?>
	<?= $this->Form->input('role_id', array(
		'label' => 'Default Permissions from Role',
		'class' => 'required'
	)) ?>
	
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>