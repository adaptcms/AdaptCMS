<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Menus', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Menu', null) ?>

<?= $this->Html->script('jquery-ui.min.js') ?>
<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<?= $this->Form->create('Menu', array('class' => 'well admin-validate')) ?>
	<h2>Add Menu</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('Menu.settings.header', array(
        'label' => 'Header Type',
        'options' => $header_types,
        'empty' => 'Do not show header'
    )) ?>
    <?= $this->Form->input('Menu.settings.separator', array(
        'label' => 'Separator HTML Tag',
        'options' => $separator_types
    )) ?>

	<div class="pull-left col-lg-5 no-pad-l">
		<h3>Add Menu Item</h3>

		<legend>Custom Link</legend>

		<?= $this->Form->input('url', array(
			'class' => 'form-control link-url url',
			'label' => 'Website URL',
            'placeholder' => 'http://'
		)) ?>

		<div class="input-group col-lg-12 no-pad-l clearfix">
			<?= $this->Form->label('url_text', 'Text to Display') ?>
			<div class="clearfix"></div>
			<?= $this->Form->input('url_text', array(
				'class' => 'link-url-text form-control form-control-inline',
				'label' => false,
				'div' => false
			)) ?>
			<?= $this->Form->button('Add', array(
				'type' => 'button',
				'class' => 'btn btn-info add-item link'
			)) ?>
		</div>

		<legend>Page</legend>

		<div class="input-group col-lg-12 no-pad-l clearfix">
			<?= $this->Form->label('page_id', 'Pick a Static Page') ?>
			<div class="clearfix"></div>

			<?= $this->Form->input('page_id', array(
				'class' => 'page-id form-control form-control-inline',
				'label' => false,
				'div' => false,
				'empty' => '- choose -'
			)) ?>
			<?= $this->Form->button('Add', array(
				'type' => 'button',
				'class' => 'btn btn-info add-item page'
			)) ?>
		</div>

		<legend>Category</legend>

		<div class="input-group col-lg-12 no-pad-l clearfix">
			<?= $this->Form->label('category_id', 'Pick a Category') ?>
			<div class="clearfix"></div>

			<?= $this->Form->input('category_id', array(
				'class' => 'category-id form-control form-control-inline',
				'label' => false,
				'div' => false,
				'empty' => '- choose -'
			)) ?>
			<?= $this->Form->button('Add', array(
				'type' => 'button',
				'class' => 'btn btn-info add-item category'
			)) ?>
		</div>
	</div>
	<div class="pull-right col-lg-6 no-pad-l">
		<h2>Menu Items</h2>

		<div class="menu-items">
			<ul id="sort-list" class="unstyled col-lg-6"></ul>
		</div>
	</div>
	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>