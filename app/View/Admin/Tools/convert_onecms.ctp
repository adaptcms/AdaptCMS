<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Convert OneCMS', null) ?>

<?= $this->Form->create('Convert', array('class' => 'well')) ?>
	<h2>Convert OneCMS Install</h2>

	<p>
		Content, Games, Systems, Users, Categories, Fields, Field Data and Pages are all transferred.
	</p>

	<?= $this->Form->input('prefix', array('value' => 'onecms_')) ?>
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>