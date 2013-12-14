$(document).ready(function() {
	if ($("table.table").length > 0)
	{
		$.each($("table.table"), function() {
			$.each($(this).find('tbody tr'), function() {
				action_text = $.trim($(this).find('td').first().text());
				action = '';

				if (action_text.match(/Admin Add/))
				{
					action = 'add';
				} else if (action_text.match(/Admin Edit/))
				{
					action = 'edit';
				} else if (action_text.match(/Admin Delete/))
				{
					action = 'delete';
				} else if (action_text.match(/Admin Restore/))
				{
					action = 'restore';
				} else if (action_text.match(/Admin Index/))
				{
					action = 'index';
				}

				if (action == 'add')
				{
					$(this).find('td').eq(2).html('&nbsp;');
					$(this).find('td').eq(3).html('&nbsp;');
				} else if (action == 'delete')
				{
					$(this).find('td label').prepend('Delete ');
				} else if (action == 'restore')
				{
					$(this).find('td label').prepend('Restore ');
				} else if (action == 'index')
				{
					$(this).find('td label').prepend('List ');
				} else if (action == 'edit')
				{
					$(this).find('td label').prepend('Edit ');
				}
			});
		});
	}
});