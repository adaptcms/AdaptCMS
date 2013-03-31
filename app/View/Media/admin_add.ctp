<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Media Libraries', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Library', null) ?>

<?= $this->Form->create('Media', array('class' => 'well admin-validate')) ?>
	<h2>Add Media Library</h2>

	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>

	<?= $this->Html->link('Attach Images <i class="icon icon-white icon-upload"></i>', '#media-modal', array(
		'class' => 'btn btn-primary', 
		'escape' => false, 
		'data-toggle' => 'modal'
	)) ?>

	<ul class="selected-images span12 thumbnails"></ul>
	<div class="clearfix"></div>
	
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>

<?= $this->element('media_modal') ?>