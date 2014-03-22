<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<?= $this->Form->input($model . '.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'type' => 'text', 
    'data-date-format' => 'yyyy-mm-dd', 
    'class' => 'datepicker' . (!empty($field['Field']['required']) ? ' required' : ''),
    'value' => !empty($field[$model][0]['data']) ? $field[$model][0]['data'] : date("Y-m-d")
)) ?>