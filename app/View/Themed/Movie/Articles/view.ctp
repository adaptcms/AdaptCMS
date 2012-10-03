<div class="box">
	<h1><?= $this->request->data['Article']['title'] ?></h1>

	posted by: <?= $this->request->data['User']['username'] ?><br />
	at: <?= $this->Time->format('F jS, Y h:i A', $this->request->data['Article']['created']) ?><br />
	category: <?= $this->request->data['Category']['title'] ?>

	<?php foreach($this->request->data['ArticleValue'] as $data): ?>
	<p><?= $data['Field']['title'] ?>: 
		<?php if (is_array(json_decode($data['data']))): ?>
			<?= debug(json_decode($data['data'])) ?>
		<?php else: ?>
			<?= $data['data'] ?>
		<?php endif ?>
	</p>
	<?php endforeach; ?>
</div>