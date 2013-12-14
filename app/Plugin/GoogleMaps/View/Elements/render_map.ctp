<?= $this->Html->script('http://maps.google.com/maps/api/js?sensor=true') ?>
<?= $this->Html->script('GoogleMaps.gmaps') ?>
<?= $this->Html->css('GoogleMaps.admin.google_maps') ?>

<?php if ($data['map_type'] == 'static'): ?>
    <script>
        var map;
        var params = [];
        $(document).ready(function() {
            params['size'] = [<?= $data['options']['width'] ?>, <?= $data['options']['height'] ?>];
            params['zoom'] = <?= $data['options']['zoom'] ?>;
            params['lat'] = <?= $data['options']['center']['latitude'] ?>;
            params['lng'] = <?= $data['options']['center']['longitude'] ?>;

            <?php if (!empty($data['locations']) && count($data['locations']) > 1): ?>
                params['markers'] = [];
                <?php foreach($data['locations'] as $i => $location): ?>
                    params['markers'][<?= $i ?>] = {
                        'lat': <?= $location['latitude'] ?>,
                        'lng': <?= $location['longitude'] ?>,
                        'color': '<?= $location['color'] ?>',
                        'size': '<?= $location['size'] ?>'
                    };
                <?php endforeach ?>
            <?php endif ?>

            var url = GMaps.staticMapURL(params);

            $('#map').html('');
            $('<img/>').attr('src', url).appendTo('#map');
        });
    </script>
    <div id="map"></div>
<?php else: ?>
    <script>
        var map;
        $(document).ready(function() {
            $('#map').css('width', <?= $data['options']['width'] ?>).css('height', <?= $data['options']['height'] ?>);
            map = new GMaps({
                div: '#map',
                'lat': <?= $data['options']['center']['latitude'] ?>,
                'lng': <?= $data['options']['center']['longitude'] ?>,
                zoom: <?= $data['options']['zoom'] ?>,
                width: <?= $data['options']['width'] ?>,
                height: <?= $data['options']['height'] ?>,
                disableDefaultUI: true
            });
            <?php if (!empty($data['locations']) && $data['map_type'] == 'basic'): ?>
                <?php foreach($data['locations'] as $location): ?>
                    map.addMarker({
                        'lat': <?= $location['latitude'] ?>,
                        'lng': <?= $location['longitude'] ?>,
                        'color': '<?= $location['color'] ?>',
                        'size': '<?= $location['size'] ?>',
                        'icon': $('#webroot').text() + 'google_maps/img/<?= $location['color'] ?>-dot.png'<?php if (!empty($location['content'])): ?>,
                            'infoWindow': {
                                content: '<p><?= $location['content'] ?></p>'
                            }
                        <?php endif ?>

                    });
                <?php endforeach ?>

                <?php if (!empty($data['locations'][1])): ?>
                    map.fitZoom();
                <?php endif ?>
            <?php endif ?>

            <?php if ($data['map_type'] == 'basic-route' || $data['map_type'] == 'basic-route-directions'): ?>
                <?php $route = true ?>
                var from_route = [<?= $data['locations']['from']['latitude'] ?>, <?= $data['locations']['from']['longitude'] ?>];
                var to_route = [<?= $data['locations']['to']['latitude'] ?>, <?= $data['locations']['to']['longitude'] ?>];
                var travel_type = '<?= $data['locations']['type'] ?>';
            <?php endif ?>

            <?php if ($data['map_type'] == 'basic-route'): ?>
                map.drawRoute({
                    origin: from_route,
                    destination: to_route,
                    travelMode: travel_type,
                    strokeColor: '#131540',
                    strokeOpacity: 0.6,
                    strokeWeight: 6
                });
            <?php elseif ($data['map_type'] == 'basic-route-directions'): ?>
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
            <?php endif ?>
            <?php if (isset($route)): ?>
                map.setCenter(<?= $data['locations']['from']['latitude'] ?>, <?= $data['locations']['from']['longitude'] ?>);
            <?php endif ?>
        });
    </script>
    <div id="map"></div>
    <?php if ($data['map_type'] == 'basic-route-directions'): ?>
        <div id="directions" style="display: none">
            <h2>Directions</h2>
            <ul></ul>

            <div class="btn-group">
                <?= $this->Form->button('Next', array(
                    'type' => 'button',
                    'class' => 'btn btn-info next-dir',
                    'style' => 'margin-left: 5px',
                ), array('escape' => false)) ?>
                <?= $this->Form->button('Reset', array(
                    'type' => 'button',
                    'class' => 'btn btn-info reset-dir',
                    'style' => 'margin-left: 5px',
                ), array('escape' => false)) ?>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>