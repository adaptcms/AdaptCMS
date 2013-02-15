<script>
$(document).ready(function(){
	$("#MediaAdminAddForm").validate();
});
</script>

<h1>Add Media Library</h1>
<?php
	echo $this->Form->create('Media', array('class' => 'well'));
	echo $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required',
		'ng-model' => 'title'
	));

	echo $this->Html->link('Attach Images <i class="icon icon-white icon-upload"></i>', '#media-modal', array('class' => 'btn btn-primary', 'escape' => false, 'data-toggle' => 'modal'));
?>
	<p>&nbsp;</p>
	<ul class="selected-images span12 thumbnails"></ul>

	<div class="clearfix"></div>
<?= $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>
<?= $this->element('media_modal') ?>