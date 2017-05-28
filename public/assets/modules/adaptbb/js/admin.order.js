$(document).ready(function() {
    var categories = $('.adaptbb-forum-categories.sortable');
    if (categories.length) {
        categories.sortable({
      		  onDrop: function($item, container, _super, event) {
      			  $item.removeClass(container.group.options.draggedClass).removeAttr("style");
      			  $("body").removeClass(container.group.options.bodyClass);

      			  var items = [];
      			  $.each($('ol.sortable li'), function() {
      			  		items.push($(this).attr('data-id'));
      			  });

      				var data = {
      						items: JSON.stringify(items),
      						_token: $('meta[name="csrf-token"]').attr('content')
      				};

      			  $.post('/admin/adaptbb/forum_categories/order', data, function(response) {
      				  	toastr.success('Order has been saved.');
      			  	}, 'json');
      		}
      	  });
    }

    var forums = $('.adaptbb-forums.sortable');
    if (forums.length) {
        forums.sortable({
      		  onDrop: function($item, container, _super, event) {
      			  $item.removeClass(container.group.options.draggedClass).removeAttr("style");
      			  $("body").removeClass(container.group.options.bodyClass);

      			  var items = [];
      			  $.each($('ol.sortable li'), function() {
      			  		items.push($(this).attr('data-id'));
      			  });

      				var data = {
      						items: JSON.stringify(items),
      						_token: $('meta[name="csrf-token"]').attr('content')
      				};

      			  $.post('/admin/adaptbb/forums/order', data, function(response) {
      				  	toastr.success('Order has been saved.');
      			  	}, 'json');
      		}
      	  });
    }
});
