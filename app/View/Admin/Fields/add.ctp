<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Fields', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Field', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Html->script('data-tagging.js') ?>
<?= $this->Html->script('jquery-ui.min.js') ?>

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

<?= $this->Form->create('Field', array('class' => 'admin-validate')) ?>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade active in well" id="main">
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
			<?= $this->Form->input('field_type_id', array(
				'options' => $field_types,
                'label' => 'Field Type',
				'empty' => '- Choose -',
                'class' => 'required'
			)) ?>

			<div class="field_options input-group col-lg-5">
				<?= $this->Form->label('field_options', "Field Options") ?>
				<div class="clearfix"></div>

				<?= $this->Form->input('field_options', array(
					'label' => false,
					'div' => false,
					'class' => 'form-control form-control-inline',
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
			<?= $this->Form->input('field_limit_min', array('type' => 'text', 'value' => 0, 'label' => 'Field Limit Minimum')) ?>
			<?= $this->Form->input('field_limit_max', array('type' => 'text', 'value' => 0, 'label' => 'Field Limit Maximum')) ?>
			<?= $this->Form->input('required', array('type' => 'checkbox', 'value' => 1, 'label' => 'Required Field?')) ?>

			<?= $this->Form->hidden('field_order', array('value' => 0)) ?>
			<?= $this->Form->hidden('order') ?>
		</div>

		<div class="tab-pane" id="order">
			<div class="well">
				<h2>Field Order</h2>

				<ul id="sort-list" class="unstyled span5 col-lg-5">
					<p>Please select a category first.</p>
				</ul>

				<div class="clearfix"></div>
			</div>
		</div>
	</div>
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>

<div class="hidden" id="field-rules">
	<?php if (!empty($field_rules)): ?>
		<?php foreach($field_rules as $field => $rule): ?>
			<div class="field" data-id="<?= $field ?>"><?= $rule ?></div>
		<?php endforeach ?>
	<?php endif ?>
</div>