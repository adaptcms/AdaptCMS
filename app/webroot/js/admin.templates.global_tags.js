var App = angular.module('tags', []);

App.controller('FormCtrl', function($scope, $timeout, $http) {
    var url = $('#TemplateAdminGlobalTagsForm').attr('action');

    $http.get(url + '/get_tags:1', { dataType: 'json' })
        .success(function(response) {
            var data = response.data;

            $scope.tags = data;
        });

    $scope.updateTags = function(event) {
        event.preventDefault();

        $http({
            method: 'POST',
            url: url,
            data: { 'data': $scope.tags },
            dataType: 'json',
            transformRequest: function(obj) {
                var str = [];
                for (var key in obj) {
                    if (obj[key] instanceof Array) {
                        for(var idx in obj[key]){
                            var subObj = obj[key][idx];
                            for(var subKey in subObj){
                                str.push(encodeURIComponent(key) + "[" + idx + "][" + encodeURIComponent(subKey) + "]=" + encodeURIComponent(subObj[subKey]));
                            }
                        }
                    }
                    else {
                        str.push(encodeURIComponent(key) + "=" + encodeURIComponent(obj[key]));
                    }
                }
                return str.join("&");
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data) {
            var status = data.data;

            if (status == 'success') {
                successMessage('The tags have been updated.');
            } else {
                errorMessage('Could not update tags, make sure your file is writable:<br /> app/Config/configuration.php');
            }
        });
    }

    $scope.addTag = function(event) {
        event.preventDefault();

        if (!$scope.tags.length) {
            var pos = 0;
        } else {
            var pos = $scope.tags.length;
        }

        $scope.tags[pos] = { 'tag': '', 'value': '', 'enabled': true };
    }

    $scope.removeTag = function(event, index) {
        event.preventDefault();

        $scope.tags[index]['enabled'] = false;
    }
});