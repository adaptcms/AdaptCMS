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
<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Field</a>
	</li>
	<?php if (!empty($fields)): ?>
		<li>
			<a href="#order" data-toggle="tab">Field Order</a>
		</li>
	<?php endif ?>
	<div class="pull-right">
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
</ul>

<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="main">
		<?= $this->Form->create('Field', array('action' => 'edit', 'class' => 'well')) ?>

			<h2>Edit Field</h2>

			<?php
			    echo $this->Form->input('title', array(
			    	'type' => 'text', 
			    	'class' => 'required'
			    ));
			    echo $this->Form->input('label', array('type' => 'text'));
				echo $this->Form->input('category_id', array(
					'empty' => '- Choose Category -',
					'class' => 'required'
				));
				echo $this->Form->input('field_type', array(
					'options' => $field_types, 
					'empty' => '- Choose -', 
					'class' => 'required'
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
					<?php if (!empty($this->request->data['Field']['field_options'])): ?>
						<?php foreach($this->request->data['Field']['field_options'] as $row): ?>
							<span><?= $row ?></span>
						<?php endforeach ?>
					<?php endif ?>
				</div>
				<div class="clearfix"></div>
				<?php
				echo $this->Form->input('description', array(
					'rows' => 15, 
					'style' => 'width: 45%',
					'div' => array(
						'class' => 'input text clear'
					)
				));
				echo $this->Form->input('field_limit_min', array('label' => 'Field Limit Minimum'));
				echo $this->Form->input('field_limit_max', array('label' => 'Field Limit Maximum'));
				echo $this->Form->hidden('field_order');
				echo $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?'));

			    echo $this->Form->hidden('id');
			    echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
			?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>

	<div class="tab-pane" id="order">
		<div class="well">
			<h2>Field Order</h2>

			<ul id="sort-list" class="unstyled span6">
				<?php if (!empty($fields)): ?>
					<?php foreach($fields as $field): ?>
						<li class="btn" id="<?= $field['Field']['id'] ?>">
							<i class="icon icon-move"></i> 
							<?php if ($this->request->data['Field']['id'] == $field['Field']['id']): ?>
								<span>
									<?= $this->request->data['Field']['title'] ?>
								</span> 
								<i class="icon icon-question-sign" data-content="<?= $this->request->data['Field']['description'] ?>" data-title="<?= $this->request->data['Field']['title'] ?>"></i> 
								<span class="label label-info pull-right">
									Current Field
								</span>
							<?php else: ?>
								<span>
									<?= $field['Field']['label'] ?>
								</span> 
								<i class="icon icon-question-sign" data-content="<?= $field['Field']['description'] ?>" data-title="<?= $field['Field']['label'] ?>"></i>
							<?php endif ?>

							<div class="clearfix"></div>
						</li>
					<?php endforeach ?>
				<?php endif ?>
			</ul>

			<div class="clearfix"></div>
		</div>
	</div>
</div>