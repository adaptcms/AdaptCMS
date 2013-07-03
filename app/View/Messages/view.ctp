<?php $this->Html->addCrumb('Profile', array(
    'action' => 'profile',
    'controller' => 'users',
    $this->Session->read('Auth.User.username')
)) ?>
<?php $this->Html->addCrumb('Messages', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('View Message', null) ?>

<?php $this->TinyMce->editor(array('simple' => true)) ?>
<?= $this->Html->script('jquery.blockui.min.js') ?>

<h1>
	View Message
	<small>
		<?= $subject ?>
	</small>
</h1>

<?= $this->Html->link('« Back to Messages', array('action' => 'index'), array(
	'class' => 'btn btn-primary pull-right', 
	'style' => 'margin-bottom: 10px;'
)) ?>
<div class="clearfix"></div>

<div class="messages">
	<?php foreach($messages as $message): ?>
		<div class="span10 well message<?= ($sender == $message['Sender']['id'] ? ' no-marg-left' : '') ?>" id="message-<?= $message['Message']['id'] ?>">
			<a name="message-<?= $message['Message']['id'] ?>"></a>
			<div class="btn-toolbar pull-right">
				<?php if ($message['Sender']['username'] == $username && $message['Message']['sender_archived_time'] != '0000-00-00 00:00:00' ||
						$message['Receiver']['username'] == $username && $message['Message']['receiver_archived_time'] != '0000-00-00 00:00:00'):
				?>
					<span class="label label-info btn-group">
						Archived
					</span>
				<?php endif ?>
				<?php if ($this->Time->wasWithinLast('15 minutes', $message['Message']['created'])): ?>
					<span class="label label-success btn-group">
						New Message!
					</span>
				<?php endif ?>
				<?php if ($message['Message']['is_read'] == 0): ?>
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
						<?php if (!empty($message['Sender']['username'])): ?>
							<?= $this->Html->link($message['Sender']['username'], array(
								'controller' => 'users',
								'action' => 'profile',
								$message['Sender']['username']
							)) ?>
						<?php endif ?>
					</dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>To</dt>
					<dd>
						<?php if (!empty($message['Receiver']['username'])): ?>
							<?= $this->Html->link($message['Receiver']['username'], array(
								'controller' => 'users',
								'action' => 'profile',
								$message['Receiver']['username']
							)) ?>
						<?php endif ?>
					</dd>
				</dl>

				<em>
					@ 
					<?= $this->Admin->time($message['Message']['created'], 'words') ?>
				</em>
			</div>
			<div class="clearfix"></div>

			<div class="body span8 no-marg-left" style="padding-top:10px">
				<?= $message['Message']['message'] ?>
			</div>
		</div>

		<div class="clearfix"></div>

		<?php if ($message['Message']['parent_id'] == 0): ?>
			<?php $parent_id = $message['Message']['id'] ?>
			<?php $title = 'RE: ' . $message['Message']['title'] ?>
		<?php else: ?>
			<?php $parent_id = $message['Message']['parent_id'] ?>
		<?php endif ?>
	<?php endforeach ?>
</div>

<?= $this->element('flash_error', array('message' => 'Please enter in a message')) ?>
<?= $this->element('flash_success', array('message' => 'Your message has been sent.')) ?>

<?= $this->Form->create('Message', array('class' => 'SendMessage')) ?>

	<?= $this->Form->input('message', array(
		'class' => 'span7',
		'style' => 'height: 100%',
        'required' => false,
		'placeholder' => 'Enter in your message...'
	)) ?>

	<?= $this->Form->hidden('parent_id', array(
		'value' => $parent_id
	)) ?>
	<?= $this->Form->hidden('title', array(
		'value' => $title
	)) ?>
	<?= $this->Form->hidden('receiver_user_id', array(
		'value' => ($messages[0]['Receiver']['id'] == $this->Session->read('Auth.User.id') ? $messages[0]['Sender']['id'] : $messages[0]['Receiver']['id'])
	)) ?>

	<?= $this->Form->button('Send Reply', array(
		'type' => 'submit',
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px;margin-bottom: 10px'
	)) ?>
<?= $this->Form->end() ?>