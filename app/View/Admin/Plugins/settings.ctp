<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Plugin Settings', null) ?>

<h1><?= $plugin ?> Settings</h1>

<?= $this->Html->link(
    '<i class="icon-list icon-white"></i> Back to Plugins',
    array('action' => 'index'),
    array('class' => 'btn btn-info pull-right admin-edit-options', 'escape' => false)
) ?>
<div class="clearfix"></div>

<?= $this->Form->create('Settings', array('class' => 'well')) ?>

	<?php if (empty($params)): ?>
		Sory, there are no settings for this Plugin
	<?php else: ?>
		<?php foreach($params as $key => $value): ?>
			<?php if (strlen($value) == 1 && is_numeric($value) && in_array($value, array(0, 1)) || is_bool($value)): ?>
				<?= $this->Form->input($key, array('value' => $value, 'type' => 'checkbox', ($value == 1 ? 'checked' : ''))) ?>
            <?php elseif(strlen($value) > 25 && strstr($value, ' ')): ?>
                <?= $this->Form->input($key, array(
                    'value' => $value,
                    'type' => 'textarea',
                    'class' => 'span4'
                )) ?>
			<?php else: ?>
				<?= $this->Form->input($key, array('value' => $value, 'type' => 'text')) ?>
			<?php endif ?>
		<?php endforeach ?>

        <?php if ($writable == 1): ?>
            <?= $this->Form->submit('Submit', array(
                'class' => 'btn btn-primary'
            )) ?>
        <?php else: ?>
            <?= $this->Element('writable_notice', array(
                'type' => 'configuration',
                'file' => $writable,
                'sensitive' => true
            )) ?>
        <?php endif ?>
	<?php endif ?>
	
<?= $this->Form->end() ?>