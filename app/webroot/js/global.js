$.ajaxSetup({ cache: false });

$(document).ready(function() {
	changeRequiredFields();

	$('.btn-confirm').live('click', function(e) {
		return confirm('You sure you wish to delete this item?');
	});

	/**
	 * convience function, button class of 'btn btn-info reset-field ArticleTitle'
	 * when clicked, will reset the field with the id of 'ArticleTitle'
	 */
	$(".reset-field").on('click', function() {
		var reset_class = $(this).attr('class');

        if (reset_class)
        {
            var new_class = reset_class.split(' ').slice(-1);
        }

        if (new_class)
        {
		    $("#" + new_class).val('');
        }
    });

	/*
	 *
	*/
	$("input[type='checkbox'].check-all").on('change', function(e) {
		e.preventDefault();

		if (!$(this).attr('checked')) {
			$(this).next().text('Check All');
			$(this).parent().parent().find(':checkbox:not(.check-all)').attr('checked', false);
		} else {
			$(this).next().text('Un-Check All');
			$(this).parent().parent().find(':checkbox:not(.check-all)').attr('checked', true);
		}
	});

	if ($(".admin-validate").length > 0)
	{
		$.each($(".admin-validate"), function() {
			$(this).validate();
		});
	}

	$('.btn-confirm').on('click', function(e) {
		if ($(this).attr('title'))
		{
			var text = $(this).attr('title');
		}
		else
		{
			var text = 'this item';
		}

		if (confirm('Are you sure you wish to delete ' + text + '?'))
		{
			return true;
		}

		return false;
	});

	$("#captcha .refresh").live('click', function(e) {
		e.preventDefault();

		reloadCaptcha($(this).parent().parent().attr('id'));
		$(this).blur(); 
	});
});

function reloadCaptcha(id)
{
	if (id && id != 'captcha') {
		var div_id = 'captcha_' + id.replace('captcha_', '');
	} else {
		var div_id = 'captcha';
	}

	$('#' + div_id).find('#siimage').attr('src', $('#webroot').text() + 'libraries/captcha/securimage_show.php?sid=' + Math.random());
	$('#' + div_id).find('.captcha').val('').focus();
}

// Grab all required inputs on page, put in a * to note that it's a required field
function changeRequiredFields() 
{
	$.each($(".required:input"), function(i, val) {
		var label = $(this).parent().find('label').first();

		// For the article page, this function is called so want to make sure to not have more than one *
		if ($(label).find('i:not(.icon)').length == 0) {
			$(label).append(' <i>*</i>');
		}
	});
}

function getBlockUI()
{
    $.blockUI({
        message: 'Loading, Please Wait...',
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
    });
}