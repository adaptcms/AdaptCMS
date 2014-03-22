<?php if (!empty($results)): ?>
	<ul>
		<?php foreach($results as $data): ?>
			<li>
				<?= $this->Html->link($data['Link']['link_title'], $data['Link']['url'], array(
						'target' => $data['Link']['link_target'],
						'escape' => false,
						'class' => 'track',
						'id' => $data['Link']['id']
				)) ?>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?>