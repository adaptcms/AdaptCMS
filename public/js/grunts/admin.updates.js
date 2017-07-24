var moduleUpdates = [
    {
        'name': 'Plugin',
        'slug': 'plugin'
    },
    {
        'name': 'Theme',
        'slug': 'theme'
    }
];

_.each(moduleUpdates, function(module) {
    var updatesApp = $('.ui.table.' + module.slug + 's.update');
    if (updatesApp.length) {
        new Vue({
            el: '.ui.table.' + module.slug + 's.update',
            data: {
                module_ids: {}
            },
            methods: {
                update: function() {
                    if (confirm('Are you sure you wish to update these ' + module.slug + '(s)?')) {
                        var data = {
                            module_ids: this.module_ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };

                        $.post('/admin/updates/update/' + module.slug + 's', data, function(response) {
                            if (response.status) {
                                toastr.success(module.name + '(s) have been installed!');
                            } else {
                                toastr.error('Could not install, sorry!');
                            }
                        }, 'json');
                    }
                },
                updateAll: function() {
                    $.each($(module.slug + '-id'), function() {
                        $(this).attr('checked', 'checked');
                    });

                    setTimeout(function() {
                        _this.update();
                    }, 50);
                }
            }
        });
    }
});
