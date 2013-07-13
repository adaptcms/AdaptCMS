$(document).ready(function() {
    if ($('.admin-validate').length)
    {
        $("#UserPasswordConfirm").rules("add", {
            required: false,
            equalTo: "#UserPassword",
            messages: {
                equalTo: "Passwords do not match"
            }
        });

        $("#UserEmail").rules("add", {
            required: true,
            email: true
        });

        $(".security-question").on('change', function() {
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

        if ($(".security-question").length > 0) {
            $.each($(".security-question"), function() {
                if ($(this).val()) {
                    $(this).parent().next().show();
                }
            });
        }
    }
});