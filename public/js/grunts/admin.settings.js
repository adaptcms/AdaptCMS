if ($('div.settings').length) {
    new Vue({
		el: 'div.settings',
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
		    saveSettings: function() {
				var _this = this;
				var settings = [];
		
				$.each($('.setting'), function() {
				    settings.push({
					key: $(this).attr('data-key'),
					value: $(this).val()
					});
				});
	
				var data = { 
					type: 'save', 
					many: true, 
					data: settings, 
					_token: $('meta[name="csrf-token"]').attr('content')
				};
		
				$.post('/admin/settings/simple-save', data, function(response) {
				    if (response.status) {
						toastr.success('The settings have been saved.');
				    } else {
						toastr.error('Could not save settings, please try again.');
				    }
				});
		    },
		    deleteMany: function() {
				if (confirm('Are you sure you wish to delete?')) {
				    var _this = this;
					var data = {
						type: 'delete',
						many: true,
						ids: JSON.stringify(_this.items),
						_token: $('meta[name="csrf-token"]').attr('content')
					};
					
				    $.post('/admin/settings/simple-save', data, function(response) {
						if (response.status) {
						    _this.items = [];
			
						    toastr.success('The settings have been deleted.');
						} else {
						    toastr.error('Could not delete settings, please try again.');
						}
				    });
				}
		    }
		}
    });
}
