<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Polls', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Poll', null) ?>

<?= $this->Html->script('Polls.admin.js') ?>
<?= $this->Html->css('Polls.admin.css') ?>

<?= $this->Form->create('Poll', array('class' => 'well admin-validate')) ?>
	<h2>Add Poll</h2>

	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('article_id', array(
		'label' => 'Attach to Article', 
		'empty' => ' - choose - '
	)) ?>
	<div id="options">
		<?= $this->Form->input('PollValue.0.title', array(
			'label' => 'Option 0', 
			'class' => 'required option'
		)) ?>
	</div>

	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

	<div class="btn-group">
		<?= $this->Form->button('Add Option', array(
			'type' => 'button',
			'id' => 'poll-option-add',
			'class' => 'btn btn-warning'
		)) ?>
		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary',
			'div' => false
		)) ?>
	</div>