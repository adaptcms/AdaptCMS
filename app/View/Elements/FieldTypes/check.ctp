<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<div class="input checkbox <?= !empty($field['Field']['required']) ? 'required' : '' ?>">
    <?= $this->Form->input($key . $model . '.data', array(
        'label' => $icon . $field['Field']['label'], 
        'multiple' => 'checkbox', 
        'options' => array_combine($field['Field']['field_options'], $field['Field']['field_options']),
        'class' => !empty($field['Field']['required']) ? 'required' : '',
        'value' => !empty($field[$model][0]['data']) ? $field[$model][0]['data'] : ''
    )) ?>
</div>