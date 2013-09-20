<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins',
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Google Maps', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Map', null) ?>

<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>
<?= $this->Html->script('http://maps.google.com/maps/api/js?sensor=true') ?>
<?= $this->Html->script('GoogleMaps.gmaps.js') ?>

<?= $this->Form->create('GoogleMap', array('class' => 'well admin-validate')) ?>
    <h2>Add Map</h2>

    <?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('map_type', array(
        'empty' => '- choose map type -',
        'options' => $map_types,
        'class' => 'required map_type'
    )) ?>

    <div class="pull-left span6 no-marg-left" id="map-parameters">
        <legend>Map Options</legend>

        <div class="btn-group">
            <?= $this->Form->input('GoogleMap.options.center.address', array(
                'class' => 'input-xlarge required center-address',
                'placeholder' => $defaults['address'],
                'value' => $defaults['address'],
                'label' => 'Center on Address',
                'div' => false
            )) ?>

            <?= $this->Form->button('Update', array(
                'type' => 'button',
                'class' => 'btn btn-info pull-right',
                'style' => 'margin-left: 10px',
                'id' => 'update-center-address'
            )) ?>
        </div>

        <?= $this->Form->input('GoogleMap.options.zoom', array(
            'class' => 'input-small required zoom',
            'options' => $zoom,
            'value' => $defaults['zoom'],
            'label' => 'Zoom Level'
        )) ?>

        <div class="btn-group pull-left clearfix">
            <?= $this->Form->input('GoogleMap.options.width', array(
                'class' => 'input-small required width',
                'value' => $defaults['width'],
                'label' => 'Map Width'
            )) ?>

            <?= $this->Form->input('GoogleMap.options.height', array(
                'class' => 'input-small required height',
                'value' => $defaults['height'],
                'label' => 'Map Height'
            )) ?>
        </div>

        <?= $this->Form->hidden('GoogleMap.options.center.latitude', array(
            'value' => $defaults['latitude'],
            'class' => 'center-latitude'
        )) ?>
        <?= $this->Form->hidden('GoogleMap.options.center.longitude', array(
            'value' => $defaults['longitude'],
            'class' => 'center-longitude'
        )) ?>

        <div class="route span6 no-marg-left" style="clear: left">
            <legend>Route</legend>

            <?= $this->Form->input('GoogleMap.locations.from.address', array(
                'class' => 'required input-xlarge from-address',
                'label' => 'From Address'
            )) ?>

            <?= $this->Form->hidden('GoogleMap.locations.from.latitude', array(
                'class' => 'from-lat'
            )) ?>
            <?= $this->Form->hidden('GoogleMap.locations.from.longitude', array(
                'class' => 'from-lng'
            )) ?>

            <?= $this->Form->input('GoogleMap.locations.to.address', array(
                'class' => 'required input-xlarge to-address',
                'label' => 'Destination Address'
            )) ?>

            <?= $this->Form->hidden('GoogleMap.locations.to.latitude', array(
                'class' => 'to-lat'
            )) ?>
            <?= $this->Form->hidden('GoogleMap.locations.to.longitude', array(
                'class' => 'to-lng'
            )) ?>

            <?= $this->Form->input('GoogleMap.locations.type', array(
                'class' => 'required travel-type',
                'label' => 'Travel Type',
                'value' => $defaults['travel-type'],
                'options' => array(
                    'driving' => 'Driving',
                    'bicycling' => 'Bicycling',
                    'walking' => 'Walking'
                )
            )) ?>


            <?= $this->Form->button('Update', array(
                'type' => 'button',
                'class' => 'btn btn-info pull-right update-map'
            )) ?>
        </div>

        <div class="markers">
            <legend>Markers (optional)</legend>

            <div class="locations span6 no-marg-left">
                <?= $this->Element('marker', array('key' => 0)) ?>
            </div>
        </div>
    </div>
    <div class="pull-right span6 no-marg-left map" style="margin-right: 22px;">
        <div id="map"></div>
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
    </div>
    <div class="clearfix"></div>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary',
    'style' => 'margin-top: 10px;'
)) ?>