$(document).ready(function() {
	if ($("#modules").length > 0 && $("#q").length > 0)
	{
		$("#modules,#q").hide();
		modules = $.trim( $("#modules").text() ).split(',');
		count = modules.length;

		$.each(modules, function(i, val) {
			key = i + 1;

			$.ajax({
				type: "POST",
				url: $("#webroot").text() + "search/search", 
	            data: {
	                data:{
	                    'Search':{
	                        q: $("#q").text(),
	                        module: val
	                    }
	                }
	            },
	            async: false
	            }).done(function(data) {

	            $(".search-container").append(data);

	            if (count == key)
	            {
	            	$(".search-loading").hide();
	            }
	        });
	    });
	}

	$(".pagination ul li a").live('click', function(e) {
		e.preventDefault();

		$(".search-loading").show();
		id = $(this).parent().parent().parent().parent();

		page = $(this).attr('href').split(':');
		module_id = id.attr('id').split('-');

		$.post($(this).attr('href'), 
            {
                data:{
                    'Search':{
                        q: $("#q").text(),
                        module: module_id[1],
                        page: page[1]
                    }
                }
            }, function(data) {

            id.html(data);

            $(".search-loading").hide();
        });
	});
});