<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Menus', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Menu', null) ?>

<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>
<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<?= $this->Form->create('Menu', array('class' => 'well admin-validate')) ?>
	<h2>Edit Menu</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->input('Menu.settings.header', array(
        'label' => 'Header Type',
        'options' => array(
            'h1' => 'Header1 <h1>',
            'h2' => 'Header2 <h2>',
            'h3' => 'Header3 <h3>',
            'h4' => 'Header4 <h4>',
            'strong' => 'Bold <strong>',
            'text' => 'Normal Text Style'
        ),
        'empty' => 'Do not show header'
    )) ?>
    <?= $this->Form->input('Menu.settings.separator', array(
        'label' => 'Separator HTML Tag',
        'options' => array(
            'li' => 'li list <li>',
            'br' => 'Break Line <br />',
            'p' => 'Paragraph <p>'
        )
    )) ?>

	<div class="pull-left">
		<h3>Add Menu Item</h3>

		<legend>Custom Link</legend>

		<?= $this->Form->input('url', array(
			'class' => 'link-url url',
			'label' => 'Website URL'
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
			<ul id="sort-list" class="unstyled span6">
				<?php if (!empty($this->request->data['Menu']['menu_items'])): ?>
					<?php foreach($this->request->data['Menu']['menu_items'] as $key => $item): ?>
						<li class="btn no-marg-left clearfix" id="<?= $key ?>">
							<i class="icon icon-move"></i> 

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

							<i class="icon icon-trash remove-item"></i>
						</li>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>

    <?= $this->Form->hidden('id') ?>
    <?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
    <?= $this->Form->hidden('old_title', array('value' => $this->request->data['Menu']['title'])) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>