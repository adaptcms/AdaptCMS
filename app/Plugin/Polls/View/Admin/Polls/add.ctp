<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Polls', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Poll', null) ?>

<?= $this->AdaptHtml->script('bootstrap-datepicker') ?>
<?= $this->Html->css("datepicker") ?>

<?= $this->Html->script('Polls.admin') ?>
<?= $this->Html->css('Polls.admin') ?>

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

	<?= $this->Form->input('start_date', array(
		'type' => 'text',
		'class' => 'datepicker',
		'data-date-format' => 'yyyy-mm-dd'
	)) ?>
	<?= $this->Form->input('end_date', array(
		'type' => 'text',
		'class' => 'datepicker',
		'data-date-format' => 'yyyy-mm-dd'
	)) ?>

	<div id="options">
		<?= $this->Form->input('PollValue.0.title', array(
			'label' => 'Option 0', 
			'class' => 'required option'
		)) ?>
	</div>

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