<?php $this->Html->addCrumb($tag, null) ?>

<h1><?= $tag ?></h1>

<?php if (empty($this->request->data)): ?>
	<p>No Articles Found</p>
<?php else: ?>
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
<?php endif ?>

<?= $this->element('admin_pagination') ?>