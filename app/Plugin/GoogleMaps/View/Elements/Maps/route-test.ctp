<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script><script type="text/javascript" src="/google_maps/js/gmaps.js"></script><link rel="stylesheet" type="text/css" href="/google_maps/css/admin.google_maps.css" />
    <script>
        var map;
        $(document).ready(function() {
            $('#map').css('width', 610).css('height', 300);
            map = new GMaps({
                div: '#map',
                'lat': 38.897096,
                'lng': -77.036545,
                zoom: 12,
                width: 610,
                height: 300,
                disableDefaultUI: true
            });
            
                            map.drawRoute({
                    origin: [33.756048, -84.366061],
                    destination: [33.772653, -84.274068],
                    travelMode: 'driving',
                    strokeColor: '#131540',
                    strokeOpacity: 0.6,
                    strokeWeight: 6
                });
                map.setCenter(33.756048, -84.366061);
                    });
    </script>
    <div id="map"></div>
