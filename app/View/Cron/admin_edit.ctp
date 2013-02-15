<h1>Edit Cron Entry</h1>

<script>
$(document).ready(function(){
	$("#CronEditForm").validate();
});
</script>

<?php
	echo $this->Form->create('Cron', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('module_id', array(
		'empty' => '- Choose Component -',
		'class' => 'required'
	));
	echo $this->Form->input('function', array(
		'class' => 'required'
	));
	echo $this->Form->input('period_amount', array(
		'class' => 'required',
		'options' => $period_amount,
		'empty' => '- choose -'
	));
	echo $this->Form->input('period_type', array(
		'class' => 'required',
		'options' => array(
			'minute' => 'Minute(s)',
			'hour' => 'Hour(s)',
			'day' => 'Day(s)',
			'week' => 'Week(s)'
		),
		'empty' => '- choose -'
	));

	echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->hidden('id');
?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>