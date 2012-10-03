<?php
	$this->EditArea->editor('PageContent');
?>

<script>
 $(document).ready(function(){
    $("#PageEditForm").validate();
 });
 </script>

<h1>Edit Page</h1>

<?php
    echo $this->Form->create('Page', array('type' => 'file', 'action' => 'edit', 'class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('old_title', array('value' => $this->request->data['Page']['title']));
	echo $this->Form->input('content', array('style' => 'width:80%;height: 300px', 'class' => 'required'));
	echo $this->Form->hidden('created', array('type' => 'hidden'));
    echo $this->Form->input('id', array('type' => 'hidden'));
 ?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>