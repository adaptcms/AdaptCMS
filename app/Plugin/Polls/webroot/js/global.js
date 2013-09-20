$(document).ready(function() {
	$(".poll-vote").on('submit', function(e) {
		e.preventDefault();

        var el = $(this);

		var id = $(this).find('#PollId').val();
		var option = $(this).find('input:radio:checked').val();

		if (id && option) {
			$.post($("#webroot").text() + "polls/polls/vote/", 
            {
                data:{
                    Poll:{
                        id: id,
                        value: option
                    }
                }
            }, function(response) {
                var data = response.data;

                $('.poll-vote[data-id="' + el.attr('data-id') + '"]').html(data);
            }, 'json');
		}
	});

	$(".poll-vote").on('click', '.results,span .go-back', function(e) {
		e.preventDefault();

        if (e.target.className.match(/results/)) {
            var type = 'results/';
            var parent = $(this).parent();
        } else {
            var type = 'view_poll/';
            var parent = $(this).parent().parent();
        }

        var id = parent.find('#PollId').val();
        var block = $(this).attr('data-block-title');

        if (id) {
            $.post($("#webroot").text() + "ajax/polls/polls/" + type, 
            {
                data:{
                    Poll:{
                        id: id
                    },
                    Block: {
                        title: block
                    }
                }
            }, function(response) {
                parent.html(response.data);
            }, 'json');
        }
	});
});