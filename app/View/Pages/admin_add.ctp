<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Pages', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Page', null) ?>

<?php $this->CodeMirror->editor('PageContent') ?>

<?= $this->Form->create('Page', array('class' => 'well admin-validate')) ?>
	<h2>Add Page</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->input('content', array('style' => 'width:80%;height: 300px', 'class' => 'required')) ?>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>