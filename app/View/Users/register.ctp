<script type="text/javascript">
$(document).ready(function() {
    $("#UserPasswordConfirm").rules("add", {
        required: true,
        equalTo: "#UserPassword",
        messages: {
            equalTo: "Passwords do not match"
        }
    });

    $("#UserEmail").rules("add", {
        required: true,
        email: true
    });

    $("#UserUsername").live('change', function() {
        var username = $("#UserUsername").val();
        if (username.length > 0) {
            $.post("<?= $this->webroot ?>ajax/users/check_user/", {data:{User:{username:username}}}, function(data) {
                if (data == 1) {
                    $("#username_ajax_result").hide();
                    $("#submit").attr('disabled', false);
                } else {
                    $("#username_ajax_result").attr('class', 'error-message');
                    $("#username_ajax_result").text('Username is already in use');
                    $("#username_ajax_result").css('display','inline');
                    $("#submit").attr('disabled', true);
                }
            });
        }
    });

    $(".security-question").live('change', function() {
        var id = $(this).attr('id');

        if ($(this).val()) {
            $("div#" + id).show();
        } else {
            $("div#" + id).hide();
        }

        $.each($(".security-question"), function(i, row) {
            if ($(this).attr('id') != id) {
                var new_id = $(this).attr('id');
                
                $.each($("#UserSecurityQuestionHidden option"), function(key, val) {
                    var find = $("form").find($(".security-question option[value='" + $(this).val() + "']:selected")).val();
                    
                    if ($(this).val() == find && find) {
                        $("#" + new_id + " option[value='" + $(this).val() + "']:not(:selected)").remove();
                    } else {
                        if ($("#" + new_id + " option[value='" + $(this).val() + "']").length == 0) {
                            $("#" + new_id).append("<option value='" + $(this).val() + "'>" + $(this).html() + "</option>");
                        }
                    }
                });
            }
        });
    });
});
</script>

<?php $this->Html->addCrumb('Register', null) ?>

<div class="pull-left">
    <h1>New User</h1>

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
                            'class' => 'required',
                            'label' => 'Security Answer '.$i
                    )) ?>
                </div>
            <?php endfor ?>
        <?php endif ?>
    <?php endif ?>

    <?php if (!empty($captcha_setting)): ?>
        <?= $this->Captcha->form() ?>
    <?php endif ?>

    <?= $this->Form->end(array(
        'label' => 'Submit',
        'class' => 'btn',
        'id' => 'submit'
    )); ?>
</div>
<div class="pull-right">
    <h1>3rd Party Signup</h1>

    <?php if (!empty($this->Facebook)): ?>
        <?= $this->Facebook->registration() ?>
    <?php endif ?>
</div>
<div class="clearfix"></div>