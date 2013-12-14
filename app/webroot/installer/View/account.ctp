<?= $this->Html->script('admin.users.js') ?>

<h2>Admin Account</h2>

<?= $this->Form->create('User', array('class' => 'well admin-validate')) ?>
	<?= $this->Form->input('username', array(
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('password', array(
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('password_confirm', array(
		'class' => 'required',
		'type' => 'password',
		'label' => 'Confirm Password'
	)) ?>
	<?= $this->Form->input('email', array(
		'class' => 'required email'
	)) ?>

	<?php if (!empty($this->request->data['SecurityQuestions']['SettingValue']['data'])): ?>
	    <?php if (!empty($security_options)): ?>
	        <?= $this->Form->input('security_question_hidden', array(
	                'options' => $security_options,
	                'label' => false,
	                'div' => false,
	                'style' => 'display:none'
	        )) ?>
	        <?php for($i = 1; $i <= $this->request->data['SecurityQuestions']['SettingValue']['data']; $i++): ?>
	            <?= $this->Form->input('Security.'.$i.'.question', array(
	                    'empty' => '- choose -', 
	                    'class' => 'required security-question', 
	                    'options' => $security_options,
	                    'label' => 'Security Question '.$i
	            )) ?>
	            <div id="Security<?= $i ?>Question" style="display: none">
	                <?= $this->Form->input('Security.'.$i.'.answer', array(
	                        'class' => 'required',
	                        'label' => 'Security Answer '.$i
	                )) ?>
	            </div>
	        <?php endfor ?>
	    <?php endif ?>
	<?php endif ?>
<?= $this->Form->end('Continue') ?>