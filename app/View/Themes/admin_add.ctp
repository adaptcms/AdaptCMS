<script>
 $(document).ready(function(){
    $("#ThemeAdminAddForm").validate();
 });
 </script>

<h1>Add Theme</h1>
<?php
	echo $this->Form->create('Theme', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->end('Submit');
?>