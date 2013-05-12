<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Template', null) ?>

<?php
	$this->CodeMirror->editor('TemplateTemplate');
?>

<?= $this->Form->create('Template', array('class' => 'well admin-validate')) ?>
	<h2>Add Template</h2>
	
	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required', 
		'label' => 'File Name'
	)) ?>
	<?= $this->Form->input('label', array('type' => 'text')) ?>
	
	<?= $this->Form->input('template', array(
		'rows' => 25, 
		'style' => 'width:90%', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('theme_id', array(
	    'empty' => '- Choose -',
	    'class' => 'required',
	    'value' => $theme_id
	)) ?>
	<?= $this->Form->input('location', array(
		'options' => $locations,
		'empty' => '- Choose -',
		'class' => 'required'
	)) ?>

	<?= $this->Form->hidden('created', array(
		'value' => $this->Admin->datetime()
	)) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>