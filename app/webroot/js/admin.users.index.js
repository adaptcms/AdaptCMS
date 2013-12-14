$(document).ready(function(){
    if ($('#user-change-status').length)
        $(".user-status").live('click', function () {
            var el = $(this);
            var user_id = el.attr('data-id');
            var status = el.hasClass('icon-remove-sign');
            var new_status = (status ? 1 : 0);

            $.post($('#webroot').text() + "admin/users/ajax_change_user/",
            {
                data: {
                    User: {
                        id: user_id,
                        status: new_status
                    }
                }
            }, function (response) {
                var change = $('#user-change-status');

                if (change.length != 0) {
                    change.replaceWith(response.data);
                } else {
                    $(response.data).insertAfter($(".breadcrumb"));
                }

                if ($('#user-change-status').hasClass('alert-success')) {
                    if (new_status == 1)
                    {
                        el.removeClass('icon-remove-sign').addClass('icon-ok-sign');
                    }
                    else
                    {
                        el.addClass('icon-remove-sign').removeClass('icon-ok-sign');
                    }
                }
            }, 'json');
        });
});