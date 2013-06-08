<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Forum Categories', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Forum Category', null) ?>

<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<?= $this->Form->create('ForumCategory', array('class' => 'well admin-validate')) ?>
	<h2>Add Forum Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
	<?= $this->Form->hidden('ord', array('value' => 0)) ?>

    <div class="hidden-phone">
        <h4>Category Order</h4>

        <ul id="sort-list" class="unstyled span6 no-marg-left">
            <?php if (!empty($categories)): ?>
                <?php foreach($categories as $category): ?>
                    <li class="btn" id="<?= $category['ForumCategory']['id'] ?>">
                        <i class="icon icon-move"></i>
                        <span>
                            <?= $category['ForumCategory']['title'] ?>
                        </span>

                        <div class="clearfix"></div>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
            <li class="btn" id="0">
                <i class="icon icon-move"></i>
                <span>
                    Category
                </span>
                <span class="label label-info pull-right">
                    Current Category
                </span>

                <div class="clearfix"></div>
            </li>
        </ul>

        <div class="clearfix"></div>
    </div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>