<script>
 $(document).ready(function(){
    $("#ThemeAdminAddForm").validate();
 });
 </script>

<h1>Add Theme</h1>
<?php
	$time = date('Y-m-d H:i:s');

	echo $this->Form->create('Theme', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('created', array('value' => $time));
	echo $this->Form->end('Submit');
?>