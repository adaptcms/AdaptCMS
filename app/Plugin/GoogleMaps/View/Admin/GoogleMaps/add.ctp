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

    <div class="pull-left col-lg-5 no-pad-l no-pad-r" id="map-parameters">
        <legend>Map Options</legend>

        <div class="btn-group input-group col-lg-11 no-pad-l clearfix">
	        <?= $this->Form->label('GoogleMap.options.center.address', 'Center on Address') ?>
	        <div class="clearfix"></div>

            <?= $this->Form->input('GoogleMap.options.center.address', array(
                'class' => 'form-control form-control-inline pull-left required center-address',
                'placeholder' => $defaults['address'],
                'value' => $defaults['address'],
                'label' => false,
                'div' => false
            )) ?>

            <?= $this->Form->button('Update', array(
                'type' => 'button',
                'class' => 'btn btn-info',
                'id' => 'update-center-address'
            )) ?>
        </div>

        <?= $this->Form->input('GoogleMap.options.zoom', array(
            'class' => 'required zoom',
            'options' => $zoom,
            'value' => $defaults['zoom'],
            'label' => 'Zoom Level'
        )) ?>

        <div class="btn-group pull-left">
            <?= $this->Form->input('GoogleMap.options.width', array(
                'class' => 'required width',
                'value' => $defaults['width'],
                'label' => 'Map Width',
	            'div' => array('class' => 'pull-left')
            )) ?>

            <?= $this->Form->input('GoogleMap.options.height', array(
                'class' => 'required height',
                'value' => $defaults['height'],
                'label' => 'Map Height',
	            'div' => array('class' => 'pull-right')
            )) ?>
        </div>
	    <div class="clearfix"></div>

        <?= $this->Form->hidden('GoogleMap.options.center.latitude', array(
            'value' => $defaults['latitude'],
            'class' => 'center-latitude'
        )) ?>
        <?= $this->Form->hidden('GoogleMap.options.center.longitude', array(
            'value' => $defaults['longitude'],
            'class' => 'center-longitude'
        )) ?>

        <div class="route col-lg-6 no-pad-l" style="clear: left">
            <legend>Route</legend>

            <?= $this->Form->input('GoogleMap.locations.from.address', array(
                'class' => 'required form-control from-address',
                'label' => 'From Address'
            )) ?>

            <?= $this->Form->hidden('GoogleMap.locations.from.latitude', array(
                'class' => 'from-lat'
            )) ?>
            <?= $this->Form->hidden('GoogleMap.locations.from.longitude', array(
                'class' => 'from-lng'
            )) ?>

            <?= $this->Form->input('GoogleMap.locations.to.address', array(
                'class' => 'required form-control to-address',
                'label' => 'Destination Address'
            )) ?>

            <?= $this->Form->hidden('GoogleMap.locations.to.latitude', array(
                'class' => 'to-lat'
            )) ?>
            <?= $this->Form->hidden('GoogleMap.locations.to.longitude', array(
                'class' => 'to-lng'
            )) ?>

            <?= $this->Form->input('GoogleMap.locations.type', array(
                'class' => 'form-control required travel-type',
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
                'class' => 'btn btn-info pull-left update-map',
	            'style' => 'margin-top: 10px;'
            )) ?>
        </div>
	    <div class="clearfix"></div>

        <div class="markers">
            <legend>Markers (optional)</legend>

            <div class="locations col-lg-11 no-pad-l">
                <?= $this->Element('marker', array('key' => 0)) ?>
            </div>
        </div>
	    <div class="clearfix"></div>
    </div>
    <div class="pull-right col-lg-6 no-pad-l map">
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