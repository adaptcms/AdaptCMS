<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Menus', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Menu', null) ?>

<?= $this->Html->script('jquery-ui.min.js') ?>
<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<?= $this->Form->create('Menu', array('class' => 'well admin-validate')) ?>
	<h2>Edit Menu</h2>

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
			'class' => 'link-url url',
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
			<ul id="sort-list" class="unstyled col-lg-6">
				<?php if (!empty($this->request->data['Menu']['menu_items'])): ?>
					<?php foreach($this->request->data['Menu']['menu_items'] as $key => $item): ?>
						<li class="btn btn-success no-marg-left clearfix" id="<?= $key ?>">
							<i class="fa fa-arrows"></i>

							<?= $item['text'] ?>

							<?php if (!empty($item['url'])): ?>
								<?= $this->Form->hidden('Menu.items.' . $key . '.url', array(
									'value' => $item['url']
								)) ?>
								<?= $this->Form->hidden('Menu.items.' . $key . '.url_text', array(
									'value' => (!empty($item['url_text']) ? $item['url_text'] : '')
								)) ?>
							<?php elseif (!empty($item['page_id'])): ?>
								<?= $this->Form->hidden('Menu.items.' . $key . '.page_id', array(
									'value' => $item['page_id']
								)) ?>
							<?php else: ?>
								<?= $this->Form->hidden('Menu.items.' . $key . '.category_id', array(
									'value' => $item['category_id']
								)) ?>
							<?php endif ?>

							<?= $this->Form->hidden('Menu.items.' . $key . '.text', array(
								'value' => $item['text']
							)) ?>
							<?= $this->Form->hidden('Menu.items.' . $key . '.ord', array(
								'value' => $item['ord'],
								'class' => 'ord'
							)) ?>

							<i class="fa fa-trash-o remove-item"></i>
						</li>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>

    <?= $this->Form->hidden('id') ?>
    <?= $this->Form->hidden('old_title', array('value' => $this->request->data['Menu']['title'])) ?>

<?php if ($writable == 1): ?>
    <?= $this->Form->end(array(
        'label' => 'Submit',
        'class' => 'btn btn-primary'
    )) ?>
<?php else: ?>
    <?= $this->Element('writable_notice', array(
        'type' => 'template',
        'file' => $writable
    )) ?>
    <?= $this->Form->end() ?>
<?php endif ?>