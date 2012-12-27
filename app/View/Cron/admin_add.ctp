<h1>Add Cron Entry</h1>

<script>
$(document).ready(function(){
	$("#CronAdminAddForm").validate();
});
</script>

<?php
	echo $this->Form->create('Cron', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('component_id', array(
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

	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->end('Submit');
?>