<script>
 $(document).ready(function(){
    $("#UserAdminAddForm").validate();
 });
 </script>

<h1>Add User</h1>

<?php

	echo $this->Form->create('User', array('class' => 'well'));

	echo $this->Form->input('username', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('password', array('type' => 'password', 'class' => 'required'));
	echo $this->Form->input('password_confirm', array('type' => 'password', 'class' => 'required'));
	echo $this->Form->input('email', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('role_id', array('empty' => '- choose -', 'class' => 'required'));

	echo $this->Form->hidden('status', array('value' => 1));
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->hidden('last_reset_time', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
?>
<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
)) ?>