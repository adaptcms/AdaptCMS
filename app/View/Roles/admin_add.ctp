<script>
 $(document).ready(function(){
    $("#RoleAdminAddForm").validate();
 });
 </script>

<h1>Add Role</h1>

<?php
	echo $this->Form->create('Role', array('class' => 'well'));
	
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('defaults', array('options' => array(
			'default-member' => 'Default Member', 
			'default-guest' => 'Default Guest'
			),
		'label' => 'Default Settings',
		'empty' => '- choose -'
	));
	
	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>