$(document).ready(function(){
    var change_status = $('#user-change-status');

    if (change_status.length)
    {
        $(".user-status").live('click', function() {
            if ($(this).hasClass('icon-remove-sign')) {
                var user_id = $(this).attr('data-id');
                var new_status = 1;

                $.post($('#webroot').text() + "ajax/users/change_user/",
                    {
                        data:
                        {
                            User:
                            {
                                id: user_id,
                                status: new_status
                            }
                        }
                    }, function(data) {
                    if (change_status.length != 0) {
                        change_status.replaceWith(data);
                    } else {
                        $(data).insertBefore($(".span9 h1"));
                    }

                    if (change_status.hasClass('alert-success')) {
                        $("#" + user_id).removeClass('icon-remove-sign').addClass('icon-ok-sign');
                    }
                });
            }
        });
    }
});