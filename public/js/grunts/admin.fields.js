// index
if ($('.ui.fields.search').length) {
	$(document).ready(function() {
		$('.ui.fields.search').search({
			apiSettings: {
				url: '/admin/api/fields?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminFieldsIndex = new Vue({
		el: 'table.fields',
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
					
					$.post('/admin/fields/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The fields have been deleted.');

							window.location.reload();
						} else {
							toastr.error('Could not delete fields, please try again.');
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
				
				$.post('/admin/fields/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The fields have been saved.');

						window.location.reload();
					} else {
						toastr.error('Could not save fields, please try again.');
					}
				});
			}
		}
	});
}

if ($('.ui.form.fields').length) {
	new Vue({
		el: '.ui.form.fields',
		data: {
			name: '',
			caption: '',
			field_type: ''
		},
		methods: {
			updateCaption: function() {
				this.caption = S(this.name).humanize().s;
			}
		}
	});
}

if ($(".sortable.fields").length) {
	$(".sortable.fields").sortable({
		onDrop: function($item, container, _super, event) {
			$item.removeClass(container.group.options.draggedClass).removeAttr("style");
			$("body").removeClass(container.group.options.bodyClass);

			var items = [];
			$.each($('.sortable li'), function() {
				items.push($(this).attr('data-id'));
			});

			var data = {
				items: JSON.stringify(items),
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			$.post('/admin/fields/order', data, function(response) {
				toastr.succes('Order has been saved.');
			}, 'json');
		}
	});
}
