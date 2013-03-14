<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Fields', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Field', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Html->script('data-tagging.js') ?>
<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<?= $this->Html->css("data-tagging.css") ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Add Field</a>
	</li>
	<li>
		<a href="#order" data-toggle="tab">Field Order</a>
	</li>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="main">
		<?= $this->Form->create('Field', array('class' => 'well admin-validate')) ?>
			<h2>Add Field</h2>

			<?= $this->Form->input('import', array(
				'options' => $import,
				'empty' => '- import field values -'
			)) ?>

			<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
			<?= $this->Form->input('label', array('type' => 'text')) ?>
			<?= $this->Form->input('category_id', array(
				'class' => 'required',
				'empty' => '- Choose Category -',
				'value' => (!empty($this->params['pass'][0]) ? $this->params['pass'][0] : "")
			)) ?>
			<?= $this->Form->input('field_type', array(
				'options' => $field_types, 
				'empty' => '- Choose -', 'class' => 'required'
			)) ?>

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
			<div class="clearfix"></div>
			
			<?= $this->Form->input('description', array('rows' => 15, 'style' => 'width: 45%',
				'div' => array(
					'class' => 'input text'
				)
			)) ?>
			<?= $this->Form->input('field_limit_min', array('value' => 0, 'label' => 'Field Limit Minimum')) ?>
			<?= $this->Form->input('field_limit_max', array('value' => 0, 'label' => 'Field Limit Maximum')) ?>
			<?= $this->Form->hidden('field_order', array('value' => 0)) ?>
			<?= $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?')) ?>

			<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
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