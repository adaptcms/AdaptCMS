<?php
if (empty($id)) {
	$id = "media-modal";
} else {
	$id = "media-modal".$id;
}
?>

<?= $this->Html->script('media') ?>

<script>
$(document).ready(function() {
	$("#select_all").live('change', function() {
		$("#<?= $id ?> .file").attr('checked', true);
		$("#select_none").attr('checked', false);
	});

	$("#select_none").live('change', function() {
		$("#<?= $id ?> .file:not(:disabled), #select_all").attr('checked', false);
	});

	$(".btn.btn-danger").live('click', function(e) {
		e.preventDefault();

		$("#sort_by").val('').trigger('change');

		loadAjax('<?= $this->params->here ?>', '<?= $id ?>');
	});

	/*
	* Image functions, clicking same as clicking checkbox and hover pops up message regarding this
	*/

	$("#<?= $id ?> .file_info img, .selected-images .file_info img").live('click', function() {
		$(this).parent().find('input:checkbox').trigger('click');
		$(this).popover('hide');
	});

	$("#<?= $id ?> .file_info img, .selected-images .file_info img").popover({
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

	 		loadAjax(href, '<?= $id ?>');
		}
	});

	$("#<?= $id ?> .modal-footer .pull-right .btn-primary").live('click', function(e) {
		e.preventDefault();
		getImages('<?= $id ?>');

		$(this).prev().trigger('click');
	});

	/*
	 */

	getImages('<?= $id ?>');
	getImagesDefault('<?= $id ?>');

	/*
	 * AJAX Pagination
	 */

	 $(".pagination ul li a").live('click', function(e) {
	 	e.preventDefault();

	 	if ($(this).attr('href')) {
	 		loadAjax($(this).attr('href'), '<?= $id ?>');
	 	}
	 });

	 /*
	  */

	/*
	 * Limit for other components, currently only used with Links Plugin
	 * Call this element with a limit option
	 */

	<?php if (!empty($limit)): ?>
		$("#<?= $id ?>").find('#media-modal-current-limit').val(<?= $limit ?>);
		var count = $('#<?= $id ?> .modal-body .thumbnails .file_info.span4 input:checked').length;

		if (count == <?= $limit ?>) {
			disabledImages('not-checked', '<?= $id ?>');
		}

		$("#<?= $id ?>").on('change', '.modal-body .thumbnails .file_info.span4 input:checkbox', function() {
			var id = $(this).attr('id');
			var checked = $(this).attr('checked');
			var count = $('#<?= $id ?> .modal-body .thumbnails .file_info.span4 input:checked').length;

			if (count == <?= $limit ?>) {
				if (checked) {
					disabledImages('not-checked', '<?= $id ?>');
				}
			} else if (!checked) {
				disabledImages('enable', '<?= $id ?>');
			}
		});
	<?php endif ?>
});

function disabledImages(type, id)
{
	// console.log(type);
	if (type == 'disable') {
		$('#' + id).find('input:disabled').parent().parent().css('opacity', '1.0');
		$('#' + id).find('input:disabled').attr('disabled', false);
	} else if (type == 'enable') {
		$('#' + id).find('input:disabled').parent().parent().css('opacity', '1.0');
		$('#' + id).find('input:disabled').attr('disabled', false);
	} else if (type == 'not-checked') {
		$('#' + id).find('input:checkbox:not(:checked)').attr('disabled', true);
		$('#' + id).find('input:checkbox:not(:checked)').parent().parent().css('opacity', '0.4');
	}
}

function getImages(modal_id)
{
	$("#" + modal_id + " .file:checked").each(function(i, val) {
		if (!$(this).attr('disabled')) {
			var id = $(this).parent().parent().attr('id');

			if ($(".selected-images #" + modal_id).length == 0) {
				var html = $(this).parent().parent().clone();
				var length = $(".selected-images .file_info").length;
				var modal = $("#" + modal_id).find("#media-modal-current-id");

				if (modal.length == 0 || !modal.val()) {
					if (i % 3 === 0 && i != 0)
					{
						// $(".selected-images").append('<div class="clearfix"></div>');
					}
					$(".selected-images").append(html);
				} else {
					var current_id = modal.val();
					var file_id = id.split('-');

					$("#" + current_id).parent().find('.selected-images').append(html);
					$("#" + current_id).val(file_id[1]);
					$("#" + current_id).next().val(file_id[1]);
				}
				$('#' + modal_id).attr('checked', true);
				$(this).attr('disabled', true);
				$(this).parent().parent().css('opacity', '0.4');
			}
		}
	});
}

function getImagesDefault(modal_id)
{
	$("a[href='#" + modal_id + "']").parent().find('.selected-images').each(function() {
		if ($(this).find(".span4.file_info input:checkbox").length > 0) {
			$(this).find(".span4.file_info input:checkbox").each(function() {
				var id = $(this).parent().parent().attr('id');

				$('#' + modal_id + ' #' + id).find('input:checkbox').attr('checked', true).attr('disabled', true);
				$('#' + modal_id + ' #' + id).css('opacity', '0.4');
			});
		}
	});
}

function loadAjax(href, id)
{
    var modal = $("#" + id).find(".modal-body");

    modal.load(href + '?unique=' + Math.round(Math.random()*10000) + ' #' + id, function() {
        modal.replaceWith($(this).find('.modal-body'));
		fixPagination();
		getImagesDefault(id);
	});
}
</script>

<div id="<?= $id ?>" class="modal hide fade">
	<div class="modal-header">
		<i class="icon-remove icon-large close" data-dismiss="modal" aria-hidden="true"></i>
	    <h3>Add Images to Library</h3>
	</div>
	<div class="modal-body">
		<?php if (!empty($images)): ?>
			<ul class="thumbnails">
				<?php foreach($images as $key => $row): ?>
					<?php if ($key % 3 === 0 && $key != 0): ?>
						<div class="clearfix"></div><br />
					<?php endif ?>

					<?= $this->element('media_modal_image', array(
							'image' => $row['File'], 
							'key' => $key,
							'limit' => (!empty($limit) ? $limit : ''),
							'modal' => true
					)) ?>
				<?php endforeach ?>
			</ul>

			<div class="clearfix"></div>
			<?= $this->element('admin_pagination') ?>
		<?php else: ?>
			No Images to Select
		<?php endif ?>
	</div>
	<div class="modal-footer">
		<div class="pull-left form-inline">
			<?php if (empty($limit)): ?>
				<?= $this->Form->input('select_all', array('type' => 'checkbox', 'div' => false)) ?>
				<?= $this->Form->input('select_none', array('type' => 'checkbox', 'div' => false)) ?>
			<?php else: ?>
				<?= $this->Form->hidden('current_id', array('id' => 'media-modal-current-id')) ?>
				<?= $this->Form->hidden('current_limit', array('id' => 'media-modal-current-limit')) ?>
			<?php endif ?>

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
				'style' => 'margin-left: 20px',
				'class' => 'btn-warning btn'
			)) ?>
			<?= $this->Form->input('sort_direction', array(
				'options' => array(
					'asc' => 'Ascending',
					'desc' => 'Descending'
				),
				'empty' => '- sort direction -',
				'div' => false,
				'label' => false,
				'style' => 'display: none',
				'class' => 'btn-warning btn'
			)) ?>
			<?= $this->Form->button('<i class="icon icon-white icon-remove"></i>', array(
					'escape' => false, 
					'class' => 'btn btn-danger',
					'style' => 'display: none',
					'title' => 'Reset Sorting'
			)) ?>
		</div>
		<div class="pull-right">
			<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Save Selected</button>
		</div>
		<div class="clearfix"></div>
	</div>
</div>