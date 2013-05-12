<?= $this->Form->create('Ticket', array(
	'url' => array(
		'action' => 'reply'
	)
)) ?>
	<h3>Post Reply</h3>

	<?= $this->Form->input('full_name', array(
		'type' => 'text', 
		'class' => 'required',
		'value' => ''
	)) ?>
	<?= $this->Form->input('email', array(
		'type' => 'text', 
		'class' => 'required email',
		'value' => !empty($current_user['email']) ? $current_user['email'] : ''
	)) ?>

	<?= $this->Form->input( 'message', array(
		'class' => 'span7',
		'style' => 'height: 100%',
		'label' => 'Message',
		'required' => true,
		'value' => ''
	)) ?>

	<?= $this->Form->hidden( 'parent_id', array(
		'value' => $ticket['Ticket']['id']
	)) ?>
	<?= $this->Form->hidden( 'subject', array(
		'value' => $ticket['Ticket']['subject']
	)) ?>
	<?= $this->Form->hidden( 'slug', array(
		'value' => $ticket['Ticket']['slug']
	)) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

	<?php if (!empty($captcha)): ?>
		<div id="captcha" style="margin-top: 10px;margin-bottom: 10px">
			<?= $this->Captcha->form() ?>
		</div>
	<?php endif ?>

	<?= $this->Form->button('Post Reply <i class="icon-ok"></i>', array(
		'type' => 'submit',
		'class' => 'btn btn-info',
		'escape' => false
	)) ?>
<?= $this->Form->end() ?>