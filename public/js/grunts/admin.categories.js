// index
if ($('.ui.categories.search').length) {
	$(document).ready(function() {
		$('.ui.categories.search')
	  .search({
	    apiSettings: {
	      url: '/admin/api/categories?keyword={query}'
	    },
	    fields: {
	      results : 'results',
	      title   : 'name',
	      url     : 'url'
	    },
	    minCharacters : 3
	  })
	;
	});

	var AdminCategoriesIndex = new Vue({
		el: 'table.categories',
		data: {
			items: []
		},
		ready: function() {
			var _this = this;
			$('.ui.checkbox').checkbox({
				onChange: function() {
					var items = [];

					$.each($('input[type="checkbox"]:checked'), function() {
						items.push($(this).attr('data-id'));
					});

					_this.items = items;
				}
			});
		},
		methods: {
			deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
					var _this = this;
					var data = {
            type: 'delete',
            many: true,
            ids: JSON.stringify(_this.items),
            _token: $('meta[name="csrf-token"]').attr('content')
          };
					$.post('/admin/categories/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The categories have been deleted.');
						} else {
							toastr.error('Could not delete categories, please try again.');
						}
					});
				}
			}
		}
	})
}

if ($("ol.sortable.categories").length) {
	$("ol.sortable.categories").sortable({
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

			  $.post('/admin/categories/order', data, function(response) {
				  	toastr.success('Order has been saved.');
			  	}, 'json');
		}
	  });
}
