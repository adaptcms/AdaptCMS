<?php
	$this->TinyMce->editor();
?>

<script>
 $(document).ready(function(){
    $("#FieldEditForm").validate();

	fieldTypeToggle($("#FieldFieldType").val());

	$("#FieldFieldType").live('change', function() {
		fieldTypeToggle($(this).val());
	});
 });
 </script>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<h2 class="left">Edit Field</h2>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Field']['id'], $this->request->data['Field']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this field?')"));
    ?>
</div>
<div class="clearfix"></div>

<?php
    echo $this->Form->create('Field', array('action' => 'edit', 'class' => 'well'));

    echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
    echo $this->Form->input('label', array('type' => 'text'));
	echo $this->Form->input('category_id', array(
		'empty' => '- Choose Category -',
		'class' => 'required'
	));
	echo $this->Form->input('field_type', array(
		'options' => array(
			'text' => 'Text Input', 
			'textarea' => 'Text Box', 
			'dropdown' => 'Dropdown Selector', 
			'multi-dropdown' => 'Dropdown Selector Multiple', 
			'radio' => 'Radio', 
			'check' => 'Checkbox', 
			'file' => 'File', 
			'img' => 'Image', 
			'url' => 'Website URL', 
			'num' => 'Number', 
			'email' => 'Email', 
			'date' => 'Date'
			), 
		'empty' => '- Choose -', 'class' => 'required'
	));
	?>
	<div class="field_options" style="margin-bottom: 9px">
		<?= $this->Form->input('field_options', array(
			'div' => false, 
			'style' => 'margin-bottom: 0',
			'type' => 'text',
			'value' => ''
		)) ?>
		<?= $this->Form->button('Add', array(
			'class' => 'btn btn-info', 
			'type' => 'button',
			'id' => 'add-data'
		)) ?>
	</div>
	<div id="field_data" style="width: 30%;margin-bottom: 9px"></div>
	<div id="field_existing_data" style="display:none">
		<?php
			$field_options = json_decode($this->request->data['Field']['field_options']);
			if (count($field_options) == 0) {
				$field_style = "padding-top: 0px";
			} else {
				$field_style = "padding-top: 9px";
			}
		?>
		<?php if (count($field_options) > 0): ?>
			<?php foreach($field_options as $row): ?>
				<span><?= $row ?></span>
			<?php endforeach ?>
		<?php endif ?>
	</div>
	<?php
	echo $this->Form->input('description', array(
		'rows' => 15, 
		'style' => 'width: 45%',
		'div' => array(
			'class' => 'input text clear',
			'style' => $field_style
			)
	));
	echo $this->Form->input('field_limit_min', array('label' => 'Field Limit Minimum'));
	echo $this->Form->input('field_limit_max', array('label' => 'Field Limit Maximum'));
	echo $this->Form->input('field_order');
	echo $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?'));

    echo $this->Form->hidden('id');
    echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
    echo $this->Form->end('Submit');
 ?>