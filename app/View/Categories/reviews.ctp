<h1><?= $title_for_layout ?></h1>

<ul>
	<?php foreach($this->request->data as $data): ?>
		<li>
			<?= $this->Html->link($data['Article']['title'], array(
					'controller' => 'articles',
					'action' => 'view',
					$data['Article']['slug']
			)) ?> @ 
			<?= $this->Admin->time($data['Article']['created']) ?>
		</li>
	<?php endforeach ?>
</ul>