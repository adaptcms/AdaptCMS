<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script><script type="text/javascript" src="/google_maps/js/gmaps.js"></script><link rel="stylesheet" type="text/css" href="/google_maps/css/admin.google_maps.css" />
    <script>
        var map;
        $(document).ready(function() {
            $('#map').css('width', 610).css('height', 300);
            map = new GMaps({
                div: '#map',
                'lat': 38.897096,
                'lng': -77.036545,
                zoom: 13,
                width: 610,
                height: 300,
                disableDefaultUI: true
            });
            
                                            var from_route = [33.756048, -84.366061];
                var to_route = [33.772653, -84.274068];
                var travel_type = 'driving';
            
                            var parent = $('#directions');
                var directions = parent.find('ul');
                directions.html('');

                map.travelRoute({
                    origin: from_route,
                    destination: to_route,
                    travelMode: travel_type,
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
                                        map.setCenter(33.756048, -84.366061);
                    });
    </script>
    <div id="map"></div>
            <div id="directions" style="display: none">
            <h2>Directions</h2>
            <ul></ul>

            <div class="btn-group">
                <button type="button" class="btn btn-info next-dir" style="margin-left: 5px">Next</button>                <button type="button" class="btn btn-info reset-dir" style="margin-left: 5px">Reset</button>            </div>
        </div>
    