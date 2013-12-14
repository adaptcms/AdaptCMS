<?php if (!empty($data['Poll']['title'])): ?>
    <?= $this->Form->hidden('PollId', array('value' => $data['Poll']['id'])) ?>

    <?php if (!empty($msg['type'])): ?>
        <?= $this->Element('flash_' . $msg['type'], array('message' => $msg['message'])) ?>
	<?php endif ?>

    <legend>
        <?= $data['Poll']['title'] ?>
    </legend>

    <?php foreach($data['PollValue'] as $row): ?>
        <?= $row['title'] ?> - <?= $row['votes'] ?> Votes
        <div class="progress">
            <div class="progress-bar" style="width: <?= $row['percent'] ?>%;"></div>
        </div>
    <?php endforeach ?>

    <span class="pull-left">
		<strong>
            Total Votes:
        </strong>

        <?= $data['Poll']['total_votes'] ?>
	</span>

    <?php if (!empty($permissions['related']['polls']) && $data['Poll']['can_vote'] ||
		$data['Poll']['can_vote'] && !empty($block_permissions[$data['Block']['title']]['related'])): ?>
        <span class="pull-right">
			<?= $this->Html->link('Go Back', '#', array(
                'class' => 'pull-right go-back',
                'data-block-title' => $data['Block']['title']
            )) ?>
		</span>
    <?php endif ?>

    <div class="clearfix"></div>
<?php endif ?>