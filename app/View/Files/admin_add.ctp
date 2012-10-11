<script>
$(document).ready(function() {
	$("#FileType").live('change', function() {
		if ($(this).val()) {
			if ($(this).val() == 'upload') {
				$("#file_upload").show();
				$("#file_contents").hide();
			} else {
				$("#file_contents").show();
				$("#file_upload").hide();
				$("#frame_FileContent").css("height", "325px");
			}
			$("#extra").show();
		} else {
			$("#file_upload, #file_contents, #extra").hide();
		}
	});
});
</script>

<h1>Add File</h1>

<?php
    echo $this->Form->create('File', array('type' => 'file', 'class' => 'well'));
    echo $this->Form->input('type', array(
    	'label' => 'How would you like to add this file?',
    	'options' => array(
    		'upload' => 'Upload File (such as images)',
    		'edit' => 'Add File (css, js, etc.)'
    	),
    	'empty' => '- choose -',
    	'required' => true
    ));
?>

<div id="file_upload" style="display: none">
	<?= $this->Form->input('filename', array('type' => 'file', 'class' => 'required')) ?>
    <?= $this->Form->hidden('dir', array('value' => 'uploads/')) ?>
    <?= $this->Form->hidden('mimetype') ?>
    <?= $this->Form->hidden('filesize') ?>
</div>
<div id="file_contents" style="display:none">
    <?php $this->EditArea->editor('FileContent') ?>

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

<div id="extra" style="display: none">
	<?php if (empty($theme)): ?>
	    <?= $this->Form->input('caption') ?>
	<?php else: ?>
		<?= $this->Form->hidden('theme', array('value' => $theme)) ?>
	    <?= $this->Form->input('folder', array(
	    	'class' => 'required',
			'empty' => '- choose -',
			'label' => 'Folder Location',
			'options' => array(
				'css' => 'Css',
				'js' => 'Js',
				'img' => 'Images',
				'other' => 'Other'
			)
		)) ?>
	<?php endif ?>
</div>

<?= $this->Form->end('Submit') ?>