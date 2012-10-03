<?php
	$this->TinyMce->editor();
	$time = date('Y-m-d H:i:s');
?>

<script>
$(document).ready(function(){
	$("#FieldAdminAddForm").validate();

	$("#FieldFieldType").live('change', function() {
		fieldTypeToggle($(this).val());
	});
});
</script>

<?= $this->Html->script('data-tagging.js') ?>
<?= $this->Html->css("data-tagging.css") ?>

<h1>Add Field</h1>

<?php
	echo $this->Form->create('Field', array('type' => 'file', 'class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('label', array('type' => 'text'));
	echo $this->Form->input('category_id', array('multiple' => true, 'class' => 'required'));
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
			'type' => 'text'
		)) ?>
		<?= $this->Form->button('Add', array(
			'class' => 'btn btn-info', 
			'type' => 'button',
			'id' => 'add-data'
		)) ?>
	</div>
	<div id="field_data" style="width: 30%;margin-bottom: 9px"></div>
	<?php
	echo $this->Form->input('description', array('rows' => 15, 'style' => 'width: 45%',
		'div' => array(
			'class' => 'input text clear',
			'style' => 'margin-top: -9px'
			)
	));
	echo $this->Form->input('field_limit_min', array('value' => 0, 'label' => 'Field Limit Minimum'));
	echo $this->Form->input('field_limit_max', array('value' => 0, 'label' => 'Field Limit Maximum'));
	echo $this->Form->input('field_order', array('value' => 0));
	echo $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?'));
	echo $this->Form->hidden('created', array('value' => $time));
	echo $this->Form->end('Submit');
?>