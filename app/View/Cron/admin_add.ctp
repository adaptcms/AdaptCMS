<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('controller' => 'tools', 'action' => 'index')) ?>
<?php $this->Html->addCrumb('Cron Entries', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Cron', null) ?>

<?= $this->Form->create('Cron', array('class' => 'well admin-validate')) ?>
	<h2>Add Cron Entry</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->input('module_id', array(
		'empty' => '- Choose Component -',
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('function', array(
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('period_amount', array(
		'class' => 'required',
		'options' => $period_amount,
		'empty' => '- choose -'
	)) ?>
	<?= $this->Form->input('period_type', array(
		'class' => 'required',
		'options' => array(
			'minute' => 'Minute(s)',
			'hour' => 'Hour(s)',
			'day' => 'Day(s)',
			'week' => 'Week(s)'
		),
		'empty' => '- choose -'
	)) ?>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>