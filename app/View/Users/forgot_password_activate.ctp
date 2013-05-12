<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Login', array('action' => 'login')) ?>
<?php $this->Html->addCrumb('Forgot Password Activation', null) ?>

<script type="text/javascript">
$(document).ready(function() {
    if ($("#UserPasswordConfirm").length == 1) {
        $("#UserPasswordConfirm").rules("add", {
            required: true,
            equalTo: "#UserPassword",
            messages: {
                equalTo: "Passwords do not match"
            }
        });
    }
});
</script>

<h2>
    Forgot Password - Activate
</h2>

<p>
    Please fill out the below form to update your password. If you have not submitted a request, 
    <?= $this->Html->link('click here', array(
        'action' => 'forgot_password'
    )) ?> 
    to submit a forgot password request.
</p>

<?= $this->Form->create('User', array('class' => 'admin-validate')) ?>

    <?= $this->Form->input('username', array(
        'type' => 'text', 
        'class' => 'required'
    )) ?>

    <?= $this->Form->input('activate_code', array(
        'type' => 'text', 
        'class' => 'required'
    )) ?>

    <?= $this->Form->input('password', array(
        'type' => 'password',
        'label' => 'New Password',
        'class' => 'required'
    )) ?>
    <?= $this->Form->input('password_confirm', array(
        'type' => 'password', 
        'class' => 'required'
    )) ?>

    <label>Captcha</label>

    <div id="captcha">
        <?= $this->Captcha->form('data[User][captcha]') ?>
    </div>
<?= $this->Form->end('Submit') ?>