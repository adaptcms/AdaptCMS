<h1>Edit File</h1>
<?php
    echo $this->Form->create('File', array('type' => 'file', 'class' => 'well'));
?>

<?php if (!empty($this->request->data['File']['filename'])): ?>
	<label>Current File</label>
    <?php
        $var = "<p><a href='".$this->params->webroot.
                $this->request->data['File']['dir'].
                $this->request->data['File']['filename']."' target='_new'>";
    ?>
    <?php if (strstr($this->request->data['File']['mimetype'], "image")):?>
        <?= $this->Html->image(
                "/".$this->request->data['File']['dir']."thumb/".
                $this->request->data['File']['filename']
        ) ?>
        <?= $var ?>
        View Full Size</a></p>
    <?php else: ?>
        <?= $var ?>
        View File</a></p>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($file_contents)): ?>
    <?php $this->EditArea->editor('FileContent') ?>

    <?= $this->Form->input('content', array(
        'label' => 'Edit File',
        'rows' => 25, 
        'style' => 'width:90%', 
        'value' => $file_contents
    )) ?>
<?php else: ?>
    <?= $this->Form->input('filename', array('type' => 'file', 'label' => 'Upload New File')) ?>
<?php endif ?>

<?php
    echo $this->Form->input('caption');

    echo $this->Form->hidden('old_filename', array('value' => $this->request->data['File']['filename']));
    echo $this->Form->hidden('dir');
    echo $this->Form->hidden('mimetype');
    echo $this->Form->hidden('filesize');
    
    echo $this->Form->end('Submit');
?>