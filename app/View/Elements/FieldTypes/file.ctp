<div class="input text">
    <?= $this->Form->label(ucfirst($field['Field']['title'])) ?>
    <?= $this->Form->file('ArticleValue.' . $key . '.data', array(
        'class' => !empty($field['Field']['required']) ? 'required' : ''
    )) ?>
    
    <?php if (!empty($field['ArticleValue'][0]['File']['filename'])): ?>
        <?= $this->Form->hidden('ArticleValue.'.$key . '.filename', array(
            'value' => $field['ArticleValue'][0]['data']
        )) ?>
        <br />
        Current File: 
        <?= $this->Html->link($field['ArticleValue'][0]['File']['filename'],
            '/' . $field['ArticleValue'][0]['File']['dir'] . $field['ArticleValue'][0]['data'],
            array('target' => '_blank')
        ) ?>
        <?= $this->Form->input('ArticleValue.' . $key . '.delete', array(
                'type' => 'checkbox',
                'label' => 'Unlink?'
        )) ?>
        <?= $this->Form->hidden('ArticleValue.'.$key . '.file_id', array(
            'value' => $field['ArticleValue'][0]['file_id']
        )) ?>
    <?php endif ?>
</div>