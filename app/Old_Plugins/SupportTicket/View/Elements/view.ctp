<div class="span10 well<?php if (!$ticket['reply']): ?> no-marg-left<?php endif ?>" id="ticket-<?= $ticket['Ticket']['id'] ?>">
	<div class="header">
		<h5>
			<?php if (!empty($ticket['Ticket']['full_name'])): ?>

			<?php endif ?>
			<?php if (!empty($ticket['User']['username'])): ?>
				<?= $this->Html->link('(' . $ticket['User']['username'] . ')', array(
					'controller' => 'users',
					'action' => 'profile',
					$ticket['User']['username']
				)) ?>
			<?php else: ?>
				(Guest)
			<?php endif ?>
			 @ 
			<?= $this->Admin->time($ticket['Ticket']['created'], 'words') ?>
		</h5>

		<?php if (!$ticket['reply']): ?>
			<h3>
				<?= $ticket['Ticket']['subject'] ?>
			</h3>
		<?php endif ?>
	</div>

	<div class="body">
		<?= $ticket['Ticket']['message'] ?>
	</div>
</div>

<div class="clearfix"></div>