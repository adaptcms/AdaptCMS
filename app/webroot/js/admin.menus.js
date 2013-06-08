$(document).ready(function() {
	$('.add-item').on('click', function(e) {
		e.preventDefault();

		var el = $(this);
		var count = $('.menu-items ul li').length;

		if (el.hasClass('link') && $('.link-url').val())
		{
			if ($('.link-url-text').val())
			{
				var text = 'Link - ' + $('.link-url-text').val();
			}
			else
			{
				var text = 'Link';
			}

			var hidden_url = '<input type="hidden" name="data[Menu][items][' + count + '][url]" value="' + $('.link-url').val() + '">';
			var hidden_url_text = '<input type="hidden" name="data[Menu][items][' + count + '][url_text]" value="' + $('.link-url-text').val() + '">';
			var hidden = hidden_url + hidden_url_text;

			$('.link-url,.link-url-text').val('');

			var add_to_list = true;
		} else if (el.hasClass('page') && $('.page-id').val())
		{	
			var text = 'Page - ' + $('.page-id :selected').text();
			var hidden_page = '<input type="hidden" name="data[Menu][items][' + count + '][page_id]" value="' + $('.page-id').val() + '">';
			var hidden = hidden_page;

			$('.page-id').val('');

			var add_to_list = true;
		} else if (el.hasClass('category') && $('.category-id').val())
		{	
			var text = 'Category - ' + $('.category-id :selected').text();
			var hidden_category = '<input type="hidden" name="data[Menu][items][' + count + '][category_id]" value="' + $('.category-id').val() + '">';
			var hidden = hidden_category;

			$('.category-id').val('');

			var add_to_list = true;
		}

		if (add_to_list)
		{
			var hidden_ord = '<input type="hidden" name="data[Menu][items][' + count + '][ord]" value="' + count + '" class="ord">';
			var hidden_text = '<input type="hidden" name="data[Menu][items][' + count + '][text]" value="' + text + '">';

			var hidden = hidden + hidden_ord + hidden_text;

			var div = '<li class="btn no-marg-left clearfix" id="' + count + '"><i class="icon icon-move hidden-phone"></i> ' + text + hidden + ' <i class="icon icon-trash remove-item"></i></li>';

			$('.menu-items ul').append(div);
			$.smoothScroll({
				scrollTarget: $('.admin-validate')
			});
		}
	});
	
	if ($('#sort-list').length)
	{
	    $('#sort-list').sortable({
	        cursor: 'move',
	        update: function(event, ui)
	        {
	            var order = $(this).sortable("toArray");
	            var ul = $(this).parent();

	            $.each(ul.find('li'), function() {
	                $(this).find('.ord').val($(this).index());
	            });
	        }
	    });

	    $('#sort-list').disableSelection();
	}

    $('.remove-item').live('click', function(e) {
    	e.preventDefault();

    	var el = $(this);

    	if (confirm('Delete this menu item?'))
    	{
    		el.parent().remove();
    	}
    });

    $('a[data-toggle="modal"]').live('click', function() {
        if ($(this).attr('data-slug'))
        {
            var default_val = $('.element').html();
            var value = '<' + default_val.replace('[slug]', $(this).attr('data-slug')).replace('&gt;', '>') + '>';

            $('textarea.code').val(value);
        }
    });
});