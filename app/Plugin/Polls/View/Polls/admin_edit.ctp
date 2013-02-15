<?php
	$time = date('Y-m-d H:i:s');
?>

<style>
.remove {
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
    $("#PollAdminEditForm").validate();

    $("button#poll-option-add").live('click', function() {
    	var count = Number($(".option").length);
    	var number = count + 1;

    	$("#options").append('<div id="option'+number+'"><div class="input text"><label for="PollValue'+number+'Title">Option '+number+'</label><input name="data[PollValue]['+number+'][title]" class="required option" type="text" id="PollValue'+number+'Title"> <a class="btn btn-danger poll-option-remove remove" id="'+number+'"><i class="icon-trash icon-white poll-delete"></i> Delete</a></div></div>');
    });

    $(".poll-option-remove").live('click', function() {
    	$("#option"+this.id).remove();
    });

    $(".poll-option-remove2").live('click', function() {
    	var id = this.id;

    	$("#option"+id).css("opacity", "0.3");
    	$(".title"+id).removeClass('required');
    	$(".title"+id).prop('disabled', true);
    	$(".delete"+id).val('1');
    	$("#"+id).replaceWith('<a class="btn btn-primary poll-option-undo remove" id="'+id+'"><i class="icon-refresh icon-white"></i> Undo&nbsp;</a>');
    });

    $(".poll-option-undo").live('click', function() {
    	var id = this.id;

    	$("#option"+id).css("opacity", "1");
    	$(".title"+id).addClass('required');
    	$(".title"+id).prop('disabled', false);
    	$(".delete"+id).val('0');
    	$("#"+id).replaceWith('<a class="btn btn-danger poll-option-remove2 remove" id="'+id+'"><i class="icon-trash icon-white poll-delete"></i> Delete</a>');
    });
 });
 </script>

<h2 class="left">Edit Poll</h2>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Poll']['id'], $this->request->data['Poll']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this poll?')"));
    ?>
</div>
<div class="clearfix"></div>

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
<?php 
$key = 0;
foreach($this->request->data['PollValue'] as $data): 
$key++;
?>

<div id='option<?= $key ?>'>
	<div class='input text'>
	<?= $this->Form->input('PollValue.'.$data['id'].'.title', array(
		'label' => 'Option '.$key,
		'value' => $data['title'],
		'class' => 'required option title'.$key,
		'div' => false
	)) ?>
	<a class="btn btn-danger poll-option-remove2 remove" id="<?= $key ?>"><i class="icon-trash icon-white poll-delete"></i> Delete</a></div>

<?= $this->Form->input('PollValue.'.$data['id'].'.id', array('value' => $data['id'])) ?>
<?= $this->Form->hidden('PollValue.'.$data['id'].'.delete', array('value' => 0, 'class' => 'delete'.$key)) ?>
</div>

<?php endforeach; ?>
</div>

<?= $this->Form->hidden('modified', array('value' => $time)) ?>
<?= $this->Form->input('id', array('type' => 'hidden')) ?>
<br />
<?= $this->Form->button('Add Option', array(
	'type' => 'button',
	'id' => 'poll-option-add',
	'class' => 'btn btn-warning'
)) ?>
<?= $this->Form->submit('Submit',array(
	'class' => 'btn btn-primary',
	'style' => 'margin-left:5px'
)) ?>
<br />
<?= $this->Form->end() ?>