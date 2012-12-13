$(document).ready(function() {
	$("form.PostComment button[type='submit']").live('click', function(e) {
		e.preventDefault();

		var comments_text = $(this).parent().find('textarea').val();
		var article_id = $(this).parent().find('#CommentArticleId').val();
		if ($(this).parent().find('#CommentParentId').length > 0) {
			var parent_id = $(this).parent().find('#CommentParentId').val();
		} else {
			var parent_id = '';
		}
		var form = $(this).parent();

		if (comments_text) {
			var comment_count = $("#comments .comment").length;

            $.post("/ajax/comments/post/", 
            	{
            		data:
	            	{
	            		Comment:
	            		{
	            			comment_text: comments_text,
	            			article_id: article_id,
	            			parent_id: parent_id
	            		}
	            	}
	            }, function(return_data) {
	            var data = $.parseJSON(return_data);

        		if ($("#flashMessage").length > 0) {
        			$("#flashMessage").remove();
        		}

            	if (data.status) {
	            	$(form).find('textarea').val('');

	            	reloadComments(comment_count, data.id);
            	}

            	$(form).prepend(data.message);

            	if (data.status) {
            		$("#flashMessage").fadeOut(3000);
            	}
            });
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
			var form = $('.PostComment').first().clone().addClass('span11');
			var cancel_button = "<button type='cancel' class='btn'>Cancel</button>";

			var parent_id = "<input type='hidden' id='CommentParentId' name='data[Comment][parent_id]' value='" + id[1] + "'>";

			$(cancel_button).insertAfter($(form).find('button'));
			$(parent_id).insertAfter($(form).find('textarea'));

			$(form).attr('id', 'CommentViewForm' + id[1]);

			$(form).insertAfter($(this).parent().parent());
		} else {
			$(this).parent().parent().next().toggle();
		}
	});
});

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