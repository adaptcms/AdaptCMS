var App = angular.module('search', ['ngSanitize']);

App.controller('SearchCtrl', function($scope, $timeout, $http, $sce) {
    $scope.results = null;
    $scope.modules = [];

    if (typeof $scope.q == 'undefined') {
        $scope.q = '';
    }

    if (typeof $scope.module == 'undefined') {
        $scope.module = '';
    }

    $scope.search = function(event) {
        event.preventDefault();

        var url = '/search/search/' + $scope.q + '/' + $scope.module + '/clear_search:1';

        $http.get(url, { dataType: 'json' })
        .success(function(data){
            $scope.modules = data.data;

            for(var module in $scope.modules) {
                var module_data = $scope.modules[module];

                if (module_data.results != '') {
                    $scope.modules[module].results = $sce.trustAsHtml(module_data.results);
                }
            }
        });
    };

    $scope.paginator = function(event) {
        event.stopPropagation();
        event.preventDefault();

        $http.get(event.target.href, { dataType: 'json' })
        .success(function(data){
            $scope.modules = data.data;

            for(var module in $scope.modules) {
                var module_data = $scope.modules[module];

                if (module_data.results != '') {
                    $scope.modules[module].results = $sce.trustAsHtml(module_data.results);
                }
            }
        });
    }
});

$(document).ready(function(e) {
    var form = $('.search-results');

    if (form.find('#SearchQ').val()) {
        form.find('button').trigger('click');
    }
});