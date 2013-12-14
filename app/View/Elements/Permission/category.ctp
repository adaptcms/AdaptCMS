<?= $this->Form->checkbox($key . '.Permission.status', array(
	'default' => $role[$action]['value'],
	'value' => 1
)) ?>
<?php if (!empty($role[$action]['permission_id'])): ?>
	<?= $this->Form->hidden($key . '.Permission.id', array(
		'value' => $role[$action]['permission_id']
	)) ?>
<?php endif ?>
<?= $this->Form->hidden($key . '.Permission.controller', array('value' => 'articles')) ?>
<?= $this->Form->hidden($key . '.Permission.action', array('value' => $action)) ?>
<?= $this->Form->hidden($key . '.Permission.role_id', array('value' => $role['id'])) ?>