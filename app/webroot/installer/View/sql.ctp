<h2>SQL Insert</h2>

<?php if (empty($this->request->data)): ?>
	<?= $this->Form->create('', array('class' => 'well')) ?>
		<p>In this step, we will attempt to insert the sql data into your MySQL Database. If the attempt failed, please delete all AdaptCMS Tables and try again or visit the official site for support</p>
		<?= $this->Form->hidden('continue') ?>
	<?= $this->Form->end('Submit') ?>
<?php else: ?>
	<?php $fail = 0; ?>

	<?= $this->Form->create('', array('action' => 'account', 'class' => 'well')) ?>
		<p>
			<?php if ($sql_results['tables'][0] == $sql_results['tables'][1] && $sql_results['tables'][0] > 0): ?>
				<span class="notice success">
					SQL Tables Inserted Successfully
				</span>
			<?php else: ?>
				<span class="notice success">
					Unable to insert SQL Tables. <?= $this->Html->link('Click Here', array('action' => 'sql')) ?> to try again
				</span>
				<?php $fail = 1; ?>
			<?php endif ?>
		</p>
		<p>
			<?php if ($sql_results['data'][0] == $sql_results['data'][1] && $sql_results['data'][0] > 0): ?>
				<span class="notice success">
					SQL Data Inserted Successfully
				</span>
			<?php else: ?>
				<span class="notice success">
					Unable to insert SQL Data. <?= $this->Html->link('Click Here', array('action' => 'sql')) ?> to try again
				</span>
				<?php $fail = 1; ?>
			<?php endif ?>
		</p>
	<?php if($fail == 0): ?>
		<?= $this->Form->end('Continue') ?>
	<?php endif ?>
<?php endif ?>