<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script><script type="text/javascript" src="/google_maps/js/gmaps.js"></script><link rel="stylesheet" type="text/css" href="/google_maps/css/admin.google_maps.css" />
    <script>
        var map;
        $(document).ready(function() {
            $('#map').css('width', 500).css('height', 250);
            map = new GMaps({
                div: '#map',
                'lat': 38.897096,
                'lng': -77.03654499999999,
                zoom: 12,
                width: 500,
                height: 250,
                disableDefaultUI: true
            });
                                                                        map.addMarker({
                            'lat': 33.772653,
                            'lng': -84.274068,
                            'color': 'blue',
                            'size': 'normal',
                            'icon': $('#webroot').text() + 'google_maps/img/blue-dot.png'
                        });
                                                                                map.addMarker({
                            'lat': 33.756048,
                            'lng': -84.366061,
                            'color': 'red',
                            'size': 'normal',
                            'icon': $('#webroot').text() + 'google_maps/img/red-dot.png',
                                'infoWindow': {
                                    content: '<p>sup bro?</p>'
                                }
                            
                        });
                                                                        
                                    map.fitZoom();
                                    });
    </script>
    <div id="map"></div>
