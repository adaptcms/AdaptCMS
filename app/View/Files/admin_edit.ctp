<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit File', null) ?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<script>
$(document).ready(function() {
    $(".field_options").show();

    $("#add-media").on('click', function() {
        var val = $("#FileLibrary :selected");

        if (val.val()) {
            var random = (((1+Math.random())*0x10000)|0).toString(16).substring(1);

            if ($(".media_libraries input[value='" + val.val() + "']").length == 0) {
                $(".media_libraries").prepend('<div id="data-' + random + '"><span class="label label-info">' + val.text() + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="Media[]" name="Media[]" value="' + val.val() + '"></div>');
            }
        }
    });
});
</script>

<h2 class="left">Edit File</h2>

<div class="pull-ight admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?php if (empty($theme)): ?>
        <?= $this->Html->link(
            '<i class="icon-trash icon-white"></i> Delete',
            array('action' => 'delete', $data['File']['id']),
            array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file?')"));
        ?>
    <?php endif ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('File', array('action' => 'edit', 'type' => 'file', 'class' => 'well admin-validate')) ?>

<?php if (!empty($data['File']['filename']) &&
          !empty($data['File']['dir'])): ?>
	<label>Current File</label>
    <?php
        $var = "<p><a href='".$this->params->webroot.
                $data['File']['dir'].
                $data['File']['filename']."' target='_new'>";
    ?>
    <?php if (strstr($data['File']['mimetype'], "image")):?>
        <?= $this->Html->image(
                "/".$data['File']['dir']."thumb/".
                $data['File']['filename']
        ) ?>
        <?= $var ?>
        View Full Size
        <?php if (!empty($data['info'])): ?>
            - <?= $data['info'][0] ?> x <?= $data['info'][1] ?>
        <?php endif ?>
        </a></p>
    <?php else: ?>
        <?= $var ?>
        View File</a></p>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($file_contents)): ?>
    <?= $this->Form->input('filename', array('value' => $data['File']['filename'], 'label' => 'File Name')) ?>

    <?php $this->CodeMirror->editor('FileContent') ?>

    <?= $this->Form->input('content', array(
        'label' => 'Edit File',
        'rows' => 25, 
        'style' => 'width:90%', 
        'value' => $file_contents
    )) ?>
<?php else: ?>
    <?= $this->Form->input('filename', array('value' => $data['File']['filename'], 'label' => 'File Name')) ?>
<?php endif ?>

<?php if (!empty($theme)): ?>

    <?= $this->Form->hidden('theme', array('value' => $theme)) ?>
    <?= $this->Form->hidden('location', array('value' => $location)) ?>
<?php else: ?>
    <?= $this->Form->input('caption', array('value' => $data['File']['caption'])) ?>
<?php endif ?>

<?php
    echo $this->Form->hidden('old_filename', array('value' => $data['File']['filename']));
    if (empty($theme)) {
        echo $this->Form->hidden('dir', array('value' => $data['File']['dir']));
        echo $this->Form->hidden('mimetype', array('value' => $data['File']['mimetype']));
        echo $this->Form->hidden('filesize', array('value' => $data['File']['filesize']));
    }

    if (!empty($data['File']['id'])) {
        echo $this->Form->hidden('id', array('value' => $data['File']['id']));
    }
    
    echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
?>

<?php if (empty($theme) && strstr($data['File']['mimetype'], "image")): ?>
    <h4 class="image-filters">Image Filters</h4>

    <?php if (empty($data['File']['watermark'])): ?>
        <?= $this->Form->input('watermark', array('type' => 'checkbox')) ?>
    <?php endif ?>
    
    <?= $this->Form->input('resize_width', array('class' => 'span1 pull-left')) ?>
    <?= $this->Form->input('resize_height', array('class' => 'span1 pull-right')) ?>
    <?= $this->Form->input('random_filename', array('type' => 'checkbox')) ?>

    <h4 class="image-filters">Media Libraries</h4>

    <div class="media-libraries" style="margin-bottom: 9px">
        <?= $this->Form->input('library', array(
            'div' => false, 
            'style' => 'margin-bottom: 0',
            'empty' => '- add library -',
            'options' => $data['media-list']
        )) ?>
        <?= $this->Form->button('Add', array(
            'class' => 'btn btn-info', 
            'type' => 'button',
            'id' => 'add-media'
        )) ?>
    </div>
    <div class="media_libraries">
        <?php if (!empty($data['Media'])): ?>
            <?php foreach($data['Media'] as $key => $media): ?>
                <div id="data-<?= $key ?>">
                    <span class="label label-info">
                        <?= $media['title'] ?> <a href="#" class="icon-white icon-remove-sign"></a>
                    </span>
                    <input type="hidden" id="Media[]" name="Media[]" value="<?= $media['id'] ?>">
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <div class="clearfix media"></div>
<?php endif ?>

<?= $this->Form->end('Submit') ?>