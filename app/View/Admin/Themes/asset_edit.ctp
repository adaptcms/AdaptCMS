<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Themes', array('action' => 'index', 'controller' => 'templates')) ?>

<?php if (!empty($plugin)): ?>
	<?php $this->Html->addCrumb($plugin . ' Files', array('controller' => 'plugins', 'action' => 'assets', $plugin)) ?>
<?php else: ?>
	<?php $this->Html->addCrumb($theme . ' Theme', array('action' => 'edit', $theme)) ?>
<?php endif ?>

<?php $this->Html->addCrumb('Edit File', null) ?>

<?= $this->Form->create('Asset', array('class' => 'well admin-validate')) ?>
	<h2>Edit Plugin/Theme File</h2>

	<?php if (!empty($file_contents)): ?>
	    <?= $this->Form->input('filename', array('value' => $ext['basename'], 'label' => 'File Name')) ?>

	    <?php $this->CodeMirror->editor('AssetContent') ?>

	    <?= $this->Form->input('content', array(
	        'label' => 'File Contents',
	        'rows' => 25, 
	        'style' => 'width:90%', 
	        'value' => $file_contents
	    )) ?>
	<?php else: ?>
	    <?= $this->Form->input('filename', array('value' => $ext['basename'], 'label' => 'File Name')) ?>
	<?php endif ?>

	<?= $this->Form->hidden('old_filename', array('value' => $ext['basename'])) ?>

	<?php if (!empty($plugin)): ?>
    	<?= $this->Form->hidden('plugin', array('value' => $plugin)) ?>
    <?php else: ?>
    	<?= $this->Form->hidden('theme', array('value' => $theme)) ?>
    <?php endif ?>

    <?= $this->Form->hidden('dir', array('value' => $dir)) ?>

<?php if ($writable == 1): ?>
    <?= $this->Form->end(array(
        'label' => 'Submit',
        'class' => 'btn btn-primary'
    )) ?>
<?php else: ?>
    <?= $this->Element('writable_notice', array(
        'type' => 'template',
        'file' => $writable
    )) ?>
    <?= $this->Form->end() ?>
<?php endif ?>