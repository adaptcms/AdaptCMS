map = '';
type = null;
map_markers = [];
center = [];

$(document).ready(function() {
    setCenter({ 'lat': $('.center-latitude').val(), 'lng': $('.center-longitude').val() });

    var default_address = $('.location .address:not([value])').parent().parent().clone();
    default_address.find('.address').val('');

    if ($('.lat[value!=""]').length && $('.map_type').val() == 'basic')
    {
        $.each($('.location'), function() {
            var location = $(this);
            var lat = location.find('.lat').val();

            if (lat)
            {
                addMapMarker(
                    lat,
                    location.find('.lng').val(),
                    location.find('.color').val(),
                    location.find('.size').val(),
                    location.find('.content').val()
                );
            }
        });
    }

    setMapType();

    $('.map_type').on('change', function() {
        setMapType($(this).val());
    });

    $('.add-location,.edit-location').live('click', function(e) {
        e.preventDefault();

        var adding = ($(this).hasClass('add-location') ? true : false);
        var parent = $(this).parent();

        var address = parent.find('.address').val();

        if (address.length)
        {
            var latitude = parent.find('.lat');
            var longitude = parent.find('.lng');

            var color = parent.find('.color').val();
            var size = parent.find('.size').val();
            var c = parent.find('.content').val();

            updateLocation(address, latitude, longitude, function(data) {
                if (adding)
                {
                    addMapMarker(data['latitude'], data['longitude'], color, size, c);
                }
                else
                {
                    updateMapMarker(data['latitude'], data['longitude'], color, size, c, parent.index());
                }

                if (adding)
                {
                    var index = Number($('.location').length);
                    var element = default_address.html();
                    var default_id = Number(default_address.attr('id').replace('location-', ''));
                    var regex = new RegExp('\\[GoogleMap\\]\\[locations\\]\\[' + default_id + '\\]', 'g');

                    $('.locations').append('<div class="location btn-group no-marg-left" id="location-' + index + '">' + element.replace(regex, '[GoogleMap][locations][' + index + ']') + '</div>');
                }

                updateMap(getMapType());
            });

            if (adding)
            {
                $(this).hide();
                parent.find('.edit-location,.delete-location').show();
            }
        }
    });

    $('.delete-location').live('click', function(e) {
        e.preventDefault();

        var parent = $(this).parent();

        parent.find('.edit-location,.delete-location').hide();
        parent.find('.add-location').show();
        parent.find('.address,.lat,.lng,.content').val('');

        removeMapMarker(parent.index());
        updateMap(getMapType());
    });

    $('.zoom').live('change', function() {
        updateMap(getMapType());
    });

    $('#update-center-address,.update-map').live('click', function(e) {
        e.preventDefault();

        if ($(this).hasClass('update-map'))
        {
            updateMap(getMapType());
        }
        else if ($(this).prev().val())
        {
            updateLocation($(this).prev().val(), $('.center-latitude'), $('.center-longitude'), function(data) {
                setCenter({ 'lat': data['latitude'], 'lng': data['longitude'] });
                updateMap(getMapType());
            });
        }
    });
});

function setMapType(value)
{
    if (!value)
    {
        type = $('.map_type').val();
    }
    else
    {
        type = value;
    }

    map_type(type);
}

function getMapType()
{
    return type;
}

function map_type(value)
{
    if (!value)
    {
        $('.map,#map-parameters').hide();
    }
    else
    {
        if (value == 'static')
        {
            $('.route,.content-container,#directions').hide();
            $('.markers,.marker-size').show();
        } else if (value == 'basic-route')
        {
            $('.route').show();
            $('.markers,.content-container,.marker-size,#directions').hide();
        } else if (value == 'basic-route-directions')
        {
            $('.route').show();
            $('.markers,.content-container,.marker-size').hide();
        } else if (value == 'basic')
        {
            $('.route,.marker-size,#directions').hide();
            $('.markers,.content-container').show();
        }

        updateMap(value);

        $('.map,#map-parameters').show();
    }
}

function updateMap(value)
{
    if (value == 'static')
    {
        updateStaticMap();
    }
    else if(value == 'basic')
    {
        updateBasicMap();
    }
    else if(value == 'basic-route' || value == 'basic-route-directions')
    {
        updateBasicRouteMap();
    }
}

function updateStaticMap()
{
    $('.marker-size').show();
    $('.content-container').hide();

    var center = getCenter();
    var params = [];

    params['size'] = getMapSize();
    params['zoom'] = getZoom();
    params['lat'] = center.lat;
    params['lng'] = center.lng;

    var markers = getMapMarkers();

    if (markers.length)
    {
        params['markers'] = fixMarkers(markers);
    }

    var url = GMaps.staticMapURL(params);

    $('#map').html('');
    $('<img/>').attr('src', url).appendTo('#map');
}

function updateBasicMap()
{
    $('.marker-size').hide();
    $('.content-container').show();

    var center = getCenter();
    var size = getMapSize();
    var zoom_lvl = parseInt(getZoom());

    $('#map').css('width', size[0]).css('height', size[1]);

    map = new GMaps({
        div: '#map',
        'lat': center['lat'],
        'lng': center['lng'],
        zoom: zoom_lvl,
        width: size[0],
        height: size[1],
        disableDefaultUI: true
    });

    var markers = getMapMarkers();

    if (markers.length)
    {
        map.addMarkers(fixMarkers(markers));

        if (markers.length > 1)
            map.fitZoom();
    }
}

function updateBasicRouteMap()
{
    $('.marker-size,.content-container').hide();

    var el = $('.route')
    var to_address = el.find('.to-address').val();
    var from_address = el.find('.from-address').val();

    updateBasicMap();

    if (to_address.length && from_address.length)
    {
        updateLocation(to_address, el.find('.to-lat'), el.find('.to-lng'), function(to_data) {
            updateLocation(from_address, el.find('.from-lat'), el.find('.from-lng'), function(from_data) {
                var from_route = [ from_data['latitude'], from_data['longitude'] ];
                var to_route = [ to_data['latitude'], to_data['longitude'] ];

                if (getMapType() == 'basic-route')
                {
                    map.drawRoute({
                        origin: from_route,
                        destination: to_route,
                        travelMode: el.find('.travel-type').val(),
                        strokeColor: '#131540',
                        strokeOpacity: 0.6,
                        strokeWeight: 6
                    });
                }
                else if(getMapType() == 'basic-route-directions')
                {
                    var parent = $('#directions');
                    var directions = parent.find('ul');
                    directions.html('');

                    map.travelRoute({
                        origin: from_route,
                        destination: to_route,
                        travelMode: el.find('.travel-type').val(),
                        step: function(e) {
                            var data_lat = e.end_location.lat();
                            var data_lng = e.end_location.lng();
                            var starting_li = (e.step_number == 0 ? '<li' : '<li style="display:none;"') + ' data-lat=' + data_lat + ' data-lng=' + data_lng + '>';
                            var distance = (parseFloat(e.distance.value) * 0.000621371192).toFixed(1);

                            if (distance == '0.0')
                            {
                                distance = '0.1 <';
                            }

                            directions.append(starting_li + e.instructions + ' (' + e.duration.text + ', ' + distance + ' mi)</li>');
                            map.drawPolyline({
                                path: e.path,
                                strokeColor: '#131540',
                                strokeOpacity: 0.6,
                                strokeWeight: 6
                            });
                        }
                    });

                    parent.off('click', '.next-dir,.reset-dir');
                    parent.on('click', '.next-dir,.reset-dir', function() {
                        if ($(this).hasClass('reset-dir'))
                        {
                            parent.find('.next-dir').show();
                            directions.find('li:not(:first)').hide();

                            var goto = directions.find('li:first');

                            map.setCenter(goto.attr('data-lat'), goto.attr('data-lng'));
                        } else if($(this).hasClass('next-dir'))
                        {
                            var goto = directions.find('li:hidden:first');
                            goto.fadeIn(200);
                            map.setCenter(goto.attr('data-lat'), goto.attr('data-lng'));

                            if (!directions.find('li:hidden').length)
                            {
                                parent.find('.next-dir').hide();
                            }
                        }
                    });

                    parent.show();
                }

                map.setCenter(from_data['latitude'], from_data['longitude']);
            });
        });
    }
}

function getMapSize()
{
    var width = ($('.width').val() ? $('.width').val() : 610);
    var height = ($('.height').val() ? $('.height').val() : 300);

    return [width, height];
}

function updateLocation(address, lat_element, lng_element, callback)
{
    location_lookup(address, function(data) {
        lat_element.val(data['latitude']);
        lng_element.val(data['longitude']);

        callback(data);
    });
}

function location_lookup(address, callback)
{
    GMaps.geocode({
        address: $.trim(address),
        callback: function(results, status){
            var location = [];

            if (status=='OK') {
                var coord = results[0].geometry.location;

                location['latitude'] = coord.lat();
                location['longitude'] = coord.lng();
            }

            callback(location);
        }
    });
}

function getCenter()
{
    return center;
}

function setCenter(param)
{
    center = param;
}

function getZoom()
{
    return $('.zoom').val();
}

function addMapMarker(lat, lng, color, size, content)
{
    var params = {
        'latitude': lat,
        'longitude': lng,
        'color': color,
        'size': size
    };

    if (content.length)
    {
        params['infoWindow'] = {
            content: '<p>' + content + '</p>'
        };
    }

    map_markers.push(params);
}

function updateMapMarker(lat, lng, color, size, content, position)
{
    var params = {
        'latitude': lat,
        'longitude': lng,
        'color': color,
        'size': size
    };

    if (content.length)
    {
        params['infoWindow'] = {
            content: '<p>' + content + '</p>'
        };
    }

    map_markers[position] = params;
}

function removeMapMarker(position)
{
    var index = Number(position);

    map_markers.splice(index, 1);
}

function fixMarkers(data)
{
    var results = [];
    $.each(data, function(i, val) {
        results[i] = {
            'lat': val['latitude'],
            'lng': val['longitude'],
            'color': val['color'],
            'size': val['size'],
            'icon': $('#webroot').text() + 'google_maps/img/' + val['color'] + '-dot.png'
        };

        if (val['infoWindow'])
        {
            results[i]['infoWindow'] = val['infoWindow'];
        }
    });

    return results;
}

function getMapMarkers()
{
    return map_markers;
}

function getMapRoute()
{
    return [];
}