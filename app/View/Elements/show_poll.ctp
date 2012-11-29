<?php if (!empty($data)): ?>
	<?php if ($data['Poll']['can_vote']): ?>
		<?= $this->element('poll_vote', array('data' => $data)) ?>
	<?php else: ?>
		<?= $this->element('poll_vote_results', array('data' => $data)) ?>
	<?php endif ?>
<?php endif ?>