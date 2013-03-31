<div>
    <?= $this->Form->hidden('ArticleValue.' . $key . '.data') ?>
    <?= $this->Form->hidden($key . 'ArticleValue.file_id') ?>
    <?= $this->Form->label($icon . $field['Field']['label']) ?>

    <?= $this->Html->link(
            'Attach Image <i class="icon icon-white icon-upload"></i>', 
            '#media-modal' . $field['Field']['id'], 
            array(
                'class' => 'btn btn-primary media-modal', 
                'escape' => false, 
                'data-toggle' => 'modal'
            )
    ) ?>

    <p>&nbsp;</p>
    <ul class="selected-images span12 thumbnails">
        <?php if (!empty($field['ArticleValue'][0]['File'])): ?>
            <?= $this->element('media_modal_image', array(
                    'image' => $field['ArticleValue'][0]['File'], 
                    'key' => 0, 
                    'check' => true
            )) ?>
        <?php endif ?>
    </ul>
</div>
<div class="clearfix"></div>

<?= $this->element('media_modal', array(
    'limit' => 1, 
    'ids' => 'ArticleValue.' . $key . '.data', 
    'id' => $field['Field']['id']
)) ?>
