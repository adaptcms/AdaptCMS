<h1>Add Category</h1>
<?php
	$time = date('Y-m-d H:i:s');

	echo $this->Form->create('Category', array('type' => 'file', 'class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('created', array('value' => $time));
	echo $this->Form->end('Submit');
?>