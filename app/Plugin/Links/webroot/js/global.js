$(document).ready(function() {

	$(".track").on('click', function(e) {
		var href = $(this).attr('href');
		var id = $(this).attr('id');
		var clicked = $(this).hasClass('clicked');

		if (!clicked) {
			e.stopPropagation();
		}

		if (href && id && !clicked) {
			$.post($("#webroot").text() + "links/links/track", 
                {
                    data:{
                        'Link':{
                            id: id,
                        }
                    }
                }, function(data) {
                $(".track#" + id).addClass('clicked');
                $(".track#" + id).trigger('click');
            });
		}
	});

});