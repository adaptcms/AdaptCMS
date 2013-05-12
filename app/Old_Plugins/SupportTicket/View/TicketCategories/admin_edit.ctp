<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Tickets', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Ticket Category', null) ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['TicketCategory']['id'], $this->request->data['TicketCategory']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this ticket category?')"))
    ?>
</div>

<div class="clearfix"></div>

<?= $this->Form->create('TicketCategory', array('class' => 'well admin-validate')) ?>
	<h2>Edit Ticket Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
	<?= $this->Form->hidden('id') ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>