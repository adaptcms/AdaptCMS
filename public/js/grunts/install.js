$(document).ready(function() {
    if ($('body.install.database').length) {
        new Vue({
            el: '.ui.segment',
            data: {
                database_connection: false,
                filled_out: false,
                'DB_HOST': 'localhost',
                'DB_PORT': '3306',
                'DB_DATABASE': '',
                'DB_USERNAME': 'root',
                'DB_PASSWORD': '',
                'sitename': '',
                fields: [
                    'DB_HOST',
                    'DB_PORT',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD'
                ]
            },
            methods: {
                testConnection: function() {
                    var _this = this;

                    var data = {};
                    _.each(this.fields, function(key) {
                        data[key] = _this[key];
                    });

                    data._token = $('meta[name="csrf-token"]').attr('content');

                    // TEMPORARY
                    // gotta write it and try it on the second attempt
                    $.post('/install/database', data, function() {
                        $.post('/install/database', data, function(response) {
                            if (response.status) {
                                toastr.success('Database connection is a-go!');
                            } else {
                                toastr.error('No dice! Try again please.');
                            }

                            _this.database_connection = response.status;
                        }, 'json');
                    });
                },
                isFilledOut: function() {
                    var _this = this;

                    var filled_out = true;
                    _.each(this.fields, function(key) {
                        if (!_this[key].length) {
                            filled_out = false;
                        }
                    });

                    this.filled_out = filled_out;
                }
            },
            watch: {
                DB_HOST: function(newVal) {
                    this.isFilledOut();
                },
                DB_PORT: function(newVal) {
                    this.isFilledOut();
                },
                DB_DATABASE: function(newVal) {
                    this.isFilledOut();
                },
                DB_USERNAME: function(newVal) {
                    this.isFilledOut();
                },
                DB_PASSWORD: function(newVal) {
                    this.isFilledOut();
                }
            }
        })
    }
});
