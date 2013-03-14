$(document).ready(function() {
	$("form.PostComment button[type='submit']").live('click', function(e) {
		e.preventDefault();

		if (typeof tinyMCE == 'undefined')
		{
			var comments_text = $(this).parent().find('textarea').val();
		} else {
			var comments_text = tinyMCE.activeEditor.getContent();
		}

		var article_id = $(this).parent().find('#CommentArticleId').val();
		if ($(this).parent().find('#CommentParentId').length > 0) {
			var parent_id = $(this).parent().find('#CommentParentId').val();
		} else {
			var parent_id = '';
		}
		var form = $(this).parent();

		var captcha = $(this).parent().find('input.captcha').val();

		if (comments_text) {
			var comment_count = $("#comments .comment").length;

            $.post($("#webroot").text() + "ajax/comments/post/", 
            	{
            		data:
	            	{
	            		Comment:
	            		{
	            			comment_text: comments_text,
	            			article_id: article_id,
	            			parent_id: parent_id,
	            			captcha: captcha
	            		}
	            	}
	            }, function(return_data) {
	            var data = $.parseJSON(return_data);

        		if ($("#flashMessage").length > 0) {
        			$("#flashMessage").remove();
        		}

            	if (data.status) {
					if (typeof tinyMCE == 'undefined')
					{
						var comments_text = $(form).find('textarea').val('');
					} else {
						var comments_text = tinyMCE.activeEditor.setContent('');
					}

	            	reloadComments(comment_count, data.id);
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
            });
		}
	});

	$(".refresh").live('click', function(e) {
		e.preventDefault();

		reloadCaptcha($(this).parent().parent().attr('id'));
		$(this).blur(); 
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
				tinyMCE.EditorManager.execCommand('mceRemoveControl', false, 'CommentCommentText');
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
				tinyMCE.execCommand('mceAddControl', false, 'CommentCommentText');
				tinyMCE.execCommand('mceAddControl', false, 'CommentCommentText' + id[1]);
			}

			if ($("#captcha").length > 0) {
				$(form).find('#captcha').attr('id', 'captcha_' + id[1]);
				reloadCaptcha(id[1]);
			}

			$(form).attr('id', 'CommentViewForm' + id[1]);

			$(form).insertAfter($(this).parent().parent());

			if (typeof tinyMCE != 'undefined')
			{
				tinyMCE.execCommand('mceAddControl', false, 'CommentCommentText' + id[1]);
			}
		} else {
			// $(this).parent().parent().next().toggle();
			$(this).parent().parent().next().fadeOut(500, function() {
				$(this).remove();
			});
		}
	});
});

function reloadCaptcha(id)
{
	if (id && id != 'captcha') {
		var div_id = 'captcha_' + id.replace('captcha_', '');
	} else {
		var div_id = 'captcha';
	}

	$('#' + div_id).find('#siimage').attr('src', $('#webroot').text() + 'libraries/captcha/securimage_show.php?sid=' + Math.random());
	$('#' + div_id).find('.captcha').val('').focus();
}

function reloadComments(comment_count, id)
{
	var comment_count = $("#comments .comment").length;
	var form_url = $('.PostComment').attr('action');

	$("#comments").load(form_url + ' #comments', function() {
		if (id) {
			$("#comments").find('#comment-' + id + ' .header h5').append('<span class="label label-important pull-right">New!</span>');
		}
	});
}