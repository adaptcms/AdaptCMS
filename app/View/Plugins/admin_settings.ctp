<h1><?= $plugin ?> Settings</h1>

<?= $this->Html->link(
    '<i class="icon-chevron-left"></i> Back to Plugins',
    array('action' => 'index'),
    array('class' => 'btn btn-info pull-right admin-edit-options', 'escape' => false
)) ?>
<div class="clearfix"></div>

<?= $this->Form->create('Settings', array('class' => 'well')) ?>

	<?php if (empty($params)): ?>
		Sory, there are no settings for this Plugin
	<?php else: ?>
		<?php foreach($params as $key => $value): ?>
			<?php if (strlen($value) == 1 && is_numeric($value)): ?>
				<?= $this->Form->input($key, array('value' => $value, 'type' => 'checkbox', ($value == 1 ? 'checked' : ''))) ?>
			<?php else: ?>
				<?= $this->Form->input($key, array('value' => $value)) ?>
			<?php endif ?>
		<?php endforeach ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	<?php endif ?>