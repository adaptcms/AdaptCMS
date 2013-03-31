<div class="input radio">
        <?= $this->Form->label('ArticleValue.' . $key . '.data', ucfirst($field['Field']['title'])) ?>
        <?= $this->Form->radio('ArticleValue.' . $key . '.data', array_combine($field['Field']['field_options'], $field['Field']['field_options']), array(
            'legend' => false, 
            'hiddenField' => false, 
            'class' => !empty($field['Field']['required']) ? 'required' : '',
            'value' => !empty($field['ArticleValue'][0]['data']) ? $field['ArticleValue'][0]['data'] : ''
        )) ?>
</div>