<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<div class="input radio">
    <?= $this->Form->label($model . '.' . $key . '.data', ucfirst($field['Field']['title'])) ?>
    <?= $this->Form->radio($model . '.' . $key . '.data', array_combine($field['Field']['field_options'], $field['Field']['field_options']), array(
        'legend' => false, 
        'hiddenField' => false, 
        'class' => !empty($field['Field']['required']) ? 'required' : '',
        'value' => !empty($field[$model][0]['data']) ? $field[$model][0]['data'] : ''
    )) ?>
</div>