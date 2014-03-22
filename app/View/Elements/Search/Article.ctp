<?php if (!empty($results)): ?>
	<ul>
		<?php foreach($results as $data): ?>
			<li>
				<?= $this->Html->link($data['Article']['title'], array(
					'controller' => 'articles',
					'action' => 'view',
					$data['Article']['slug']
				)) ?>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?>