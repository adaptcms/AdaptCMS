<h1><?= $this->request->data['Article']['title'] ?></h1>

posted by: <?= $this->request->data['User']['username'] ?><br />
at: <?= $this->Time->format('F jS, Y h:i A', $this->request->data['Article']['created']) ?><br />
category: <?= $this->request->data['Category']['title'] ?>

<p>
	<ul>
	<?php foreach($this->request->data['ArticleValue'] as $data): ?>
		<li><?= $data['Field']['title'] ?>: 
			<?php if (is_array(json_decode($data['data']))): ?>
				<?= implode(", ", json_decode($data['data'])) ?>
			<?php else: ?>
				<?= $data['data'] ?>
			<?php endif ?>
		</li>
	<?php endforeach; ?>
	</ul>
</p>