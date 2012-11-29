<script>
 $(document).ready(function(){
    $("#RoleEditForm").validate();
 });
 </script>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Role</a>
	</li>
	<li>
		<a href="#permission-type" data-toggle="tab">Add Permission Type</a>
	</li>
	<li>
		<a href="#permission-value" data-toggle="tab">Add Permission Value</a>
	</li>
</ul>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Role']['id'], $this->request->data['Role']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this role?')"));
    ?>
</div>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Role', array('class' => 'well')) ?>
			<h2>Edit Role</h2>

			<?= $this->Form->input('title', array('class' => 'required')) ?>
			<?= $this->Form->input('defaults', array('options' => array(
					'default-member' => 'Default Member', 
					'default-guest' => 'Default Guest'
				),
				'label' => 'Default Settings',
				'empty' => '- choose -'
			))
			?>
			<?php
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

			<?php if ($category == 1): ?>
				</ul>
			<?php endif ?>

		<?= $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>
		<?= $this->Form->hidden('id') ?>

		<br />
		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>
	
	<div class="tab-pane" id="permission-type">
		<?= $this->Form->create('Permission', array('action' => 'add', 'class' => 'well')); ?>
			<h2>Add Permission</h2>

			<?= $this->Form->input('title') ?>
			<?= $this->Form->hidden('role_id', array('value' => $this->request->data['Role']['id'])) ?>

		<br />
		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>

	<div class="tab-pane" id="permission-value">
		<?= $this->Form->create('PermissionValue', array('action' => 'add', 'class' => 'well')) ?>
			<h2>Add Permission Values</h2>

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
		)) ?>
	</div>
</div>