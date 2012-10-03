<h1>Edit Category</h1>
<?php
    echo $this->Form->create('Category', array('type' => 'file', 'action' => 'edit', 'class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('modified', array('type' => 'hidden'));
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Form->end('Submit');
 ?>