$(document).ready(function() {
	fixPagination();
	changeRequiredFields();

	if ($(".breadcrumb li").first().next().find("a").text() == 'Themes') {
		var href = $(".breadcrumb li").first().next().find("a").attr('href').replace('themes', 'templates');
		var newLink = '<a href="' + href + '">Templates</a>';

		$(".breadcrumb li").first().next().find("a").replaceWith(newLink);
	}

	/**
	 * convience function, button class of 'btn btn-info reset-field ArticleTitle'
	 * when clicked, will reset the field with the id of 'ArticleTitle'
	 */
	$(".reset-field").on('click', function() {
		var reset_class = $(this).attr('class').split(' ').slice(-1);
		
		$("#" + reset_class).val('');
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
});

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

function fieldTypeToggle(val) 
{
	if (val == "date") {
		fieldLimitToggle('hide');
	} else if(val == "file") {
		fieldLimitToggle('hide');
	} else if(val == "dropdown") {
		fieldLimitToggle('hide');
	} else if(val == "multi-dropdown") {
		fieldLimitToggle('hide');
	} else if(val == "radio") {
		fieldLimitToggle('hide');
	} else if(val == "check") {
		fieldLimitToggle('hide');
	} else {
		fieldLimitToggle('show');
	}
}

function fieldLimitToggle(type) 
{
	if (type == "show") {
		$("#FieldFieldLimitMin").val('0').show();
		$("#FieldFieldLimitMin").prev().show();

		$("#FieldFieldLimitMax").val('0').show();
		$("#FieldFieldLimitMax").prev().show();
	} else {
		$("#FieldFieldLimitMin").val('0').hide();
		$("#FieldFieldLimitMin").prev().hide();

		$("#FieldFieldLimitMax").val('0').hide();
		$("#FieldFieldLimitMax").prev().hide();
	}
}

/*
 * Fixes cake pagination with bootstrap, otherwise current page just shows number and breaks bootstrap paginator
 */
function fixPagination()
{
	if ($(".active.paginator").length > 0) {
		var li_active_paginator_html = $(".active.paginator").html();
		var li_active_paginator = $(".active.paginator").clone().wrap('<p>').parent().html().
			replace(li_active_paginator_html, '<a>' + li_active_paginator_html + '</a>');

		$("li.active.paginator").replaceWith(li_active_paginator);
	}

	if ($(".pagination ul li a").length > 0) {
		$(".pagination ul li a").each(function() {
			if (!$(this).attr('href') && $(this).text()) {
				$(this).css('cursor', 'default');
			} else if(!$(this).text()) {
				$(this).remove();
			}
		});
	}
}