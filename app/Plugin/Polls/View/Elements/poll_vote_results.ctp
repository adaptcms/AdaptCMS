<?php if (!empty($data['Poll']['title'])): ?>
    <?= $this->Form->hidden('PollId', array('value' => $data['Poll']['id'])) ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            <?= $error ?>
        </div>
    <?php endif ?>

    <legend>
        <?= $data['Poll']['title'] ?>
    </legend>

    <?php foreach($data['PollValue'] as $row): ?>
        <?php if ($data['Poll']['total_votes'] == 0): ?>
            <?php $percent = 0 ?>
        <?php else: ?>
            <?php $percent = round($row['votes'] / $data['Poll']['total_votes'] * 100) ?>
        <?php endif ?>

        <?= $row['title'] ?> - <?= $row['votes'] ?> Votes
        <div class="progress">
            <div class="bar" style="width: <?= $percent ?>%;"></div>
        </div>
    <?php endforeach ?>

    <span class="pull-left">
		<strong>
            Total Votes:
        </strong>

        <?= $data['Poll']['total_votes'] ?>
	</span>

    <?php if ($data['Poll']['can_vote'] && !empty($block_permissions[$data['Block']['title']]['related'])): ?>
        <span class="pull-right">
			<?= $this->Html->link('Go Back', '#', array(
                'class' => 'pull-right go-back',
                'data-block-title' => $data['Block']['title']
            )) ?>
		</span>
    <?php endif ?>

    <div class="clearfix"></div>
<?php endif ?>