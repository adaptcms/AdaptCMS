<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Login', array('action' => 'login')) ?>
<?php $this->Html->addCrumb('Forgot Password', null) ?>

<h2>
    Forgot Password
</h2>

<?php if (!empty($activate)): ?>
    <p>Check your email and click on the link - from there you will enter in a new password and can then login into your account.</p>
<?php else: ?>
    <p>Please enter your e-mail address or username and a link will be sent to you. Follow those instructions to change your password.</p>

    <?= $this->Form->create('User', array('class' => 'admin-validate')) ?>

        <?= $this->Form->input('username', array(
            'required' => false
        )) ?>

        <h4>OR</h4>

        <?= $this->Form->input('email', array(
            'class' => 'email',
            'required' => false
        )) ?>

        <label>Captcha</label>

        <div id="captcha">
            <?= $this->Captcha->form('data[User][captcha]') ?>
        </div>
    <?= $this->Form->end('Submit') ?>
<?php endif ?>