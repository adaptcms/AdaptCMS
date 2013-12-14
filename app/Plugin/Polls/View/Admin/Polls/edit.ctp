<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Polls', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Poll', null) ?>

<?= $this->AdaptHtml->script('bootstrap-datepicker') ?>
<?= $this->Html->css("datepicker") ?>

<?= $this->Html->script('Polls.admin') ?>
<?= $this->Html->css('Polls.admin') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Poll</a>
	</li>
	<?php if ($this->request->data['Poll']['total_votes'] > 0): ?>
		<li>
			<a href="#votes" data-toggle="tab">Vote Totals</a>
		</li>
		<li>
			<a href="#vote-breakdown" data-toggle="tab">Vote Breakdown</a>
		</li>
	<?php endif ?>
	<div class="right hidden-phone">
		<?= $this->Html->link(
			'<i class="icon-chevron-left"></i> Return to Index',
			array('action' => 'index'),
			array('class' => 'btn', 'escape' => false
			)) ?>
		<?= $this->Html->link(
			'<i class="icon-trash icon-white"></i> Delete',
			array('action' => 'delete', $this->request->data['Poll']['id'], $this->request->data['Poll']['title']),
			array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this poll?')"));
		?>
	</div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="main">
		<?= $this->Form->create('Poll', array('class' => 'well admin-validate')) ?>
			<h2>Edit Poll</h2>

			<?= $this->Form->input('title', array(
		        'type' => 'text',
		        'class' => 'required'
		    )) ?>
		    <?= $this->Form->input('article_id', array(
		        'label' => 'Attach to Article',
		        'empty' => ' - choose - '
		    )) ?>

			<?= $this->Form->input('start_date', array(
				'type' => 'text',
				'class' => 'datepicker',
				'data-date-format' => 'yyyy-mm-dd'
			)) ?>
			<?= $this->Form->input('end_date', array(
				'type' => 'text',
				'class' => 'datepicker',
				'data-date-format' => 'yyyy-mm-dd'
			)) ?>

		    <div id="options">
		        <?php foreach($this->request->data['PollValue'] as $key => $data): ?>
		            <div id='option<?= $key ?>'>
		                <div class='input text'>
		                    <?= $this->Form->input('PollValue.' . $key . '.title', array(
		                        'label' => 'Option '.$key,
		                        'value' => $data['title'],
		                        'class' => 'required option pull-left',
		                        'div' => false
		                    )) ?>

		                  <?= $this->Form->button('<i class="icon-trash icon-white poll-delete"></i> Delete', array(
		                     'type' => 'button',
		                     'class' => 'btn btn-danger poll-remove pull-right',
		                     'div' => false
		                  )) ?>
		               </div>
		               <div class="clearfix"></div>

		               <?= $this->Form->input('PollValue.' . $key . '.id', array(
		                  'value' => $data['id']
		               )) ?>
		               <?= $this->Form->hidden('PollValue.' . $key . '.delete', array(
		                  'value' => 0,
		                  'class' => 'delete'
		               )) ?>
		            </div>
		        <?php endforeach ?>
		    </div>
		    <div class="clearfix"></div>

		    <?= $this->Form->input('id', array('type' => 'hidden')) ?>

		    <div class="btn-group">
		        <?= $this->Form->button('Add Option', array(
		            'type' => 'button',
		            'id' => 'poll-option-add',
		            'class' => 'btn btn-warning'
		        )) ?>
		        <?= $this->Form->end(array(
		            'label' => 'Submit',
		            'class' => 'btn btn-primary',
		            'div' => false
		        )) ?>
		    </div>
	</div>
	<?php if ($this->request->data['Poll']['total_votes'] > 0): ?>
		<div class="tab-pane well" id="votes">
			<h2>Vote Totals</h2>

			<div class="span5 lg-col-5 no-marg-left">
				<?php foreach($this->request->data['PollValue'] as $row): ?>
					<?= $row['title'] ?> - <?= $row['votes'] ?> Votes
					<div class="progress">
						<div class="progress-bar bar" style="width: <?= $row['percent'] ?>%;"></div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="tab-pane well" id="vote-breakdown">
			<h2>Vote Breakdown</h2>

			<?php foreach($this->request->data['PollValue'] as $row): ?>
				<?php if (!empty($row['PollVotingValue'])): ?>
					<h4><?= $row['title'] ?></h4>

					<ul class="unstyled list-unstyled">
						<?php foreach($row['PollVotingValue'] as $vote): ?>
							<li>
								<?php if (empty($vote['user_id'])): ?>
									Guest / <?= $vote['user_ip'] ?>
								<?php else: ?>
									<?= $this->Html->link($vote['User']['username'], array(
										'plugin' => null,
										'controller' => 'users',
										'action' => 'edit',
										$vote['User']['id']
									)) ?> (<em><?= $vote['user_ip'] ?></em>)
								<?php endif ?>
								@ <?= $this->Admin->time($vote['created']) ?>
							</li>
						<?php endforeach ?>
					</ul>

					<div class="clearfix"></div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	<?php endif ?>
</div>