<h1>Tickets</h1>

<?php echo $this->Html->link('Submit Ticket', array('action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('subject') ?></th>
			<th><?= $this->Paginator->sort('SendUser.username', 'Submitted By') ?></th>
			<th><?= $this->Paginator->sort('created') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->request->data as $data): ?>
			<tr>
				<td>
					<?= $this->Html->link($data['Ticket']['subject'], array(
						'action' => 'view', 
						$data['Ticket']['id']
					)) ?>
				<td>
					<?= $data['SendUser']['username'] ?>
				</td>
				<td>
					<?= $data['Ticket']['created'] ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php
    $numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1'));
?>

<?php if (!empty($numbers)): ?>
    <div class="pagination">
        <ul>
            <?= $this->Paginator->prev('«', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
            <?= $numbers ?>
            <?= $this->Paginator->next('»', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
        </ul>
    </div>
<?php endif ?>