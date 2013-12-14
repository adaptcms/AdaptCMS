<?php if (!empty($data)): ?>
    <?php if ($data['Poll']['can_vote'] && !empty($block_permissions[$data['Block']['title']]['related'])): ?>
        <?= $this->element('Polls.poll_vote', array('data' => $data)) ?>
    <?php else: ?>
        <?= $this->element('Polls.poll_vote_results', array('data' => $data)) ?>
    <?php endif ?>
<?php endif ?>