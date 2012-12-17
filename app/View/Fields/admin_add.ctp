<?php
	$this->TinyMce->editor();
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
<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<?= $this->Html->css("data-tagging.css") ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Field</a>
	</li>
	<li>
		<a href="#order" data-toggle="tab">Field Order</a>
	</li>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="main">
		<?= $this->Form->create('Field', array('class' => 'well')) ?>
			<h2>Add Field</h2>

		<?php
			echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
			echo $this->Form->input('label', array('type' => 'text'));
			echo $this->Form->input('category_id', array(
				'class' => 'required',
				'empty' => '- Choose Category -',
				'value' => (!empty($this->params['pass'][0]) ? $this->params['pass'][0] : "")
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
			echo $this->Form->hidden('field_order', array('value' => 0));
			echo $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?'));

			echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));

			echo $this->Form->end('Submit');
		?>
	</div>

	<div class="tab-pane" id="order">
		<div class="well">
			<h2>Field Order</h2>

			<ul id="sort-list" class="unstyled span6">
				<p>Please select a category first.</p>
			</ul>

			<div class="clearfix"></div>
		</div>
	</div>
</div>