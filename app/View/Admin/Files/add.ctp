<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add File', null) ?>

<script>
$(document).ready(function() {
	$("#FileType").live('change', function() {
		if ($(this).val()) {
			if ($(this).val() == 'upload') {
				$("#file_upload,#extra,#libraries").show();
				$("#file_contents").hide();
			} else {
				$("#file_contents").show();
				$("#file_upload,#extra,#libraries").hide();
				$("#frame_FileContent").css("height", "325px");
			}
		} else {
			$("#file_upload, #file_contents, #extra, #libraries").hide();
		}
	});

	<?php if (!empty($this->request->named['multiple'])): ?>
		$("#file_upload,#extra").show();
		$("#file-type").hide();
		var file_div = $("#file-0").clone().html();
		$("#file-0 .remove").hide();

		$(".well").on('click', '.add-file', function(e) {
			e.preventDefault();

			var length = Number($(".upload-file").length);
			var h4 = length;
			var file_divs = '<div class="upload-file col-lg-4 no-pad-l" id="file-0">' + file_div + '</div>';
			var contents = file_divs.replace(/0/g, length).replace('#0', '#' + h4);
			var contents = contents.replace('File #1', 'File #' + (length + 1));
			var contents = contents.replace('FileWatermark_" value="' + length, 'FileWatermark_" value="0');
			var contents = contents.replace('RandomFilename_" value="' + length, 'RandomFilename_" value="0');

			$(contents).insertAfter($(".upload-file:last"));

			var options = $('.upload-file:first .media-libraries select option').clone();
            var library = $(".upload-file:last .media-libraries");

			library.find("select option").replaceWith(options);
            library.find("select").val('');

			toggleRemove();
		});

		$(".upload-file .remove").live('click', function(e) {
			e.preventDefault();

			$(this).parent().remove();

			toggleRemove();
		});
	<?php endif ?>
});

function toggleRemove()
{
	$(".upload-file:last .remove").show();
	$(".upload-file:not(:last),.upload-file:first").find('.remove').hide();
}
</script>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<?php if (!empty($this->request->named['multiple'])): ?>
	<h1>Upload Files</h1>
<?php else: ?>
	<h1>Add File</h1>
<?php endif ?>

<?= $this->Form->create('File', array('type' => 'file', 'class' => 'well admin-validate')) ?>
	<?= $this->Form->input('type', array(
    	'label' => 'How would you like to add this file?',
    	'options' => array(
    		'upload' => 'Upload File (such as images)',
    		'edit' => 'Add File (css, js, etc.)'
    	),
    	'empty' => '- choose -',
    	'required' => (!empty($this->request->named['multiple']) ? false : true),
    	'div' => array(
    		'id' => 'file-type'
    	)
    )) ?>

	<?php if (empty($this->request->named['multiple'])): ?>
		<div id="file_upload" style="display: none">
			<?= $this->Form->input('0.File.filename', array('type' => 'file', 'class' => 'required')) ?>
		    <?= $this->Form->hidden('0.File.dir', array('value' => 'uploads/')) ?>
		    <?= $this->Form->hidden('0.File.mimetype') ?>
		    <?= $this->Form->hidden('0.File.filesize') ?>
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

			<?= $this->Form->input('caption') ?>

			<?= $this->Form->hidden('dir', array('value' => 'uploads/')) ?>
		</div>

		<div id="extra" style="display: none">
		    <?= $this->Form->input('0.File.caption') ?>

		    <h4 class="image-filters">Image Filters</h4>

		    <?= $this->Form->input('0.File.watermark', array('type' => 'checkbox')) ?>
		    <?= $this->Form->input('0.File.zoom', array(
			    'options' => $zoom_levels,
			    'label' => 'Thumbnail Crop Level'
		    )) ?>

	        <div class="col-lg-5 no-pad-l">
	            <?= $this->Form->input('resize_width', array('class' => '', 'div' => array('class' => 'pull-left'))) ?>
	            <?= $this->Form->input('resize_height', array('class' => '', 'div' => array('class' => 'pull-right'))) ?>
	        </div>
	        <div class="clearfix"></div>

		    <?= $this->Form->input('0.File.random_filename', array('type' => 'checkbox')) ?>
		</div>

		<div id="libraries" class="clearfix" style="display: none">
			<h4 class="image-filters">Media Libraries</h4>

			<div class="media-libraries input-group col-lg-5" style="margin-bottom: 9px;">
				<?= $this->Form->label('library', 'Library') ?>
				<div class="clearfix"></div>

				<?= $this->Form->input('library', array(
					'div' => false,
					'label' => false,
					'class' => 'form-control form-control-inline',
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

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	<?php else: ?>
		<div class="upload-file col-lg-4 no-pad-l" id="file-0">
			<?= $this->Form->button('<i class="fa fa-times"></i>', array(
				'class' => 'btn btn-danger pull-right remove', 
				'escape' => false
			)) ?>

			<h2>File #1</h2>

			<?= $this->Form->input('0.File.filename', array('type' => 'file', 'class' => 'required')) ?>
		    <?= $this->Form->hidden('0.File.dir', array('value' => 'uploads/')) ?>
		    <?= $this->Form->hidden('0.File.mimetype') ?>
		    <?= $this->Form->hidden('0.File.filesize') ?>

		    <?= $this->Form->input('0.File.caption', array('class' => 'form-control')) ?>

		    <h4 class="image-filters">Image Filters</h4>

		    <?= $this->Form->input('0.File.watermark', array('type' => 'checkbox')) ?>
			<?= $this->Form->input('0.File.zoom', array(
				'options' => $zoom_levels,
				'class' => 'form-control',
				'label' => 'Thumbnail Crop Level'
			)) ?>

			<div class="col-lg-11 no-pad-l">
			    <?= $this->Form->input('0.File.resize_width', array(
				    'class' => 'form-control col-xs-7',
				    'div' => array('class' => 'pull-left col-lg-5 no-pad-l')
			    )) ?>
			    <?= $this->Form->input('0.File.resize_height', array(
				    'class' => 'form-control col-xs-7',
				    'div' => array('class' => 'pull-right col-lg-5 no-pad-l')
			    )) ?>
			</div>
		    <div class="clearfix"></div>
		    <?= $this->Form->input('0.File.random_filename', array('type' => 'checkbox')) ?>

		    <h4 class="image-filters">Media Libraries</h4>

		    <div class="media-libraries input-group col-lg-11 no-pad-l">
			    <?= $this->Form->label('0.File.library', 'Library') ?>
			    <div class="clearfix"></div>

		        <?= $this->Form->input('0.File.library', array(
		            'div' => false,
			        'label' => false,
			        'class' => 'form-control form-control-inline',
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
		<div class="btn-group" style="margin-top: 10px;">
			<?php if (!empty($this->request->named['multiple'])): ?>
				<?= $this->Form->button('Upload Another File', array('class' => 'add-file btn btn-danger')) ?>
			<?php endif ?>

			<?= $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
		</div>

	<?= $this->Form->end() ?>
<?php endif ?>