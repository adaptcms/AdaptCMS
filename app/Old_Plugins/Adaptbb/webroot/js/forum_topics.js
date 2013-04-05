$(document).ready(function() {
	$('.cancel-edit-post').live('click', function(e) {
		e.preventDefault();

		var id = $(this).parent().attr('id');
		var form = $('#' + id);

		if (form.prev().parent().hasClass('post-body'))
		{
			form.prev().show();
		}

		form.hide();
	});

	$('.edit-post').live('click', function(e) {
		e.preventDefault();

		var href = $(this).attr('href');

		if ($(this).attr('data-id'))
		{
			var id = $(this).attr('data-id');
		}
		else
		{
			var id = $(this).parent().attr('id');
		}

		var post_id = id.replace('edit_post_', '');
		var form = $(this).parent().parent().parent().parent().find('.EditPost');

		if (form.is(':hidden'))
		{
			if (tinyMCE.selectedInstance.id != 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.EditorManager.execCommand('mceRemoveControl', false, tinyMCE.selectedInstance.id);

				if (tinyMCE.selectedInstance.id != 'ForumPost' + post_id + 'Content')
				{
					tinyMCE.EditorManager.execCommand('mceRemoveControl', false, tinyMCE.selectedInstance.id);
				}
			}

			if (form.prev().length)
			{
				form.prev().hide();
				tinyMCE.activeEditor.setContent(form.prev().html());
			}
		}
		else
		{
			if (tinyMCE.selectedInstance.id == 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.EditorManager.execCommand('mceRemoveControl', false, tinyMCE.selectedInstance.id);
			}

			tinyMCE.execCommand('mceAddControl', false, 'ForumPostContent');
			form.prev().show();
		}

		form.toggle();

		if (form.is(':visible'))
		{
			if (tinyMCE.selectedInstance.id == 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.EditorManager.execCommand('mceRemoveControl', false, tinyMCE.selectedInstance.id);
				tinyMCE.execCommand('mceAddControl', false, 'ForumPost' + post_id + 'Content');
			}
		}

		$('#AddPost').hide();
	});

	$('.reply,.quote').live('click', function(e) {
		e.preventDefault();

		$.each($('.post-content'), function() {
			if ($(this).parent().parent().is(':visible'))
			{
				$(this).parent().parent().find('.cancel-edit-post').trigger('click');
			}
		});

		if (tinyMCE.selectedInstance.id != 'ForumPostContent')
		{
			tinyMCE.EditorManager.execCommand('mceRemoveControl', false, tinyMCE.selectedInstance.id);
			tinyMCE.execCommand('mceAddControl', false, 'ForumPostContent');
		}

		if ($(this).hasClass('quote'))
		{
			var content = $(this).parent().parent().parent().parent().find('.post-body .message').html();
			var user = $(this).parent().parent().parent().find('.user').html();

			if (user)
			{
				var quote = '<blockquote class="quote">' + content + ' <small>originally posted by ' + user + '</small></blockquote><br />';
			} else {
				var quote = '<blockquote class="quote">' + content + ' <small>originally posted by guest</small></blockquote><br />';
			}

			tinyMCE.activeEditor.setContent(quote);
		} else {
			tinyMCE.activeEditor.setContent('');
		}

		$('#AddPost').show();
		$.smoothScroll({scrollTarget: $("#AddPost") });
	});

	$(".EditPost").live('submit', function(e) {
		e.preventDefault();

		message = tinyMCE.activeEditor.getContent();

		if (!message)
		{
			$("#flashMessage.alert-error").html('<strong>Error</strong> Please enter in a message').show();
			$.smoothScroll({scrollTarget: $("#flashMessage.alert-error").prev() });
		}
		else
		{
			$("#flashMessage.alert-error").hide();

			forum_id = $(this).find('.forum_id').val();
			topic_id = $(this).find('.topic_id').val();
			post_id = $(this).find('.post_id').val();
			reload_url = $(this).find('.reload_url').html();

			$.post($(this).attr('action'), 
            	{
            		data:
	            	{
	            		ForumPost:
	            		{
	            			topic_id: topic_id,
	            			content: message,
	            			forum_id: forum_id,
	            			id: post_id
	            		}
	            	}
	            }, function(data) {

				if (data.status)
				{
					$("#posts #post-" + post_id).load(reload_url + ' #posts #post-' + post_id, function() {
						$("#flashMessage.alert-success").html('<strong>Success</strong> ' + data.message).show();
						$.smoothScroll();
						tinyMCE.activeEditor.setContent('');
						setTimeout(function() {
							$("#flashMessage.alert-success").fadeOut(2500);
						}, 3000);
					});
				} else {
					$("#flashMessage.alert-error").html('<strong>Error</strong>  ' + data.message).show();
					$.smoothScroll({scrollTarget: $("#flashMessage.alert-error").prev() });
				}
	        }, 'json');
		}
	});

	$("#AddPost").on('submit', function(e) {
		e.preventDefault();

		message = tinyMCE.activeEditor.getContent();

		if (!message)
		{
			$("#flashMessage.alert-error").html('<strong>Error</strong> Please enter in a message').show();
			$.smoothScroll({scrollTarget: $("#flashMessage.alert-error").prev() });
		} else {
			$("#flashMessage.alert-error").hide();

			forum_id = $("#ForumPostForumId").val();
			topic_id = $("#ForumPostTopicId").val();
			reload_url = $(this).find('.reload_url').html();

			$.post($(this).attr('action'), 
            	{
            		data:
	            	{
	            		ForumPost:
	            		{
	            			topic_id: topic_id,
	            			content: message,
	            			forum_id: forum_id
	            		}
	            	}
	            }, function(data) {

				if (data.status)
				{
					$("#posts").load(reload_url + ' #posts', function() {
						$("#flashMessage.alert-success").html('<strong>Success</strong> ' + data.message).show();
						$.smoothScroll();
						tinyMCE.activeEditor.setContent('');
						setTimeout(function() {
							$("#flashMessage.alert-success").fadeOut(2500);
						}, 3000);
					});
				} else {
					$("#flashMessage.alert-error").html('<strong>Error</strong>  ' + data.message).show();
					$.smoothScroll({scrollTarget: $("#flashMessage.alert-error").prev() });
				}
	        }, 'json');
		}
	});
});