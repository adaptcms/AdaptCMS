<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Import Folder', null) ?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<?= $this->Form->create('File', array('type' => 'file', 'class' => 'well admin-validate')) ?>
	<h2>Import Folder</h2>

	<?= $this->Form->input('upload_all', array(
		'label' => 'Upload all files? Not just images?',
		'type' => 'checkbox'
	)) ?>

	<?= $this->Form->input('path', array(
		'empty' => '- pick folder -',
		'options' => $folders,
		'label' => 'Folder',
		'class' => 'required span6 col-lg-6'
	)) ?>

	<small class="span6 no-marg-left col-lg-6">
		Note: To get a folder to show up, please upload the folder via FTP/other method to the folder:
		<strong><?= WWW_ROOT . 'folder_upload' . DS ?></strong>
	</small>
	<div class="clearfix"></div>

	<h4 class="image-filters">More Options</h4>

	<?= $this->Form->input('watermark', array('type' => 'checkbox')) ?>
	<?= $this->Form->input('zoom', array(
		'options' => $zoom_levels,
		'label' => 'Thumbnail Crop Level'
	)) ?>
	<div class="clearfix"></div>
	<?= $this->Form->input('random_filename', array('type' => 'checkbox')) ?>

	<h4 class="image-filters">Media Libraries</h4>

	<div class="media-libraries">
		<?= $this->Form->input('library', array(
			'div' => false,
			'empty' => '- add library -',
			'options' => $media_list
		)) ?>
		<?= $this->Form->button('Add', array(
			'class' => 'btn btn-info add-media',
			'type' => 'button'
		)) ?>
	</div>
	<div class="media_libraries"></div>
	<div class="clearfix media"></div>

	<?= $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
<?= $this->Form->end() ?>