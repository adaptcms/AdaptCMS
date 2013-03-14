<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add File', null) ?>

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

	<?php if (!empty($this->params->named['multiple'])): ?>
		$("#file_upload,#extra").show();
		$("#file-type").hide();
		var file_div = $("#file-1").clone().html();
		$("#file-1 .remove").hide();

		$(".well").on('click', '.add-file', function(e) {
			e.preventDefault();

			var length = Number($(".upload-file").length) + 1;
			var h4 = length;
			var file_divs = '<div class="upload-file span4 no-marg-left" id="file-1">' + file_div + '</div>';
			var contents = file_divs.replace(/1/g, length).replace('#1', '#' + h4);

			$(contents).insertAfter($(".upload-file:last"));

			var options = $('.upload-file:first .media-libraries select option').clone();
			$(".upload-file:last .media-libraries select option").replaceWith(options);

			toggleRemove();
		});

		$(".upload-file .remove").live('click', function(e) {
			e.preventDefault();

			$(this).parent().remove();

			toggleRemove();
		});
	<?php endif ?>

    $(".add-media").live('click', function() {
        var val = $(this).parent().find(":selected");

        if (val.val()) {
            var random = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
            var id = $(this).prev().attr('id').charAt(0);

            if ($(this).parent().next().find("input[value='" + val.val() + "']").length == 0) {
            	$(this).parent().find('.error').remove();
                $(this).parent().next().prepend('<div id="data-' + random + '"><span class="label label-info">' + val.text() + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="' + id + '.Media[]" name="data[' + id + '][Media][]" value="' + val.val() + '"></div>');
            } else {
            	$(this).parent().next().prepend('<label class="error">Library already added!</label>');
            }
        }
    });
});

function toggleRemove()
{
	$(".upload-file:last .remove").show();
	$(".upload-file:not(:last) .remove,.upload-file:first .remove").hide();
}
</script>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<?php if (!empty($this->params->named['multiple'])): ?>
	<h1>Upload Files</h1>
<?php else: ?>
	<h1>Add File</h1>
<?php endif ?>

<?php
    echo $this->Form->create('File', array('type' => 'file', 'class' => 'well admin-validate'));
    echo $this->Form->input('type', array(
    	'label' => 'How would you like to add this file?',
    	'options' => array(
    		'upload' => 'Upload File (such as images)',
    		'edit' => 'Add File (css, js, etc.)'
    	),
    	'empty' => '- choose -',
    	'required' => (!empty($this->params->named['multiple']) ? false : true),
    	'div' => array(
    		'id' => 'file-type'
    	)
    ));
?>

<?php if (empty($this->params->named['multiple'])): ?>
	<div id="file_upload" style="display: none">
		<?= $this->Form->input('1.File.filename', array('type' => 'file', 'class' => 'required')) ?>
	    <?= $this->Form->hidden('1.File.dir', array('value' => 'uploads/')) ?>
	    <?= $this->Form->hidden('1.File.mimetype') ?>
	    <?= $this->Form->hidden('1.File.filesize') ?>
	</div>
	<div class="clearfix"></div>

	<div id="file_contents" style="display:none">
	    <?php $this->CodeMirror->editor('FileContent') ?>

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
		    <?= $this->Form->input('1.File.caption') ?>

		    <h4 class="image-filters">Image Filters</h4>

		    <?= $this->Form->input('1.File.watermark', array('type' => 'checkbox')) ?>
		    <?= $this->Form->input('1.File.resize_width', array('class' => 'span2 pull-left')) ?>
		    <?= $this->Form->input('1.File.resize_height', array('class' => 'span2 pull-right')) ?>
		    <?= $this->Form->input('1.File.random_filename', array('type' => 'checkbox')) ?>
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

	<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn btn-primary'
	)) ?>
<?php else: ?>
	<div class="upload-file span4 no-marg-left" id="file-1">
		<?= $this->Form->button('<i class="icon icon-remove icon-white"></i>', array(
			'class' => 'btn btn-danger pull-right remove', 
			'escape' => false
		)) ?>

		<h2>File #1</h2>

		<?= $this->Form->input('1.File.filename', array('type' => 'file', 'class' => 'required')) ?>
	    <?= $this->Form->hidden('1.File.dir', array('value' => 'uploads/')) ?>
	    <?= $this->Form->hidden('1.File.mimetype') ?>
	    <?= $this->Form->hidden('1.File.filesize') ?>

	    <?= $this->Form->input('1.File.caption') ?>

	    <h4 class="image-filters">Image Filters</h4>

	    <?= $this->Form->input('1.File.watermark', array('type' => 'checkbox')) ?>
	    <?= $this->Form->input('1.File.resize_width', array('div' => array('class' => 'span4 pull-left'))) ?>
	    <?= $this->Form->input('1.File.resize_height', array('div' => array('class' => 'span4 pull-right'))) ?>
	    <div class="clearfix"></div>
	    <?= $this->Form->input('1.File.random_filename', array('type' => 'checkbox')) ?>

	    <h4 class="image-filters">Media Libraries</h4>

	    <div class="media-libraries">
	        <?= $this->Form->input('1.File.library', array(
	            'div' => false, 
	            'style' => 'margin-bottom: 0',
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
	</div>

	<div class="clearfix"></div>
	<div class="btn-group">
		<?php if (!empty($this->params->named['multiple'])): ?>
			<?= $this->Form->button('Upload Another File', array('class' => 'add-file btn btn-danger')) ?>
		<?php endif ?>

		<?= $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
	</div>

	<?= $this->Form->end() ?>
<?php endif ?>