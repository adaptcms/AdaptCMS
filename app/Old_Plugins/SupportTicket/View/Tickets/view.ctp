<?php $this->TinyMce->editor(array('simple' => true)) ?>

<?php $this->set('title_for_layout', 'Ticket - ' . $ticket['Ticket']['subject']) ?>

<?php $this->Html->addCrumb('Tickets', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('View Ticket', null) ?>

<h2>
	Viewing Ticket

	<?php if (!empty($ticket['TicketCategory']['title'])): ?>
		<small>
			<?= $ticket['TicketCategory']['title'] ?>
		</small>
	<?php endif ?>
</h2>

<?= $this->Element('SupportTicket.view', array('ticket' => $ticket)) ?>

<?php if (!empty($ticket['Replies'])): ?>
	<?php foreach($ticket['Replies'] as $row): ?>
		<?= $this->Element('SupportTicket.view', array('ticket' => $row)) ?>
	<?php endforeach ?>
<?php endif ?>

<?= $this->Element('SupportTicket.reply', array('ticket' => $ticket)) ?>