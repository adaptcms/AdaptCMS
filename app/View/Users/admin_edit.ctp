<?php
	$time = date('Y-m-d H:i:s');
?>

<script>
 $(document).ready(function(){
    $("#UserEditForm").validate();
 });
 </script>

<h1>Edit User</h1>

<?php
    echo $this->Form->create('User', array('type' => 'file', 'action' => 'edit', 'class' => 'well'));
    
	echo $this->Form->input('username', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('password', array('type' => 'password', 'label' => 'New Password?'));
	echo $this->Form->input('email', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('role_id', array('empty' => '- choose -', 'class' => 'required'));

	echo $this->Form->hidden('modified', array('type' => 'hidden', 'value' => $time));
    echo $this->Form->input('id', array('type' => 'hidden'));
 ?>

 <br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>