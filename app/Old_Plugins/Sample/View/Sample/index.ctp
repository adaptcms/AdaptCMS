<?php $this->Html->addCrumb('Sample Items', null) ?>

<?php $this->set('title_for_layout', 'Sample Items') ?>

<?php if (empty($this->request->data)): ?>
	<p>
		There are no sample items at this time.
	</p>
<?php else: ?>
	<ul>
		<?php foreach($this->request->data as $item): ?>
			<li>
				<?= $this->Html->link($item['Sample']['title'], array(
					'action' => 'view',
					$item['Sample']['slug']
				)) ?>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?>

<?= $this->Element('pagination') ?>