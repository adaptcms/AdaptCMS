<script>
 $(document).ready(function(){
    $("#SettingAdminAddForm").validate();
 });
 </script>

<h1>Add Settings Category</h1>

<?php
	echo $this->Form->create('Setting', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
?>

<br />
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>