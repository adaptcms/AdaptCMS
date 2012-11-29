<?php
	$time = date('Y-m-d H:i:s');
?>

<style>
.poll-option-remove {
	margin-bottom: 9px;
	margin-left: 10px;
}
button.btn, input[type="submit"].btn {
	float: left;
}
label.error {
	display: inline;
	margin-left: 10px;
}
</style>

<script>
 $(document).ready(function(){
    $("#PollAdminAddForm").validate();

    $("button#poll-option-add").live('click', function() {
    	var count = Number($(".option").length);
    	var number = count + 1;

    	$("#options").append('<div id="option'+number+'"><div class="input text"><label for="PollValue'+number+'Title">Option '+number+'</label><input name="data[PollValue]['+number+'][title]" class="required option" type="text" id="PollValue'+number+'Title"> <a class="btn btn-danger poll-option-remove" id="'+number+'"><i class="icon-trash icon-white poll-delete"></i> Delete</a></div></div>')
    });

    $(".poll-option-remove").live('click', function() {
    	$("#option"+this.id).remove();
    });
 });
 </script>

<h1>Add Poll</h1>

<?php
	echo $this->Form->create('Poll', array('class' => 'well'));
	echo $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	));
	echo $this->Form->input('article_id', array(
		'label' => 'Attach to Article', 
		'empty' => ' - choose - '
	));
?>
<div id="options">
<?= 
	$this->Form->input('PollValue.1.title', array(
		'label' => 'Option 1', 
		'class' => 'required option'
	))
?>
</div>

<?= $this->Form->hidden('created', array('value' => $time)) ?>
<br />
<?= $this->Form->button('Add Option', array(
		'type' => 'button',
		'id' => 'poll-option-add',
		'class' => 'btn'
		)); ?>
<?= $this->Form->submit('Submit',array(
		'class' => 'btn',
		'style' => 'margin-left:5px'
		)) ?>
<br />
<?= $this->Form->end(); ?>