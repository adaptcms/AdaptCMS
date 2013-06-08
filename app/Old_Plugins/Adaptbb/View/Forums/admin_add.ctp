<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Forums', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Forum', null) ?>

<?php
	$this->TinyMce->editor();
?>

<?= $this->Html->script('jquery-ui-1.9.2.custom.min.js') ?>

<?= $this->Form->create('Forum', array('class' => 'well admin-validate')) ?>
	<h2>Add Forum</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->input('category_id', array(
		'empty' => '- choose -',
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('description', array('rows' => 15, 'style' => 'width: 45%',
		'div' => array(
			'class' => 'input text'
		)
	)) ?>
	<?= $this->Form->input('status', array(
		'class' => 'required',
		'empty' => '- choose -',
		'options' => array(
			0 => 'Locked',
			1 => 'Active',
			2 => 'Archive'
		)
	)) ?>
	<?= $this->Form->input('icon_url', array(
		'label' => 'Icon URL',
	)) ?>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
	<?= $this->Form->hidden('ord', array('value' => 0)) ?>

    <div class="hidden-phone">
        <h4>Forum Order</h4>

        <ul id="sort-list" class="unstyled span6 no-marg-left">
            <?php if (!empty($forums)): ?>
                <?php foreach($forums as $forum): ?>
                    <li class="btn" id="<?= $forum['Forum']['id'] ?>">
                        <i class="icon icon-move"></i>
                        <span>
                            <?= $forum['Forum']['title'] ?>
                        </span>

                        <div class="clearfix"></div>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
            <li class="btn" id="0">
                <i class="icon icon-move"></i>
                <span>
                    Forum
                </span>
                <span class="label label-info pull-right">
                    Current Forum
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