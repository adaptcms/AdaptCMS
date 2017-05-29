// index
if ($('.ui.pages.search').length) {
	$(document).ready(function() {
		$('.ui.pages.search')
	  .search({
	    apiSettings: {
	      url: '/admin/api/pages?keyword={query}'
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

	var AdminPagesIndex = new Vue({
		el: 'table.pages',
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
					$.post('/admin/pages/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The pages have been deleted.');
						} else {
							toastr.error('Could not delete pages, please try again.');
						}
					});
				}
			},
			toggleStatusesMany: function() {
				var _this = this;
				var data = {
          type: 'toggle-statuses',
          many: true,
          ids: JSON.stringify(_this.items),
          _token: $('meta[name="csrf-token"]').attr('content')
        };
				$.post('/admin/pages/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The pages have been saved.');
					} else {
						toastr.error('Could not save pages, please try again.');
					}
				});
			}
		}
	});
}

$(document).ready(function() {
	if ($('.ui.form.pages').length) {
			new Vue({
					el: '.ui.form.pages',
					data: {
							name: '',
							slug: '',
							initRan: false
					},
					watch: {
							name: function(newVal) {
									if (this.initRan) {
											this.slug = slugify(newVal);
									}

									if (!this.initRan) {
											this.initRan = true;
									}
							}
					}
			});
	}
});

if ($("ol.sortable.pages").length) {
	$("ol.sortable.pages").sortable({
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

			  $.post('/admin/pages/order', data, function(response) {
				  	toastr.success('Order has been saved.');
			  	}, 'json');
		}
	  });
}
