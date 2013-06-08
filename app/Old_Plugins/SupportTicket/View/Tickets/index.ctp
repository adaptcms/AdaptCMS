<h1>Tickets</h1>

<?php if ($this->Admin->hasPermission($permissions['related']['tickets']['add'])): ?>
    <div class="pull-right admin-edit-options">
		<?= $this->Html->link('Submit Ticket <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
			'class' => 'btn btn-info',
			'escape' => false
		)) ?>
	</div>
<?php endif ?>

<?php if (empty($tickets)): ?>
	<p>
		There are no tickets currently submitted.
	</p>
<?php else: ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('subject') ?></th>
				<th><?= $this->Paginator->sort('User.username', 'Submitted By') ?></th>
				<th><?= $this->Paginator->sort('TicketCategory.title', 'Category') ?></th>
				<th class="hidden-phone"># of Replies</th>
				<th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($tickets as $ticket): ?>
				<tr>
					<td>
						<?= $this->Html->link($ticket['Ticket']['subject'], array(
							'action' => 'view', 
							'id' => $ticket['Ticket']['id'],
							'slug' => $ticket['Ticket']['slug']
						)) ?>
					<td>
						<?php if (!empty($ticket['User']['username'])): ?>
							<?= $ticket['User']['username'] ?>
						<?php else: ?>
							Guest
						<?php endif ?>
					</td>
					<td>
						<?php if (!empty($ticket['TicketCategory']['title'])): ?>
							<?= $ticket['TicketCategory']['title'] ?>
						<?php endif ?>
					</td>
					<td class="hidden-phone">
						<?= $ticket['Ticket']['replies'] ?>
					</td>
					<td class="hidden-phone">
						<?= $this->Admin->time($ticket['Ticket']['created'], 'F d, Y') ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?= $this->element('admin_pagination') ?>
<?php endif ?>