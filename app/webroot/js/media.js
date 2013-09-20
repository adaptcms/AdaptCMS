$(document).ready(function() {
	$(".selected-images .span4.file_info input:checkbox").live('change', function() {
		var id = $(this).parent().parent().attr('id');
		var modal_id = $(this).parent().parent().parent().parent().find('.media-modal');
		if (modal_id.length == 0 || !modal_id.attr('href')) {
			var modal_id = '#media-modal';
		} else {
			var modal_id = modal_id.attr('href');
		}

		$(modal_id + ' #' + id).find('input:checkbox').attr('checked', false).attr('disabled', false);
		$(modal_id + ' #' + id).css('opacity', '1.0');

		var limit = $(modal_id).find('#media-modal-current-limit').val();
		var count = $(modal_id + ' .modal-body .thumbnails .file_info.span4 input:checked').length;
		var checked = $(modal_id + ' .modal-body .thumbnails .file_info.span4 input:checkbox').attr('checked');

		if (limit && count == limit) {
			if (checked) {
				disabledImages('not-checked', modal_id.replace('#', ''));
			}
		} else if (limit && !checked) {
			disabledImages('enable', modal_id.replace('#', ''));
		}

		$(this).parent().parent().fadeOut(400, function() {
			$(this).remove();
		});
	});

	$('.media-modal').on('click', function() {
		var id = $(this).parent().find('input:hidden').attr('id');
		var href = $(this).attr('href');

		if (id) {
			$(href).find("#media-modal-current-id").val(id);
		}
	});
});