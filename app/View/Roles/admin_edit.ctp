<script>
 $(document).ready(function(){
    $("#RoleEditForm").validate();
 });
 </script>

<h1>Edit Role</h1>

<?php
    echo $this->Form->create('Role', array('type' => 'file', 'action' => 'edit', 'class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('defaults', array('options' => array(
			'default-member' => 'Default Member', 
			'default-guest' => 'Default Guest'
			),
		'label' => 'Default Settings',
		'empty' => '- choose -'
	));
	$category = 0;
	$i = 0;
?>

<?php foreach($this->request->data['Permission'] as $row): $i++; $perm[$row['id']] = $row['title']; ?>
	<?= $this->Form->hidden('Permission.'.$row['id'].'.id', array('value' => $row['id'])) ?>

	<?php if ($i % 3 == 0): ?><div style="clear:both"></div><div style="float:left"><?php elseif($i == 1): ?><div style="float:left"><?php else: ?><div style="float:left;margin-left:50px"><?php endif; ?>
	
	<?php if ($category == 1) {
		echo "</ul>";
	}
	$category = 1;
	?>
	
	<b><?= $row['title'] ?></b>
	<ul>
	<?php foreach($row['PermissionValue'] as $data): ?>
		<?= $this->Form->hidden('Permission.'.$row['id'].'.PermissionValue.'.$data['id'].'.permission_id', array('value' => $row['id'])) ?>
		<?= $this->Form->hidden('Permission.'.$row['id'].'.PermissionValue.'.$data['id'].'.id', array('value' => $data['id'])) ?>
		<li><?= $data['title'] ?> <?= $this->Form->checkbox('Permission.'.$row['id'].'.PermissionValue.'.$data['id'].'.action', array(
			'default' => $data['action'],
			'value' => 1
		)) ?></li>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
<div style="clear:both"></div>

<?php if ($category == 1): echo "</ul>"; endif; ?>

<?php
	echo $this->Form->hidden('created', array('type' => 'hidden'));
    echo $this->Form->input('id', array('type' => 'hidden'));
 ?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>

<h1>Add Permission Values</h1>
<?php echo $this->Form->create('PermissionValue', array('action' => 'add', 'class' => 'well')); ?>

<?= $this->Form->input('title') ?>
<?= $this->Form->input('permission_id', array('empty' => '- choose -', 'options' => $perm)) ?>
<?= $this->Form->input('plugin') ?>
<?= $this->Form->input('controller') ?>
<?= $this->Form->input('pageAction') ?>
<?= $this->Form->input('action', array('options' => array(0, 1))) ?>

<?= $this->Form->hidden('role_id', array('value' => $this->request->data['Role']['id'])) ?>
<?= $this->Form->hidden('type', array('value' => 'default')) ?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>


<h1>Add Permission</h1>
<?php echo $this->Form->create('Permission', array('action' => 'add', 'class' => 'well')); ?>

<?= $this->Form->input('title') ?>
<?= $this->Form->hidden('role_id', array('value' => $this->request->data['Role']['id'])) ?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>