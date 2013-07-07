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
            $.post($('#webroot').text() +  "ajax/users/check_user/", {data:{User:{username:username}}}, function(data) {
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

    var security_answers = $('.security-answer');

    if (security_answers.length)
    {
        $.each(security_answers, function() {
           $(this).parent().parent().show();
        });
    }
});