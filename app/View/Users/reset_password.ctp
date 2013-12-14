<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Login', array('action' => 'login')) ?>
<?php $this->Html->addCrumb('Reset Password', null) ?>

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

    jQuery.validator.addMethod("notEqual", function(value, element, param) {
      return this.optional(element) || value != $(param).val();
    }, "Your new password must be different than your previous one");

    $("#UserPassword").rules("add", {
        required: true,
        notEqual: "#UserPasswordCurrent"
    });

    $("#UserPasswordCurrent").rules("add", {
        required: true,
        notEqual: "#UserPassword"
    });
});
</script>

<h2>
    Reset Password
</h2>

<p>The <?= $password_reset['SettingValue']['data'] ?> day limit has been reached since your last password change, please enter a new one below</p>

<?= $this->Form->create('User', array('class' => 'admin-validate')) ?>

    <?= $this->Form->input('username', array('type' => 'text', 'class' => 'required')) ?>

    <?= $this->Form->input('password_current', array(
            'type' => 'password', 
            'label' => 'Current Password',
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