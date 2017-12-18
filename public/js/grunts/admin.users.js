// index
if ($('.ui.users.search').length) {
	$(document).ready(function() {
		$('.ui.users.search').search({
			apiSettings: {
				url: '/admin/api/users?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'username',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminUsersIndex = new Vue({
		el: 'table.users',
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
					
					$.post('/admin/users/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The users have been deleted.');

							window.location.reload();
						} else {
							toastr.error('Could not delete users, please try again.');
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
				$.post('/admin/users/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The users have been saved.');

						window.location.reload();
					} else {
						toastr.error('Could not save users, please try again.');
					}
				});
			}
		}
	});
}
