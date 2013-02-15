<?php
	$this->TinyMce->editor();
	$time = date('Y-m-d H:i:s');
?>

<script>
 $(document).ready(function(){
    $("#TicketAddForm").validate();

    $("#TicketEmail").rules("add", {
    	required: true,
    	email: true
    });

 });
 </script>

<h1>Submit Ticket</h1>

<?php
	echo $this->Form->create('Ticket', array('type' => 'file', 'class' => 'well'));
	echo $this->Form->input('full_name', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('email', array('type' => 'text', 'class' => 'required'));

	echo $this->Form->input('subject', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('category', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('message', array('rows' => 15, 'style' => 'width:500px', 'class' => 'required'));

	echo $this->Form->input('priority', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('created', array('value' => $time));
?>
<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>