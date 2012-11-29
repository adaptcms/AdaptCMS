<script>
$(document).ready(function() {
	$("#select_all").live('change', function() {
		$("#media-modal .file").attr('checked', true);
		$("#select_none").attr('checked', false);
	});

	$("#select_none").live('change', function() {
		$("#media-modal .file:not(:disabled), #select_all").attr('checked', false);
	});

	$(".btn.btn-danger").live('click', function(e) {
		e.preventDefault();

		$("#sort_by").val('').trigger('change');

		loadAjax('<?= $this->params->here ?>');
	});

	/*
	* Image functions, clicking same as clicking checkbox and hover pops up message regarding this
	*/

	$("#media-modal .file_info img, #selected-images .file_info img").live('click', function() {
		$(this).parent().find('input:checkbox').trigger('click');
	});

	$("#media-modal .file_info img, #selected-images .file_info img").popover({
		trigger: 'hover',
		placement: 'left',
		content: 'Click an image to add/remove it to the media library',
		title: 'Notice',
		delay: { show: 300, hide: 0 }
	});

	/*
	*/

	/*
	* AJAX Sorting
	*/

	$("#sort_by").live('change', function() {
		if ($(this).val()) {
			$("#sort_direction").show();
			$("#sort_direction").next().show();
		} else {
			$("#sort_direction").hide();
			$("#sort_direction").next().hide();
		}
	});

	$("#sort_direction").live('change', function() {
		if ($(this).val()) {
			var text = $("#sort_by option:selected").text();
			var href = $("#sort_by option:selected").val() + $(this).val();

	 		loadAjax(href);
		}
	});

	$("#media-modal .modal-footer .pull-right .btn-primary").live('click', function() {
		getImages();

		$(this).prev().trigger('click');
	});

	/*
	 */

	$("#selected-images .span4.file_info input:checkbox").live('change', function() {
		var id = $(this).parent().parent().attr('id');

		$('#media-modal #' + id).find('input:checkbox').attr('checked', false).attr('disabled', false);
		$('#media-modal #' + id).css('opacity', '1.0');

		$(this).parent().parent().fadeOut(400, function() {
			$(this).remove();
		});
	});

	getImages();
	getImagesDefault();

	/*
	 * AJAX Pagination
	 */

	 $(".pagination ul li a").live('click', function(e) {
	 	e.preventDefault();

	 	if ($(this).attr('href')) {
	 		loadAjax($(this).attr('href'));
	 	}
	 });

	 /*
	  */
});

function getImages()
{
	$("#media-modal .file:checked").each(function(i, val) {
		if (!$(this).attr('disabled')) {
			var id = $(this).parent().parent().attr('id');
			console.log(id);

			if ($("#selected-images #" + id).length == 0) {
				var html = $(this).parent().parent().clone();
				var length = $("#selected-images .file_info").length;

				$("#selected-images").append(html);
				$('#' + id).attr('checked', true);
				$(this).attr('disabled', true);
				$(this).parent().parent().css('opacity', '0.4');
			}
		}
	});
}

function getImagesDefault()
{
	if ($("#selected-images .span4.file_info input:checkbox").length > 0) {
		$("#selected-images .span4.file_info input:checkbox").each(function() {
			var id = $(this).parent().parent().attr('id');

			$('#media-modal #' + id).find('input:checkbox').attr('checked', true).attr('disabled', true);
			$('#media-modal #' + id).css('opacity', '0.4');
		});
	}
}

function loadAjax(href)
{
	$(".modal-body").load(href + ' .modal-body', function() {
		fixPagination();
		getImagesDefault();
	});
}
</script>

<div id="media-modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel">Add Images to Library - {{title}}</h3>
	</div>
	<div class="modal-body">
		<?php if (!empty($images)): ?>
			<?php foreach($images as $key => $row): ?>
				<?php if ($key % 3 === 0 && $key != 0): ?>
					<div class="clearfix"></div><br />
				<?php endif ?>

				<?= $this->element('media_modal_image', array('image' => $row['File'], 'key' => $key)) ?>
			<?php endforeach ?>
		<?php endif ?>

		<div class="clearfix"></div>
		<?= $this->element('admin_pagination') ?>
	</div>
	<div class="modal-footer">
		<div class="pull-left form-inline">
			<?= $this->Form->input('select_all', array('type' => 'checkbox', 'div' => false)) ?>
			<?= $this->Form->input('select_none', array('type' => 'checkbox', 'div' => false)) ?>

			<?= $this->Form->input('sort_by', array(
				'empty' => '- Sort Images -',
				'options' => array(
					$this->params->here.'/sort:filename/direction:' => 'Sort By: File Name',
					$this->params->here.'/sort:filesize/direction:' => 'Sort By: File Size',
					$this->params->here.'/sort:caption/direction:' => 'Sort By: Caption',
					$this->params->here.'/sort:created/direction:' => 'Sort By: Created Date',
					$this->params->here.'/sort:modified/direction:' => 'Sort By: Modified Date'
				),
				'div' => false,
				'label' => false,
				'style' => 'margin-left: 20px'
			)) ?>
			<?= $this->Form->input('sort_direction', array(
				'options' => array(
					'asc' => 'Ascending',
					'desc' => 'Descending'
				),
				'empty' => '- sort direction -',
				'div' => false,
				'label' => false,
				'style' => 'display: none'
			)) ?>
			<?= $this->Form->button('<i class="icon icon-white icon-remove"></i>', array(
					'escape' => false, 
					'class' => 'btn btn-danger',
					'style' => 'display: none',
					'title' => 'Reset Sorting'
			)) ?>
		</div>
		<div class="pull-right">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Save Selected</button>
		</div>
		<div class="clearfix"></div>
	</div>
</div>