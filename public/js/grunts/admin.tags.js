// index
if ($('.ui.tags.search').length) {
	$(document).ready(function() {
		$('.ui.tags.search').search({
			apiSettings: {
				url: '/admin/api/tags?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminTagsIndex = new Vue({
		el: 'table.tags',
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
					
					$.post('/admin/tags/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The tags have been deleted.');
						} else {
							toastr.error('Could not delete tags, please try again.');
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
				
				$.post('/admin/tags/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The tags have been saved.');
					} else {
						toastr.error('Could not save tags, please try again.');
					}
				});
			}
		}
	});
}
