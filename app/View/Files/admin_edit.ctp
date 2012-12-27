<h2 class="left">Edit File</h2>

<div class="right">
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

<?= $this->Form->create('File', array('action' => 'edit', 'type' => 'file', 'class' => 'well')) ?>

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
        View Full Size</a></p>
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
    
    echo $this->Form->end('Submit');
?>