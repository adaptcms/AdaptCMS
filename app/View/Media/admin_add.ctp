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
	<div id="selected-images" class="span12 row"></div>

	<div class="clearfix"></div>
<?php
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->end('Submit');
?>

<?= $this->element('media_modal') ?>