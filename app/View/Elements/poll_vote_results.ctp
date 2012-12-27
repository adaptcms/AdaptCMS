<?= $this->Form->hidden('PollId', array('value' => $data['Poll']['id'])) ?>

<legend>
	<?= $data['Poll']['title'] ?>
</legend>

<?php foreach($data['PollValue'] as $row): ?>
	<?php $percent = @round($row['votes'] / $data['Poll']['total_votes'] * 100) ?>

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

<?php if ($data['Poll']['can_vote']): ?>
	<span class="pull-right">
		<?= $this->Html->link('Go Back', '#', array('class' => 'pull-right go-back')) ?>
	</span>
<?php endif ?>

<div class="clearfix"></div>