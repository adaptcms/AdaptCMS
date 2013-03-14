<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Categories', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Category', null) ?>

<?= $this->Form->create('Category', array('class' => 'well admin-validate')) ?>
	<h2>Add Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>