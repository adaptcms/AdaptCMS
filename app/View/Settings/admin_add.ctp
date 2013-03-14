<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Settings', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Setting Category', null) ?>

<?= $this->Form->create('Setting', array('class' => 'well admin-validate')) ?>
	<h2>Add Settings Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>