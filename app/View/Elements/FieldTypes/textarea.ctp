<?= $this->Form->input('ArticleValue.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'rows' => 15, 
    'style' => 'width:500px',
    'class' => !empty($field['Field']['required']) ? 'required' : '',
    'minlength' => $field['Field']['field_limit_min'] > 0 ? $field['Field']['field_limit_min'] : '',
    'maxlength' => $field['Field']['field_limit_max'] > 0 ? $field['Field']['field_limit_max'] : '',
    'value' => !empty($field['ArticleValue'][0]['data']) ? $field['ArticleValue'][0]['data'] : ''
)) ?>