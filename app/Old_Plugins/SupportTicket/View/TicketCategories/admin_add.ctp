<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Tickets', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Ticket Category', null) ?>

<?= $this->Form->create('TicketCategory', array('class' => 'well admin-validate')) ?>
	<h2>Add Ticket Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>