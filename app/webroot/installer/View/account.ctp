<script>
$(document).ready(function() {
	$("#UserAccountForm").validate();

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

<h2>Admin Account</h2>

<?= $this->Form->create('User', array('class' => 'well')) ?>
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
		'class' => 'required'
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