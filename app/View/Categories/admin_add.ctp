<h1>Add Category</h1>

<script>
$(document).ready(function(){
	$("#CategoryAdminAddForm").validate();
});
</script>

<?php
	$time = date('Y-m-d H:i:s');

	echo $this->Form->create('Category', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->end('Submit');
?>