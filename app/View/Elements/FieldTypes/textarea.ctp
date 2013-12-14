<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<?= $this->Form->input($model . '.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'rows' => 15, 
    'class' => !empty($field['Field']['required']) ? 'required' : '',
    'minlength' => $field['Field']['field_limit_min'] > 0 ? $field['Field']['field_limit_min'] : '',
    'maxlength' => $field['Field']['field_limit_max'] > 0 ? $field['Field']['field_limit_max'] : '',
    'value' => !empty($field[$model][0]['data']) ? $field[$model][0]['data'] : ''
)) ?>