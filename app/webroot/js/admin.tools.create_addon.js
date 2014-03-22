var AddonCreator = angular.module('AddonCreator', ['ngRoute']);

AddonCreator.config(
    [
        '$routeProvider',
        function($routeProvider) {
            var path = $('#webroot').text();
            var ctrl = 'Plugin';
            var folder = ctrl.toLowerCase();

            if (location.pathname.match(/theme/)) {
                ctrl = 'Theme';
                folder = ctrl.toLowerCase();
            }

            $routeProvider
                .when('/basic_info', {
                    templateUrl: path + 'angular/create_addon/' + folder + '/basic_info.html',
                    controller: ctrl + 'Ctrl'
                })
                .when('/skeleton', {
                    templateUrl: path + 'angular/create_addon/' + folder + '/skeleton.html',
                    controller: ctrl + 'Ctrl'
                })
                .when('/versions', {
                    templateUrl: path + 'angular/create_addon/' + folder + '/versions.html',
                    controller: ctrl + 'Ctrl'
                })
                .when('/overview', {
                    templateUrl: path + 'angular/create_addon/' + folder + '/overview.html',
                    controller: ctrl + 'Ctrl'
                })
                .otherwise({
                    redirectTo: '/basic_info'
                });
        }
    ]
);

AddonCreator.service('formService', function() {
    this.formData = {
        basicInfo: {
            name: '',
            block_active: '',
            is_fields: '',
            is_searchable: ''
        },
        versions: {
            current_version: '1.0',
            versions: ['1.0']
        },
        skeleton: {
            controller: false,
            model: false,
            layout: false,
            views: false
        }
    };

    this.getFormData = function() {
        return this.formData;
    }

    this.getBasicInfo = function(key) {
        return this.formData.basicInfo[key];
    };

    this.setBasicInfo = function(key, value) {
        this.formData.basicInfo[key] = value;
    };

    this.getVersions = function(key) {
        return this.formData.versions[key];
    };

    this.setVersions = function(key, value) {
        this.formData.versions[key] = value;
    };

    this.getSkeleton = function(key) {
        return this.formData.skeleton[key];
    };

    this.setSkeleton = function(key, value) {
        this.formData.skeleton[key] = value;
    };

    this.nextStep = function() {
        var current_active = $('ul.nav.nav-tabs li.active');


        setTimeout(function() {
            current_active.next().find('a').trigger('click');
        }, 1);
    };

    this.prevStep = function() {
        var current_active = $('ul.nav.nav-tabs li.active');

        setTimeout(function() {
            current_active.prev().find('a').trigger('click');
        }, 1);
    };
});

AddonCreator.controller('PluginCtrl', function PluginCtrl($rootScope, $scope, $location, formService, $http) {
    $scope.loaded = false;
    $scope.current_page = '/basic_info';

    $scope.name = '';
    $scope.block_active = '';
    $scope.is_fields = '';
    $scope.is_searchable = '';
    $scope.versions = ['1.0'];
    $scope.current_version = '1.0';
    $scope.skeleton = {
        controller: false,
        model: false
    };

    $scope.errors = [];

    $scope.initOverview = function() {

    };

    $scope.$watch('assignments', function(value) {
        $scope.loaded = true;
        $scope.current_page = $location.path();

        $('ul.nav.nav-tabs li.active').removeClass('active');
        $('ul.nav.nav-tabs li a[href="#' + $scope.current_page.replace('/', '') + '"]').parent().addClass('active');

        enablePopovers();
    });

    //Basic Info
    $scope.getBasicInfo = function(key) {
        return formService.getBasicInfo(key);
    };

    $scope.setBasicInfo = function(key) {
        return formService.setBasicInfo(key, $scope[key]);
    };

    // Versions
    $scope.getVersions = function(key) {
        return formService.getVersions(key);
    };

    $scope.setVersions = function(key) {
        return formService.setVersions(key, $scope[key]);
    };

    $scope.removeVersion = function(event, version) {
        event.preventDefault();

        if (confirm('Are you sure you wish to remove this version?')) {
            $scope.versions.remove( $scope.versions.indexOf(version) );
            $scope.setVersions('versions');

            if ($scope.getVersions('current_version') == version) {
                var new_version,
                    versions = $scope.getVersions('versions');

                for(var i in versions) {
                    if (!new_version) {
                        new_version = versions[i];
                    }
                }

                $scope.current_version = new_version;
                $scope.setVersions('current_version');
            }
        }
    };

    $scope.addVersion = function(event) {
        event.preventDefault();

        var version = $('#version');

        $scope.versions.push(version.val());
        $scope.setVersions('versions');

        version.val('');
    };

    $scope.updateCurrentVersion = function() {
        $scope.setVersions('current_version');
    };

    //Skeleton
    $scope.getSkeleton = function(key) {
        return formService.getSkeleton(key);
    };

    $scope.setSkeleton = function(key) {
        return formService.setSkeleton(key, $scope.skeleton[key]);
    };

    // Misc
    $scope.saveProgress = function(event) {
        event.preventDefault();

        $http
            .post($('#webroot').text() + 'admin/tools/create_plugin', formService.getFormData())
            .success(function(data, status, headers, config) {
                if (data.data) {
                    successMessage('Your progress has been saved.');
                }
            });
    };

    $scope.createPlugin = function(event) {
        event.preventDefault();

        $http
            .post($('#webroot').text() + 'admin/tools/create_plugin?finish=true', formService.getFormData())
            .success(function(data, status, headers, config) {
                if (data.status) {
                    window.location = $('#webroot').text() + 'admin/plugins';
                } else {
                    errorMessage(data.data);

                    $location.path('#/basic_info');
                }
            });
    };

    $scope.nextStep = function(event) {
        event.preventDefault();

        formService.nextStep();
    };

    $scope.prevStep = function(event) {
        event.preventDefault();

        formService.prevStep();
    };
});

AddonCreator.controller('ThemeCtrl', function ThemeCtrl($rootScope, $scope, $location, formService, $http) {
    $scope.loaded = false;
    $scope.current_page = '/overview';

    $scope.name = '';
    $scope.block_active = '';
    $scope.is_fields = '';
    $scope.is_searchable = '';
    $scope.versions = ['1.0'];
    $scope.current_version = '1.0';
    $scope.skeleton = {
        layout: false,
        views: false
    };

    $scope.errors = [];

    $scope.initOverview = function() {

    };

    $scope.$watch('assignments', function(value) {
        $scope.loaded = true;
        $scope.current_page = $location.path();

        $('ul.nav.nav-tabs li.active').removeClass('active');
        $('ul.nav.nav-tabs li a[href="#' + $scope.current_page.replace('/', '') + '"]').parent().addClass('active');

        enablePopovers();
    });

    //Basic Info
    $scope.getBasicInfo = function(key) {
        return formService.getBasicInfo(key);
    };

    $scope.setBasicInfo = function(key) {
        return formService.setBasicInfo(key, $scope[key]);
    };

    // Versions
    $scope.getVersions = function(key) {
        return formService.getVersions(key);
    };

    $scope.setVersions = function(key) {
        return formService.setVersions(key, $scope[key]);
    };

    $scope.removeVersion = function(event, version) {
        event.preventDefault();

        if (confirm('Are you sure you wish to remove this version?')) {
            $scope.versions.remove( $scope.versions.indexOf(version) );
            $scope.setVersions('versions');

            if ($scope.getVersions('current_version') == version) {
                var new_version,
                    versions = $scope.getVersions('versions');

                for(var i in versions) {
                    if (!new_version) {
                        new_version = versions[i];
                    }
                }

                $scope.current_version = new_version;
                $scope.setVersions('current_version');
            }
        }
    };

    $scope.addVersion = function(event) {
        event.preventDefault();

        var version = $('#version');

        $scope.versions.push(version.val());
        $scope.setVersions('versions');

        version.val('');
    };

    $scope.updateCurrentVersion = function() {
        $scope.setVersions('current_version');
    };

    //Skeleton
    $scope.getSkeleton = function(key) {
        return formService.getSkeleton(key);
    };

    $scope.setSkeleton = function(key) {
        return formService.setSkeleton(key, $scope.skeleton[key]);
    };

    // Misc
    $scope.saveProgress = function(event) {
        event.preventDefault();

        $http
            .post($('#webroot').text() + 'admin/tools/create_theme', formService.getFormData())
            .success(function(data, status, headers, config) {
                if (data.data) {
                    successMessage('Your progress has been saved.');
                }
            });
    };

    $scope.createTheme = function(event) {
        event.preventDefault();

        getBlockUI('Saving, Please Wait...');

        $http
            .post($('#webroot').text() + 'admin/tools/create_theme?finish=true', formService.getFormData())
            .success(function(data, status, headers, config) {
                $.unblockUI();

                if (data.status) {
                    window.location = $('#webroot').text() + 'admin/themes/edit/' + data.data;
                } else {
                    errorMessage(data.data);

                    $location.path('#/basic_info');
                }
            });
    };

    $scope.nextStep = function(event) {
        event.preventDefault();

        formService.nextStep();
    };

    $scope.prevStep = function(event) {
        event.preventDefault();

        formService.prevStep();
    };
});