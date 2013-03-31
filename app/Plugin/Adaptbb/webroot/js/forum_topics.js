$(document).ready(function() {
	$('.reply,.quote').on('click', function(e) {
		e.preventDefault();

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
			reload_url = $(this).find('#reload_url').html();

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
	            }, function(return_data) {
	            var data = $.parseJSON(return_data);

				if (data.status)
				{
					$("#posts").load(reload_url + ' #posts', function() {
						$("#flashMessage.alert-success").html('<strong>Success</strong> ' + data.message).show();
						$.smoothScroll();
						tinyMCE.activeEditor.setContent('');
					});
				} else {
					$("#flashMessage.alert-error").html('<strong>Error</strong>  ' + data.message).show();
					$.smoothScroll({scrollTarget: $("#flashMessage.alert-error").prev() });
				}
	        });
		}
	});
});