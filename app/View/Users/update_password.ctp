<script type="text/javascript">
$(document).ready(function() {
    $("#UserUpdatePasswordForm").validate();

    if ($("#UserPasswordConfirm").length == 1) {
        $("#UserPasswordConfirm").rules("add", {
            required: true,
            equalTo: "#UserPassword",
            messages: {
                equalTo: "Passwords do not match"
            }
        });
    }

    <?php if (!empty($this->params->named['change']) && $this->params->named['change'] == "reset" && !empty($password_reset)): ?>
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
    <?php endif ?>

    if (1 == 2) {
    $("#UserUpdatePasswordForm").submit(function(e) {
        e.preventDefault();
        var username = $("#UserUsername").val();
        if (username.length > 0) {
            $.post("<?= $this->webroot ?>ajax/users/forgot_password/", {data:{User:{username:username}}}, function(data) {
                var new_data = jQuery.parseJSON(data);
                var questions = [];
                var answers = [];
                var count = 0;

                $.each(new_data, function(i, row) {
                    questions[i] = row.question;
                    answers[i] = row.answer;
                    count++;
                });

                var random = Math.floor(Math.random()*count+1)

                console.log(random + '/' + questions[random] + '/' + answers[random]);
            });
        }
    });
    }
});
</script>

<h2>
    <?php if (!empty($this->params->named['change']) && $this->params->named['change'] == "forgot"): ?>
        Forgot
    <?php else: ?>
        Update
    <?php endif ?>
    Password
</h2>

<?php if (!empty($this->params->named['change']) && $this->params->named['change'] == "reset" && !empty($password_reset)): ?>
    <p>The <?= $password_reset['SettingValue']['data'] ?> day limit has been reached since your last password change, please enter a new one below</p>
<?php elseif(!empty($this->params->named['change']) && $this->params->named['change'] == "forgot"): ?>
    <p>Please enter your e-mail address and a link will be sent to you, follow those instructions to change your password.</p>
<?php endif ?>

<?= $this->Form->create('User', array('change' => 'forgot')) ?>

<?php if (!empty($this->params->named['change']) &&
          $this->params->named['change'] == "forgot" &&
          empty($activate)): ?>
    <?= $this->Form->input('email', array(
            'class' => 'required'
    )) ?>
<?php else: ?>
    <?php if (empty($this->params->named['username'])): ?>
        <?= $this->Form->input('username', array('type' => 'text', 'class' => 'required')) ?>
    <?php endif ?>

    <?php if (empty($this->params->named['activate']) && !empty($this->params->named['change']) &&
          $this->params->named['change'] == "forgot"): ?>
        <?= $this->Form->input('activate_code', array('type' => 'text', 'class' => 'required')) ?>
    <?php endif ?>

    <?php if (!empty($this->params->named['change']) && $this->params->named['change'] == "reset" && !empty($password_reset)): ?>
        <?= $this->Form->input('password_current', array(
                'type' => 'password', 
                'label' => 'Current Password',
                'class' => 'required'
        )) ?>
    <?php endif ?>

    <?= $this->Form->input('password', array(
            'type' => 'password',
            'label' => 'New Password',
            'class' => 'required'
    )) ?>
    <?= $this->Form->input('password_confirm', array(
            'type' => 'password', 
            'class' => 'required'
    )) ?>
<?php endif ?>

<?= $this->Form->end('Submit') ?>