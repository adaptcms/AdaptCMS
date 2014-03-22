<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit File', null) ?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<script>
$(document).ready(function() {
    $(".field_options").show();
});
</script>

<h2 class="left">Edit File</h2>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="fa fa-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn btn-info', 'escape' => false
    )) ?>

    <?= $this->Html->link(
        '<i class="fa fa-trash-o"></i> Delete',
        array('action' => 'delete', $this->request->data['File']['id']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('File', array('type' => 'file', 'class' => 'well admin-validate')) ?>

    <?php if (!empty($this->request->data['File']['filename']) &&
              !empty($this->request->data['File']['dir'])): ?>
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
            View Full Size
            <?php if (!empty($this->request->data['info'])): ?>
                - <?= $this->request->data['info'][0] ?> x <?= $this->request->data['info'][1] ?>
            <?php endif ?>
            </a></p>
        <?php else: ?>
            <?= $var ?>
            View File</a></p>
        <?php endif; ?>
    <?php endif; ?>

    <?= $this->Form->input('filename', array(
        'value' => $this->request->data['File']['filename'], 
        'label' => 'File Name'
    )) ?>

    <?php if (!empty($file_contents)): ?>
        <?php $this->CodeMirror->editor('FileContent') ?>

        <?= $this->Form->input('content', array(
            'label' => 'Edit File',
            'rows' => 25, 
            'style' => 'width:90%', 
            'value' => $file_contents
        )) ?>
    <?php endif ?>

    <?= $this->Form->input('caption', array('value' => $this->request->data['File']['caption'])) ?>

    <?= $this->Form->hidden('old_filename', array('value' => $this->request->data['File']['filename'])) ?>

    <?= $this->Form->hidden('dir', array('value' => $this->request->data['File']['dir'])) ?>
    <?= $this->Form->hidden('mimetype', array('value' => $this->request->data['File']['mimetype'])) ?>
    <?= $this->Form->hidden('filesize', array('value' => $this->request->data['File']['filesize'])) ?>

    <?php if (!empty($this->request->data['File']['id'])): ?>
        <?= $this->Form->hidden('id', array('value' => $this->request->data['File']['id'])) ?>
    <?php endif ?>
        
    <?php if (strstr($this->request->data['File']['mimetype'], "image")): ?>
        <h4 class="image-filters">Image Filters</h4>

        <?php if (empty($this->request->data['File']['watermark'])): ?>
            <?= $this->Form->input('watermark', array('type' => 'checkbox')) ?>
        <?php endif ?>

		<?= $this->Form->input('zoom', array(
			'options' => $zoom_levels,
			'label' => 'Thumbnail Crop Level'
		)) ?>
        
        <div class="col-lg-5 no-pad-l">
            <?= $this->Form->input('resize_width', array('class' => '', 'div' => array('class' => 'pull-left'))) ?>
            <?= $this->Form->input('resize_height', array('class' => '', 'div' => array('class' => 'pull-right'))) ?>
        </div>
        <div class="clearfix"></div>

        <?= $this->Form->input('random_filename', array('type' => 'checkbox')) ?>

		<div id="libraries" class="clearfix" style="margin-bottom: 9px">
	        <h4 class="image-filters">Media Libraries</h4>

	        <div class="media-libraries input-group col-lg-5" style="margin-bottom: 9px">
		        <?= $this->Form->label('library', 'Library') ?>
		        <div class="clearfix"></div>

	            <?= $this->Form->input('library', array(
	                'div' => false,
		            'label' => false,
		            'class' => 'form-control form-control-inline',
	                'style' => 'margin-bottom: 0',
	                'empty' => '- add library -',
	                'options' => $media_list
	            )) ?>
	            <?= $this->Form->button('Add', array(
	                'class' => 'btn btn-info add-media',
	                'type' => 'button'
	            )) ?>
	        </div>
	        <div class="media_libraries">
	            <?php if (!empty($this->request->data['Media'])): ?>
	                <?php foreach($this->request->data['Media'] as $key => $media): ?>
	                    <div id="data-<?= $key ?>">
	                        <span class="label label-info">
	                            <?= $media['title'] ?> <a href="#" class="fa fa-times fa-white"></a>
	                        </span>
	                        <input type="hidden" id="Media[]" name="Media[]" value="<?= $media['id'] ?>">
	                    </div>
	                <?php endforeach ?>
	            <?php endif ?>
	        </div>
	        <div class="clearfix media"></div>
		</div>
	<?php endif ?>

	<?= $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
<?= $this->Form->end() ?>