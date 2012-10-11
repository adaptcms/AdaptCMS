<h1>Edit File</h1>
<?php
    echo $this->Form->create('File', array('type' => 'file', 'class' => 'well'));
?>

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
    <?php $this->EditArea->editor('FileContent') ?>

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
    
    echo $this->Form->end('Submit');
?>