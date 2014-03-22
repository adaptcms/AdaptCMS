$(document).ready(function() {
	$("form.PostComment .submit-comment").live('click', function() {
        var form = $(this).parent();

		if (typeof tinyMCE == 'undefined')
		{
			var comments_text = form.find('textarea').val();
		} else {
			var comments_text = tinyMCE.activeEditor.getContent();
            form.find('textarea').val(comments_text);
		}

        form.valid();

		if (comments_text && form.valid()) {
            getBlockUI();

            var fields_data = form.find('input,select,textarea').serialize();

            $.post($("#webroot").text() + "ajax/comments/post/", fields_data, function(response) {
                var data = $.parseJSON(response.data);

                $.unblockUI();

        		if ($("#flashMessage").length > 0) {
        			$("#flashMessage").remove();
        		}

            	if (data.status) {
					if (typeof tinyMCE != 'undefined')
					{
						tinyMCE.activeEditor.setContent('');
					}
                    else
                    {
                        $(form).find('textarea').val('');
                    }

	            	reloadComments(data.id);
            	}

            	$('.PostComment:first').prepend(data.message);

            	if (data.status) {
            		$.smoothScroll({scrollTarget: $('#flashMessage').prev().prev()});
            		$("#flashMessage").fadeOut(4000);
            	}

            	if (data.message.search('Captcha') != -1 || data.status) {
            		if ($(form).find('input.captcha').parent().length > 0) {
            			reloadCaptcha($(form).find('input.captcha').parent().attr('id'));
            			$.smoothScroll({scrollTarget: $('#flashMessage').prev().prev()});
            		}
            	}
            }, 'json');
		}
	});

	$("form.PostComment button[type='cancel']").live('click', function(e) {
		e.preventDefault();

		$(this).parent().fadeOut(1);
	});

	$("#comments").on('click', '.comment a[href=#reply]', function(e) {
		e.preventDefault();

		var id = $(this).parent().parent().attr('id').split('-');

		if ($(this).parent().parent().next().find('textarea').length == 0) {
			$("#comments").find('form').fadeOut(300, function() {
				$(this).remove();
			});

			if (typeof tinyMCE != 'undefined')
			{
				tinyMCE.execCommand('mceRemoveEditor', false, 'CommentCommentText');
			}

			var form = $('.PostComment:first').clone().addClass('span11');
			var cancel_button = "<button type='cancel' class='btn'>Cancel</button>";

			var parent_id = "<input type='hidden' id='CommentParentId' name='data[Comment][parent_id]' value='" + id[1] + "'>";

			if ($(form).find('#flashMessage').length > 0) {
				$(form).find('#flashMessage').remove();
			}

			$(cancel_button).insertAfter($(form).find('button'));

			$(parent_id).insertAfter($(form).find('textarea').attr('id', 'CommentCommentText' + id[1]));

			if (typeof tinyMCE != 'undefined')
			{
				tinyMCE.execCommand('mceAddEditor', false, 'CommentCommentText');
				tinyMCE.execCommand('mceAddEditor', false, 'CommentCommentText' + id[1]);
			}

			if ($("#captcha").length > 0) {
				$(form).find('#captcha').attr('id', 'captcha_' + id[1]);
				reloadCaptcha(id[1]);
			}

			$(form).attr('id', 'CommentViewForm' + id[1]);

			$(form).insertAfter($(this).parent().parent());

			if (typeof tinyMCE != 'undefined')
			{
				tinyMCE.execCommand('mceAddEditor', false, 'CommentCommentText' + id[1]);
			}
		} else {
			// $(this).parent().parent().next().toggle();
			$(this).parent().parent().next().fadeOut(500, function() {
				$(this).remove();
			});
		}

        $.smoothScroll({scrollTarget: form.next()});
	});
});

function reloadComments(id)
{
	var form_url = $('.PostComment').attr('action');

	$("#comments").load(form_url + '?unique=' + Math.round(Math.random()*10000) + ' #comments', function() {
		if (id) {
			$("#comments").find('#comment-' + id + ' .body').prepend('<span class="label label-important pull-right">New!</span>');
		}
	});
}