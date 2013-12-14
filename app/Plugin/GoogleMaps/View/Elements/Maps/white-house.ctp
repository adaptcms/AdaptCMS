<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?= $this->webroot ?>google_maps/js/gmaps.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->webroot ?>google_maps/css/admin.google_maps.css" />
    <script>
        var map;
        $(document).ready(function() {
            $('#map').css('width', 610).css('height', 300);
            map = new GMaps({
                div: '#map',
                'lat': 38.897096,
                'lng': -77.036545,
                zoom: 14,
                width: 610,
                height: 300,
                disableDefaultUI: true
            });
                                                map.addMarker({
                        'lat': 38.897096,
                        'lng': -77.03654499999999,
                        'color': 'blue',
                        'size': 'normal',
                        'icon': $('#webroot').text() + 'google_maps/img/blue-dot.png',
                            'infoWindow': {
                                content: '<p>This is the White House.</p>'
                            }
                        
                    });
                                    map.addMarker({
                        'lat': 38.8881691,
                        'lng': -77.01522039999998,
                        'color': 'orange',
                        'size': 'normal',
                        'icon': $('#webroot').text() + 'google_maps/img/orange-dot.png',
                            'infoWindow': {
                                content: '<p>The Smithsonian Institution.</p>'
                            }
                        
                    });
                
                                    map.fitZoom();
                            
            
                                });
    </script>
    <div id="map"></div>
    