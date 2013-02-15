$(document).ready(function() {
	$(".poll-vote").on('submit', function(e) {
		e.preventDefault();

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
                }, function(data) {
                if (data.error) {
                	$(".poll-vote").prepend(data.message);
                } else {
                	$(".poll-vote").html(data);
                }
            });
		}
	});

	$(".poll-vote").on('click', '.results,span .go-back', function(e) {
		e.preventDefault();

        if (e.target.className.match('results')) {
            var type = 'results/';
            var id = $(this).parent().find('#PollId').val();
        } else {
            var type = 'view_poll/';
            var id = $(this).parent().parent().find('#PollId').val();
        }

        if (id) {
            $.post($("#webroot").text() + "ajax/polls/polls/" + type, 
                {
                    data:{
                        Poll:{
                            id: id
                        }
                    }
                }, function(data) {
                    $(".poll-vote").html(data);
            });
        }
	});
});