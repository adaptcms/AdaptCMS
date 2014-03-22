<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Themes', array('action' => 'index', 'controller' => 'templates')) ?>

<?php if (!empty($plugin)): ?>
	<?php $this->Html->addCrumb($plugin . ' Files', array('controller' => 'plugins', 'action' => 'assets', $plugin)) ?>
<?php else: ?>
	<?php $this->Html->addCrumb($theme . ' Theme', array('action' => 'edit', $theme)) ?>
<?php endif ?>

<?php $this->Html->addCrumb('Add File', null) ?>

<?php $this->CodeMirror->editor('AssetContent') ?>

<script>
$(document).ready(function() {
	$("#AssetType").live('change', function() {
		if ($(this).val()) {
			if ($(this).val() == 'upload') {
				$("#file_upload").show();
				$("#file_contents").hide();
			} else {
				$("#file_contents").show();
				$("#file_upload").hide();
				$("#frame_AssetContent").css("height", "325px");
			}
			$("#extra").show();
		} else {
			$("#file_upload, #file_contents, #extra").hide();
		}
	});
});
</script>

<?= $this->Form->create('Asset', array('class' => 'well admin-validate', 'type' => 'file')) ?>
	<h2>Add Plugin/Theme File</h2>

	<?= $this->Form->input('type', array(
    	'label' => 'How would you like to add this file?',
    	'options' => array(
    		'upload' => 'Upload File (such as images)',
    		'edit' => 'Add File (css, js, etc.)'
    	),
    	'empty' => '- choose -',
    	'div' => array(
    		'id' => 'file-type'
    	)
    )) ?>

	<div id="file_upload" style="display: none">
		<?= $this->Form->input('filename', array('type' => 'file', 'class' => 'required')) ?>
	</div>
	<div class="clearfix"></div>

	<div id="file_contents" style="display:none">
	    <?= $this->Form->input('content', array(
	        'label' => 'File Contents',
	        'rows' => 20, 
	        'style' => 'width: 90%'
	    )) ?>

		<?= $this->Form->input('file_extension', array(
	    	'class' => 'required',
			'empty' => '- choose -',
			'options' => $file_types
		)) ?>

		<?= $this->Form->input('file_name', array(
	    	'class' => 'required'
		)) ?>
	</div>

	<div id="extra" style="display:none">
	    <?= $this->Form->input('folder', array(
	    	'class' => 'required',
			'label' => 'Folder Location',
			'options' => array(
				'css/' => 'Css',
				'js/' => 'Js',
				'img/' => 'Images',
				'/' => 'Other'
			)
		)) ?>
	</div>

	<?php if (!empty($plugin)): ?>
    	<?= $this->Form->hidden('plugin', array('value' => $plugin)) ?>
    <?php else: ?>
    	<?= $this->Form->hidden('theme', array('value' => $theme)) ?>
    <?php endif ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>