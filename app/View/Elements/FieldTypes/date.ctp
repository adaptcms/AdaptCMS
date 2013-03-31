<?= $this->Form->input('ArticleValue.' . $key . '.data', array(
    'label' => $icon . $field['Field']['label'], 
    'type' => 'text', 
    'data-date-format' => 'yyyy-mm-dd', 
    'class' => 'datepicker' . !empty($field['Field']['required']) ? ' required' : '',
    'value' => !empty($field['ArticleValue'][0]['data']) ? $field['ArticleValue'][0]['data'] : date("Y-m-d")
)) ?>