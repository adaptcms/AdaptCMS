<?= $this->Form->create('Comment', array('class' => 'PostComment')) ?>

	<?= $this->Form->input('comment_text', array(
		'class' => 'span5',
		'label' => false,
		'placeholder' => 'Enter in your comment...'
	)) ?>

	<?php if (!empty($this->request->data['Article']['id'])): ?>
		<?= $this->Form->hidden('article_id', array(
			'value' => $this->request->data['Article']['id']
		)) ?>
	<?php endif ?>

	<?= $this->Form->button('Post Comment', array(
		'type' => 'submit',
		'class' => 'btn'
	)) ?>

<?= $this->Form->end() ?>