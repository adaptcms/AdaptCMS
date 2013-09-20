<?php $this->Html->addCrumb('Register', null) ?>

<h1>Sign Up</h1>

<?php
    echo $this->Form->create('User', array('class' => 'admin-validate'));
    echo $this->Form->input('username', array('class' => 'required'));
?>
<span id="username_ajax_result"></span>
<?php
    echo $this->Form->input('password', array('type' => 'password', 'class' => 'required'));
    echo $this->Form->input('password_confirm', array('type' => 'password', 'class' => 'required'));
    echo $this->Form->input('email', array('class' => 'required'));

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
                        'class' => 'required security-answer',
                        'label' => 'Security Answer '.$i
                )) ?>
            </div>
        <?php endfor ?>
    <?php endif ?>
<?php endif ?>

<?php if (!empty($captcha_setting)): ?>
    <div id="captcha" class="input text">
        <?= $this->Captcha->form() ?>
    </div>
<?php endif ?>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn',
    'id' => 'submit'
)); ?>

<?php if (!empty($this->Facebook)): ?>
    <h1>3rd Party Signup</h1>

    <?php if (!empty($this->Facebook)): ?>
        <?= $this->Facebook->registration() ?>
    <?php endif ?>
<?php endif ?>
<div class="clearfix"></div>