<?php
    $this->TinyMce->editor();
?>

<h1>
	View Message
	<small>
		<?= $subject ?>
	</small>
</h1>

<?= $this->Html->link('« Back to Messages', array('action' => 'index'), array(
	'class' => 'btn btn-primary pull-right', 
	'style' => 'margin-bottom: 10px;margin-top: -10px'
)) ?>
<div class="clearfix"></div>

<div class="messages">
	<?php foreach($this->request->data as $data): ?>
		<div class="span10 well message<?= ($sender == $data['Sender']['id'] ? ' no-marg-left' : '') ?>" id="message-<?= $data['Message']['id'] ?>">
			<a name="message-<?= $data['Message']['id'] ?>"></a>
			<div class="btn-toolbar pull-right">
				<?php if ($data['Sender']['username'] == $username && $data['Message']['sender_archived_time'] != '0000-00-00 00:00:00' ||
						$data['Receiver']['username'] == $username && $data['Message']['receiver_archived_time'] != '0000-00-00 00:00:00'):
				?>
					<span class="label label-info btn-group">
						Archived
					</span>
				<?php endif ?>
				<?php if ($this->Time->wasWithinLast('15 minutes', $data['Message']['created'])): ?>
					<span class="label label-success btn-group">
						New Message!
					</span>
				<?php endif ?>
				<?php if ($data['Message']['is_read'] == 0): ?>
					<span class="label label-info btn-group">
						Unread
					</span>
				<?php endif ?>
			</div>

			<div class="header pull-left">
				<dl class="dl-horizontal">
					<dt>
						From
					</dt>
					<dd>
						<?php if (!empty($data['Sender']['username'])): ?>
							<?= $this->Html->link($data['Sender']['username'], array(
								'controller' => 'users',
								'action' => 'profile',
								$data['Sender']['username']
							)) ?>
						<?php endif ?>
					</dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>To</dt>
					<dd>
						<?php if (!empty($data['Receiver']['username'])): ?>
							<?= $this->Html->link($data['Receiver']['username'], array(
								'controller' => 'users',
								'action' => 'profile',
								$data['Receiver']['username']
							)) ?>
						<?php endif ?>
					</dd>
				</dl>

				<em>
					@ 
					<?= $this->Admin->time($data['Message']['created'], 'words') ?>
				</em>
			</div>
			<div class="clearfix"></div>

			<div class="body span8 no-marg-left" style="padding-top:10px">
				<?= $data['Message']['message'] ?>
			</div>
		</div>

		<div class="clearfix"></div>

		<?php if ($data['Message']['parent_id'] == 0): ?>
			<?php $parent_id = $data['Message']['id'] ?>
			<?php $title = 'RE: ' . $data['Message']['title'] ?>
		<?php else: ?>
			<?php $parent_id = $data['Message']['parent_id'] ?>
		<?php endif ?>
	<?php endforeach ?>
</div>

<?= $this->element('flash_error', array('message' => 'Please enter in a message')) ?>
<?= $this->element('flash_success', array('message' => 'Your message has been sent.')) ?>

<?= $this->Form->create('Message', array('class' => 'SendMessage')) ?>

	<?= $this->Form->input('message', array(
		'class' => 'span7',
		'style' => 'height: 100%',
		'placeholder' => 'Enter in your message...'
	)) ?>

	<?= $this->Form->hidden('parent_id', array(
		'value' => $parent_id
	)) ?>
	<?= $this->Form->hidden('title', array(
		'value' => $title
	)) ?>
	<?= $this->Form->hidden('receiver_user_id', array(
		'value' => ($this->request->data[0]['Receiver']['id'] == $this->Session->read('Auth.User.id') ? $this->request->data[0]['Sender']['id'] : $this->request->data[0]['Receiver']['id'])
	)) ?>

	<?= $this->Form->button('Send Reply', array(
		'type' => 'submit',
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px;margin-bottom: 10px'
	)) ?>
<?= $this->Form->end() ?>