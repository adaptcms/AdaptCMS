$(document).ready(function() {
	$('.cancel-edit-post').live('click', function(e) {
		e.preventDefault();

		var id = $(this).parent().attr('id');
		var form = $('#' + id);

		if (form.prev().parent().hasClass('post-body'))
		{
			form.parent().find('.message,.signature').show();
		}

		form.hide();
	});

	$('.edit-post').live('click', function(e) {
		e.preventDefault();

        $.each($('.post-content'), function() {
            if ($(this).parent().parent().is(':visible'))
            {
                $(this).parent().parent().find('.cancel-edit-post').trigger('click');
            }
        });

		var href = $(this).attr('href');
        var id = $(this).attr('data-id') ? $(this).attr('data-id') : $(this).parent().attr('id');

		var post_id = id.replace('edit_post_', '');
		var form = $(this).parent().parent().parent().parent().find('.EditPost');

		if (form.is(':hidden'))
		{
			if (tinyMCE.activeEditor && tinyMCE.activeEditor.id != 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.execCommand('mceRemoveEditor', false, tinyMCE.activeEditor.id);
			}
		}
		else
		{
			if (tinyMCE.activeEditor && tinyMCE.activeEditor.id == 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.execCommand('mceRemoveEditor', false, tinyMCE.activeEditor.id);
			}

			tinyMCE.execCommand('mceAddEditor', false, 'ForumPostContent');
			form.parent().find('.message,.signature').show();
		}

		form.toggle();

		if (form.is(':visible'))
		{
			if (tinyMCE.activeEditor && tinyMCE.activeEditor.id == 'ForumPost' + post_id + 'Content')
			{
				tinyMCE.execCommand('mceRemoveEditor', false, tinyMCE.activeEditor.id);
            }

            if (!tinyMCE.activeEditor || tinyMCE.activeEditor.id == 'ForumPost' + post_id + 'Content')
            {
                tinyMCE.execCommand('mceAddEditor', false, 'ForumPost' + post_id + 'Content');
            }

            var body_msg = form.parent().find('.message,.signature');

            if (body_msg.length)
            {
                body_msg.hide();
                tinyMCE.activeEditor.setContent(form.parent().find('.message').html());
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

		if (tinyMCE.activeEditor && tinyMCE.activeEditor.id != 'ForumPostContent')
		{
			tinyMCE.execCommand('mceRemoveEditor', false, tinyMCE.activeEditor.id);
        }

        if (!tinyMCE.activeEditor || tinyMCE.activeEditor.id != 'ForumPostContent')
        {
			tinyMCE.execCommand('mceAddEditor', false, 'ForumPostContent');
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
            getBlockUI();

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
                    var post = $("#posts #post-" + post_id);

					post.load(reload_url + '?unique=' + Math.round(Math.random()*10000) + ' #posts', function() {
                        post.replaceWith($(this).find('#post-' + post_id));

						$("#flashMessage.alert-success").html('<strong>Success</strong> ' + data.message).show();
                        tinyMCE.activeEditor.setContent('');

                        $.unblockUI();
						$.smoothScroll();

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
            getBlockUI();

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
                    var posts = $("#posts");

                    posts.load(reload_url + '?unique=' + Math.round(Math.random()*10000) + ' #posts_container', function() {
                        posts.replaceWith($(this).find('#posts'));

						$("#flashMessage.alert-success").html('<strong>Success</strong> ' + data.message).show();
                        tinyMCE.activeEditor.setContent('');

                        $.unblockUI();
						$.smoothScroll();

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