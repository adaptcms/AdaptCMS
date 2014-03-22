var App = angular.module('images', []);

App.controller('ImageModalCtrl', function($scope, $timeout, $http, $rootScope) {
    var images_url = $('#webroot').text() + 'admin/files/json_list';

    $scope.images = [];
    $scope.selected_images = [];
    $scope.current_key = '';
    $scope.selected_ids = [];
    $scope.limits = [];
    $scope.path = $('#webroot').text();
    $scope.url_path = images_url;
    $scope.sort_by = '';
    $scope.sort_direction = '';

    $scope.sort_by_options = [
        { 'id': '/sort:filename/direction:', 'label': 'Sort By: File Name'},
        { 'id': '/sort:filesize/direction:', 'label': 'Sort By: File Size'},
        { 'id': '/sort:caption/direction:', 'label': 'Sort By: Caption'},
        { 'id': '/sort:created/direction:', 'label': 'Sort By: Created Date'},
        { 'id': '/sort:modified/direction:', 'label': 'Sort By: Modified Date'}
    ];
    $scope.sort_direction_options = [
        { 'id': 'asc', 'label': 'Ascending' },
        { 'id': 'desc', 'label': 'Descending' }
    ];

    $scope.setLimit = function(key, limit) {
        $scope.limits[key] = limit;
    };

    $scope.getLimit = function(key) {
        if (key == '') {
            key = $scope.getCurrentKey();
        }

        if (typeof $scope.limits[key] == 'undefined') {
            $scope.limits[key] = '';
        }

        return $scope.limits[key];
    };

    $scope.updateLimits = function() {
        $.each($('.selected-images'), function() {
            var id = $(this).attr('data-id');
            var limit = $(this).attr('data-limit');

            if (limit) {
                $scope.setLimit(id, limit);
            } else {
                $scope.setLimit(id, '');
            }
        });
    };

    $scope.hasReachedLimit = function() {
        var key = $scope.getCurrentKey();

        if (!$scope.getLimit(key) || $scope.getSelectedImages(key).length < $scope.getLimit(key)) {
            return false;
        } else {
            return true;
        }
    };
    
    $scope.setImages = function(images) {
        $scope.images = images;
    };
    
    $scope.getImages = function() {
        return $scope.images;  
    };

    $scope.setSelectedImages = function(images, key) {
        $scope.selected_images[key] = images;
    };

    $scope.getSelectedImages = function(key) {
        if (typeof $scope.selected_images[key] == 'undefined') {
            $scope.selected_images[key] = [];
        }

        return $scope.selected_images[key];
    };

    $scope.setSelectedIds = function(images, key) {
        $scope.selected_ids[key] = images;
    };

    $scope.getSelectedIds = function(key) {
        if (typeof $scope.selected_ids[key] == 'undefined') {
            $scope.selected_ids[key] = [];
        }

        return $scope.selected_ids[key];
    };

    $scope.isSelectedImage = function(key, id) {
        if (key == '') {
            key = $scope.getCurrentKey();
        }

        var ids = $scope.getSelectedIds(key);

        return ids.indexOf(id) != -1;
    };

    $scope.setCurrentKey = function(key) {
        $scope.current_key = key;
    };

    $scope.getCurrentKey = function() {
        return $scope.current_key;
    };
    
    $scope.updateMediaModal = function() {
        $http.get(images_url, { dataType: 'json' })
            .success(function(response) {
                $scope.setImages(response.data);
            });
    };

    $scope.paginator = function(event) {
        event.stopPropagation();
        event.preventDefault();

        $http.get(event.target.href, { dataType: 'json' })
            .success(function(response){
                var data = response.data;

                $scope.setImages(response.data);
            });
    };

    $scope.toggleModal = function(event, type, key) {
        event.preventDefault();

        if (key != '') {
            $scope.setCurrentKey(key);
        }

        if (type == 'open') {
            $('#media-modal').modal('show');
        } else {
            $('#media-modal').modal('hide');
        }
    };

    $scope.addImage = function(key, image) {
        if (key == '') {
            key = $scope.getCurrentKey();
        }

        var id = image.id;

        var images = $scope.getSelectedImages(key);
        var ids = $scope.getSelectedIds(key);

        if (ids.indexOf(id) == -1) {
            images.push(image);
            ids.push(image.id);
        } else {
            images.splice(images.indexOf(image), 1);
            ids.splice(ids.indexOf(id), 1);
        }

        $scope.setSelectedImages(images, key);
        $scope.setSelectedIds(ids, key);
    };

    $scope.selectMultipleImages = function(type) {
        switch(type) {
            case 'all':
                var images = $scope.getImages().results;

                for(var image in images) {
                    if (!$scope.isSelectedImage('', images[image].id)) {
                        $scope.addImage('', images[image]);
                    }
                }

                $("#MediaSelectAll").attr('checked', false);
                $("#MediaSelectNone").attr('checked', false);
                break;
            case 'none':
                var images = $scope.getImages().results;

                for(var image in images) {
                    if ($scope.isSelectedImage('', images[image].id)) {
                        $scope.addImage('', images[image]);
                    }
                }

                $("#MediaSelectAll").attr('checked', false);
                $("#MediaSelectNone").attr('checked', false);
                break;
        };
    };

    $scope.sortBy = function() {
        if ($scope.sort_by) {
            $('#MediaSortDirection').show();
            $('.reset-sorting').show();

            if ($scope.sort_direction) {
                $scope.triggerSortUpdate();
            }
        } else {
            $('#MediaSortDirection').hide();
            $('.reset-sorting').hide();
            $scope.sort_direction = '';
        }
    };

    $scope.sortDirection = function() {
        if ($scope.sort_direction) {
            $scope.triggerSortUpdate();
        }
    };

    $scope.triggerSortUpdate = function() {
        var url = images_url + $scope.sort_by + $scope.sort_direction;

        $http.get(url, { dataType: 'json' })
            .success(function(response) {
                $scope.setImages(response.data);
            });
    };

    $scope.getUrlPath = function() {
        if ($scope.sort_by && $scope.sort_direction) {
            var url = images_url + $scope.sort_by + $scope.sort_direction;
        } else {
            var url = images_url;
        }

        return url;
    };

    $scope.resetSorting = function(event) {
        event.preventDefault();

        $scope.sort_by = '';
        $scope.sortBy();

        $scope.updateMediaModal();
    };

    $scope.existingImages = function() {
        if ($('.existing-images').length) {
            $.each($('.existing-images'), function() {
                $.each($(this).find('span'), function() {
                    if ($(this).html()) {
                        var key = $(this).parent().attr('data-id');
                        var contents = $.parseJSON($(this).html());

                        $scope.addImage(key, contents);
                    }
                });
            });
        }
    };

    $scope.updateMediaModal();
    $scope.updateLimits();
    $scope.existingImages();
});