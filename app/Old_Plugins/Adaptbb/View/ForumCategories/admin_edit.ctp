<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Forum Categories', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Forum Category', null) ?>

<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['ForumCategory']['id'], $this->request->data['ForumCategory']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this forum category?')"))
    ?>
</div>

<div class="clearfix"></div>

<?= $this->Form->create('ForumCategory', array('class' => 'well admin-validate')) ?>
	<h2>Edit Forum Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
	<?= $this->Form->hidden('ord') ?>
	<?= $this->Form->hidden('id') ?>

	<h4>Category Order</h4>

	<ul id="sort-list" class="unstyled span6 no-marg-left">
		<?php if (!empty($categories)): ?>
			<?php foreach($categories as $category): ?>
				<li class="btn" id="<?= $category['ForumCategory']['id'] ?>">
					<i class="icon icon-move"></i> 
					<span>
						<?= $category['ForumCategory']['title'] ?>
					</span> 

					<?php if ($category['ForumCategory']['id'] == $this->request->data['ForumCategory']['id']): ?>
						<span class="label label-info pull-right">
							Current Category
						</span>
					<?php endif ?>

					<div class="clearfix"></div>
				</li>
			<?php endforeach ?>
		<?php endif ?>
	</ul>

	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>