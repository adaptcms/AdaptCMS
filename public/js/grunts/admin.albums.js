// index
if ($('.ui.albums.search').length) {
	$(document).ready(function() {
		$('.ui.albums.search').search({
			apiSettings: {
				url: '/admin/api/albums?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminAlbumsIndex = new Vue({
		el: 'table.albums',
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
					
					$.post('/admin/albums/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The album(s) have been deleted.');

							window.location.reload();
						} else {
							toastr.error('Could not delete album(s), please try again.');
						}
					});
				}
			}
		}
	});
}
