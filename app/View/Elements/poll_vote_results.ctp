<legend>
	<?= $data['Poll']['title'] ?>
</legend>

<?php foreach($data['PollValue'] as $row): ?>
	<?php $percent = round($row['votes'] / $data['Poll']['total_votes'] * 100) ?>

	<?= $row['title'] ?> - <?= $row['votes'] ?> Votes
	<div class="progress">
	  <div class="bar" style="width: <?= $percent ?>%;"></div>
	</div>
<?php endforeach ?>

<strong>
	Total Votes:
</strong>

<?= $data['Poll']['total_votes'] ?>