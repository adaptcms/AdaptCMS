<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<?= $this->Form->input($model . '.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'type' => 'select', 
    'empty' => '- Choose -', 
    'options' => array_combine($field['Field']['field_options'], $field['Field']['field_options']),
    'class' => !empty($field['Field']['required']) ? 'required' : '',
    'value' => !empty($field[$model][0]['data']) ? $field[$model][0]['data'] : ''
)) ?>