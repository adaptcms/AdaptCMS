<script>
 $(document).ready(function(){
    $("#LinkAdminEditForm").validate();

    $("#LinkUrl").rules("add", {
    	required: true,
    	url: true
    });

    $("#LinkImageUrl").rules("add", {
    	url: true
    });

    $(".image_url,.file_id").hide();

    $("#LinkType").on('change', function() {
    	if ($(this).val()) {
    		if ($(this).val() == 'file') {
    			$(".file_id").show();
    			$(".image_url").hide();

    			$("#LinkImageUrl").val('');
    		} else {
    			$(".file_id").hide();
    			$("#selected-images").html('');

    			$(".image_url").show();
    		}
    	} else {
    		$(".image_url,.file_id").hide();
    		$("#selected-images").html('');
    		$("#LinkImageUrl").val('');
    	}
    });

    <?php if ($this->request->data['Link']['file_id'] > 0): ?>
    	$("#LinkType").val('file').trigger('change');
    <?php elseif (!empty($this->request->data['Link']['image_url'])): ?>
    	$("#LinkType").val('external').trigger('change');
    <?php endif ?>
 });
 </script>

<h1>Update Link</h1>

<?php
	echo $this->Form->create('Link', array('class' => 'well'));

	echo $this->Form->input('title', array('class' => 'required'));
	echo $this->Form->input('url', array(
		'class' => 'required', 
		'label' => 'Website Address',
		'placeholder' => 'http://'
	));
	echo $this->Form->input('link_title');
	echo $this->Form->input('link_target', array(
		'options' => array(
			'_new' => '_new',
			'_blank' => '_blank'
		)
	));
?>

<?= $this->Form->input('type', array(
		'options' => array(
			'file' => 'Pick an Image',
			'external' => 'External Image URL'
		),
		'empty' => '- Choose Image Type (optional) -'
)) ?>

<?= $this->Form->input('image_url', array(
		'div' => array(
			'class' => 'text input image_url'
		),
		'placeholder' => 'http://'
)) ?>

<div class="file_id">
	<?= $this->Html->link('Attach Image <i class="icon icon-white icon-upload"></i>', '#media-modal', array('class' => 'btn btn-primary', 'escape' => false, 'data-toggle' => 'modal')) ?>

	<p>&nbsp;</p>
	<div id="selected-images" class="span12 row">
		<?php if (!empty($this->request->data['File'])): ?>
			<?= $this->element('media_modal_image', array(
					'image' => $this->request->data['File'], 
					'key' => 0, 
					'check' => true
			)) ?>
		<?php endif ?>
	</div>
</div>

<div class="clearfix"></div>

<?= $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>
<?= $this->Form->hidden('id') ?>

<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
)) ?>

<?= $this->element('media_modal', array('limit' => 1)) ?>