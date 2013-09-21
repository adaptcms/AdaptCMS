<?php $this->Html->addCrumb('Polls',null) ?>
<?php $this->Html->addCrumb('List', null) ?>

<?php $this->set('title_for_layout', 'Polls List') ?>

<h1>Polls Archive</h1>

<?php if (empty($polls)): ?>
	<p>
		Sorry, but there are no polls available at this time. Please check back soon!
	</p>
<?php else: ?>
	<ul class="list-unstyled clearfix">
		<?php foreach($polls as $poll): ?>
			<li class="col-lg-7" style="margin-bottom: 15px;">
				<?php if ($poll['Poll']['can_vote']): ?>
					<?= $this->Element('Polls.poll_vote', array('data' => $poll)) ?>
				<?php else: ?>
					<?= $this->Element('Polls.poll_vote_results', array('data' => $poll)) ?>
				<?php endif ?>
			</li>
		<?php endforeach ?>
	</ul>

	<?= $this->Element('pagination') ?>
<?php endif ?>