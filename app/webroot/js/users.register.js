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
            $.post($('#webroot').text() +  "ajax/users/check_user/", { data:{ User:{ username:username } } }, function(response) {
                var container =  $("#username_ajax_result");
                
                if (response.data == 1) {
                    container.hide();
                    $("#submit").attr('disabled', false);
                } else {
                    container.attr('class', 'error-message');
                    container.text('Username is already in use');
                    container.css('display','inline');
                    $("#submit").attr('disabled', true);
                }
            }, 'json');
        }
    });

    $(".security-question").live('change', function() {
        var id = $(this).attr('id');

        if ($(this).val()) {
            $("div#" + id).show();
        } else {
            $("div#" + id).hide();
        }

        $.each($(".security-question"), function() {
            if ($(this).attr('id') != id) {
                var new_id = $(this).attr('id');

                $.each($("#UserSecurityQuestionHidden option"), function() {
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