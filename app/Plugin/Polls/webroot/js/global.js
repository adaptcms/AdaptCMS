$(document).ready(function() {
	$(".poll-vote").on('submit', function(e) {
		e.preventDefault();

		var id = $(this).find('#PollId').val();
		var option = $(this).find('input:radio:checked').val();

		if (id && option) {
			$.post("/polls/polls/vote/", 
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

	$(".poll-vote").on('click', '#results', function(e) {
		e.preventDefault();

	});
});