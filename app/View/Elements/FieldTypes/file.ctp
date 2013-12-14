<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<div class="input text">
    <?= $this->Form->label($model . '.' . $key . 'data', $icon . $field['Field']['label'], array('escape' => false)) ?>
    <?= $this->Form->file($model . '.' . $key . '.data', array(
        'class' => !empty($field['Field']['required']) ? 'required' : ''
    )) ?>
    
    <?php if (!empty($field[$model][0]['File']['filename'])): ?>
        <?= $this->Form->hidden($model . '.'.$key . '.filename', array(
            'value' => $field[$model][0]['data']
        )) ?>
        <br />
        Current File: 
        <?= $this->Html->link($field[$model][0]['File']['filename'],
            '/' . $field[$model][0]['File']['dir'] . $field[$model][0]['data'],
            array('target' => '_blank')
        ) ?>
        <?= $this->Form->input($model . '.' . $key . '.delete', array(
                'type' => 'checkbox',
                'label' => 'Unlink?'
        )) ?>
        <?= $this->Form->hidden($model . '.'.$key . '.file_id', array(
            'value' => $field[$model][0]['file_id']
        )) ?>
    <?php endif ?>
</div>