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
    <?= $this->Form->hidden('dir') ?>
    <?= $this->Form->hidden('mimetype') ?>
    <?= $this->Form->hidden('filesize') ?>
</div>
<div id="file_contents" style="display: none">
    <?php $this->EditArea->editor('FileContent') ?>

    <?= $this->Form->input('content', array(
        'label' => 'File Contents',
        'rows' => 25, 
        'style' => 'width: 90%'
    )) ?>
</div>

<div id="extra" style="display: none">
	<?php if (empty($this->params['pass'][0])): ?>
	    <?= $this->Form->input('caption') ?>
	<?php else: ?>
		<?= $this->Form->hidden('theme_id', array('value' => $this->params['pass'][0])) ?>
	    <?= $this->Form->input('folder', array(
			'empty' => '- choose -',
			'label' => 'Folder Location',
			'options' => array(
				'Css',
				'Js',
				'Images',
				'Other'
			)
		)) ?>
	<?php endif ?>
</div>

<?= $this->Form->end('Submit') ?>