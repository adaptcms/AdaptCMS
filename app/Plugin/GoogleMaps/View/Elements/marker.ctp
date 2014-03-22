<div class="location btn-group col-lg-11 no-pad-l" id="location-<?= $key ?>">
    <h4>Location</h4>

    <?= $this->Form->input('GoogleMap.locations.' . $key . '.address', array(
        'class' => 'form-control address',
        'placeholder' => $defaults['address'],
        'label' => 'Address'
    )) ?>

    <div class="btn-group col-lg-12 clearfix no-pad-l">
        <?= $this->Form->input('GoogleMap.locations.' . $key . '.color', array(
            'class' => 'form-control color',
            'options' => $colors,
            'label' => 'Marker Color',
            'div' => array('class' => 'pull-left')
        )) ?>
        <?= $this->Form->input('GoogleMap.locations.' . $key . '.size', array(
            'class' => 'form-control size',
            'options' => $sizes,
            'label' => 'Marker Size',
            'div' => array('class' => 'pull-right marker-size')
        )) ?>
    </div>

    <div class="content-container col-lg-11 no-pad-l pull-left">
        <?= $this->Form->input('GoogleMap.locations.' . $key . '.content', array(
            'class' => 'form-control content',
            'label' => 'Popup Content',
            'div' => false
        )) ?>
    </div>
	<div class="clearfix"></div>

    <?php if (empty($this->request->data['GoogleMap']['locations'][$key])): ?>
        <?php $style = 'margin-top: 10px;display: none;' ?>
        <?php $add_style = 'margin-top: 10px;' ?>
    <?php else: ?>
        <?php $style = 'margin-top: 10px;' ?>
        <?php $add_style = 'margin-top: 10px;display: none;' ?>
    <?php endif ?>

    <?= $this->Form->button('Add Location', array(
        'type' => 'button',
        'class' => 'btn btn-info pull-left add-location',
        'style' => $add_style,
    )) ?>

	<?= $this->Form->button('<i class="fa fa-pencil"></i> Update', array(
		'type' => 'button',
		'class' => 'btn btn-info pull-left edit-location',
		'style' => $style . 'margin-right: 10px',
	), array('escape' => false)) ?>

    <?= $this->Form->button('<i class="fa fa-trash-o"></i> Delete', array(
        'type' => 'button',
        'class' => 'btn btn-info pull-left delete-location',
        'style' => $style,
    ), array('escape' => false)) ?>

    <?= $this->Form->hidden('GoogleMap.locations.' . $key . '.latitude', array(
        'class' => 'lat'
    )) ?>
    <?= $this->Form->hidden('GoogleMap.locations.' . $key . '.longitude', array(
        'class' => 'lng'
    )) ?>
</div>