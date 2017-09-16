// index
if ($('.ui.posts.search').length) {
	$(document).ready(function() {
		$('.ui.posts.search').search({
			apiSettings: {
				url: '/admin/api/posts?keyword={query}'
			},
			fields: {
				results : 'results',
				title   : 'name',
				url     : 'url'
			},
			minCharacters : 3
		});
	});

	var AdminPostsIndex = new Vue({
		el: 'table.posts',
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
					
					$.post('/admin/posts/simple-save', data, function(response) {
						if (response.status) {
							_this.items = [];

							toastr.success('The posts have been deleted.');
						} else {
							toastr.error('Could not delete posts, please try again.');
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
				
				$.post('/admin/posts/simple-save', data, function(response) {
					if (response.status) {
						$('input[type="checkbox"]:checked').trigger('click');

						toastr.success('The posts have been saved.');
					} else {
						toastr.error('Could not save posts, please try again.');
					}
				});
			}
		}
	});
}

$(document).ready(function() {
	if ($('.ui.form.posts').length) {
		new Vue({
			el: '.ui.form.posts',
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

// add/edit
if ($('#AdminArticlesApp').length) {
	var AdminArticlesApp = new Vue({
		el: '#AdminArticlesApp',
		created: function() {

		}
	});

	if ($('#datepicker').lenth) {
		new DatePicker({ dataFields: [document.getElementById('datepicker')] });
	}
}
