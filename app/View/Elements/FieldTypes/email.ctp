<?= $this->Form->input('ArticleValue.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'type' => 'text',
    'class' => !empty($field['Field']['required']) ? 'required' : '',
    'minlength' => $field['Field']['field_limit_min'] > 0 ? $field['Field']['field_limit_min'] : '',
    'maxlength' => $field['Field']['field_limit_max'] > 0 ? $field['Field']['field_limit_max'] : '',
    'email' => true,
    'value' => !empty($field['ArticleValue'][0]['data']) ? $field['ArticleValue'][0]['data'] : ''
)) ?>