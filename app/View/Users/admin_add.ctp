<h1>Add User</h1>

<?php
	echo $this->Form->create('User', array('type' => 'file', 'class' => 'well', 'id' => 'UserForm'));

	echo $this->Form->input('username', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('password', array('type' => 'password', 'class' => 'required'));
	echo $this->Form->input('password_confirm', array('type' => 'password', 'class' => 'required'));
	echo $this->Form->input('email', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('role_id', array('empty' => '- choose -', 'class' => 'required'));

	echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->hidden('last_reset_time', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
?>

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

<?= $this->Form->input('theme_id', array(
    'label' => 'Theme', 
    'empty' => '- Choose Theme -'
)) ?>

<?= $this->Form->input('User.settings.time_zone', array(
    'label' => 'Timezone',
    'empty' => '- Choose -',
    'options' => $timezones
)) ?>

<?= $this->Form->input('User.settings.avatar', array(
    'label' => 'Avatar',
    'type' => 'file'
)) ?>

<?= $this->Form->input('status', array(
    'options' => array(
        'In-Active',
        'Active'
    )
)) ?>

<br />
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>