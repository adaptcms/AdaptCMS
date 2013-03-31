<?= $this->Form->input('ArticleValue.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'type' => 'select', 
    'empty' => '- Choose -', 
    'options' => array_combine($field['Field']['field_options'], $field['Field']['field_options']),
    'class' => !empty($field['Field']['required']) ? 'required' : '',
    'value' => !empty($field['ArticleValue'][0]['data']) ? $field['ArticleValue'][0]['data'] : ''
)) ?>