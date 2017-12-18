// index
if ($('.ui.files.search').length) {
	$(document).ready(function() {
		$('.ui.files.search').search({
			apiSettings: {
				url: '/admin/api/files?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'filename',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminFilesIndex = new Vue({
		el: 'table.files',
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
					
					$.post('/admin/files/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The files have been deleted.');

							window.location.reload();
						} else {
							toastr.error('Could not delete files, please try again.');
						}
					});
				}
			}
		}
	});
}
