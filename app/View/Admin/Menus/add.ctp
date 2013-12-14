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

	<div class="pull-left">
		<h3>Add Menu Item</h3>

		<legend>Custom Link</legend>

		<?= $this->Form->input('url', array(
			'class' => 'link-url url',
			'label' => 'Website URL',
            'placeholder' => 'http://'
		)) ?>
		<?= $this->Form->input('url_text', array(
			'class' => 'link-url-text',
			'label' => 'Text to Display'
		)) ?>
		<?= $this->Form->button('Add Link', array(
			'type' => 'button',
			'class' => 'btn btn-info pull-right add-item link'
		)) ?>

		<legend>Page</legend>

		<?= $this->Form->input('page_id', array(
			'class' => 'page-id',
			'label' => 'Pick a Static Page',
			'empty' => '- choose -'
		)) ?>
		<?= $this->Form->button('Add Page', array(
			'type' => 'button',
			'class' => 'btn btn-info pull-right add-item page'
		)) ?>

		<legend>Category</legend>

		<?= $this->Form->input('category_id', array(
			'class' => 'category-id',
			'label' => 'Pick a Category',
			'empty' => '- choose -'
		)) ?>
		<?= $this->Form->button('Add Category', array(
			'type' => 'button',
			'class' => 'btn btn-info pull-right add-item category'
		)) ?>
	</div>
	<div class="pull-right span6">
		<h2>Menu Items</h2>

		<div class="menu-items">
			<ul id="sort-list" class="unstyled span6"></ul>
		</div>
	</div>
	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>