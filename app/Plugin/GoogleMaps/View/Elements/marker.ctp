<div class="location btn-group no-marg-left" id="location-<?= $key ?>">
    <h4>Location</h4>

    <?= $this->Form->input('GoogleMap.locations.' . $key . '.address', array(
        'class' => 'input-xlarge address',
        'placeholder' => $defaults['address'],
        'label' => 'Address'
    )) ?>

        <div class="btn-group span12 clearfix no-marg-left">
            <?= $this->Form->input('GoogleMap.locations.' . $key . '.color', array(
                'class' => 'input-small color',
                'options' => $colors,
                'label' => 'Marker Color',
                'div' => array('class' => 'pull-left')
            )) ?>
            <?= $this->Form->input('GoogleMap.locations.' . $key . '.size', array(
                'class' => 'input-small size',
                'options' => $sizes,
                'label' => 'Marker Size',
                'div' => array('class' => 'pull-right marker-size')
            )) ?>
        </div>

        <div class="content-container pull-left clearfix">
            <?= $this->Form->input('GoogleMap.locations.' . $key . '.content', array(
                'class' => 'input-xlarge content',
                'label' => 'Popup Content',
                'div' => false
            )) ?>
        </div>

    <?php if (empty($this->request->data['GoogleMap']['locations'][$key])): ?>
        <?php $style = 'margin-left: 5px;display: none' ?>
        <?php $add_style = 'margin-left: 10px' ?>
    <?php else: ?>
        <?php $style = 'margin-left: 5px' ?>
        <?php $add_style = 'margin-left: 10px;display: none' ?>
    <?php endif ?>

    <?= $this->Form->button('Add Location', array(
        'type' => 'button',
        'class' => 'btn btn-info pull-right add-location',
        'style' => $add_style,
    )) ?>

    <?= $this->Form->button('<i class="icon icon-trash"></i> Delete', array(
        'type' => 'button',
        'class' => 'btn btn-info pull-right delete-location',
        'style' => $style,
    ), array('escape' => false)) ?>

    <?= $this->Form->button('<i class="icon icon-pencil"></i> Update', array(
        'type' => 'button',
        'class' => 'btn btn-info pull-right edit-location',
        'style' => $style,
    ), array('escape' => false)) ?>

    <?= $this->Form->hidden('GoogleMap.locations.' . $key . '.latitude', array(
        'class' => 'lat'
    )) ?>
    <?= $this->Form->hidden('GoogleMap.locations.' . $key . '.longitude', array(
        'class' => 'lng'
    )) ?>
</div>