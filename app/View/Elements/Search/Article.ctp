<li>
	<?= $this->Html->link($data['Article']['title'], array(
		'controller' => 'articles',
		'action' => 'view',
		$data['Article']['slug']
	)) ?>
</li>