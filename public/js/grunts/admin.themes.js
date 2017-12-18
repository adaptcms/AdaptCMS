// index
if ($('table.themes').length) {
	var AdminThemesIndex = new Vue({
		el: 'table.themes',
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
					
					$.post('/admin/themes/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The themes have been deleted.');

							window.location.reload();
						} else {
							toastr.error('Could not delete themes, please try again.');
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
				
				$.post('/admin/themes/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The themes have been saved.');

						window.location.reload();
					} else {
						toastr.error('Could not save themes, please try again.');
					}
				});
			}
		}
	});
}
