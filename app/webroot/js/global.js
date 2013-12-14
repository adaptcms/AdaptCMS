$.ajaxSetup({ cache: false });

if (typeof $.noty != 'undefined') {
    $.noty.defaults = {
        layout: 'bottomRight',
        theme: 'defaultTheme',
        type: 'alert',
        text: '',
        dismissQueue: true, // If you want to use queue feature set this true
        template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
        animation: {
            open: {height: 'toggle'},
            close: {height: 'toggle'},
            easing: 'swing',
            speed: 500 // opening & closing animation speed
        },
        timeout: false, // delay for closing event. Set false for sticky notifications
        force: false, // adds notification to the beginning of queue when set to true
        modal: false,
        maxVisible: 5, // you can set max visible notification for dismissQueue true option
        closeWith: ['click'], // ['click', 'button', 'hover']
        callback: {
            onShow: function() {},
            afterShow: function() {},
            onClose: function() {},
            afterClose: function() {}
        },
        buttons: false // an array of buttons
    };
}

login_notice = [];

$(document).ajaxError(function(e, jqXHR, ajaxSettings, thrownError) {
    if (typeof $.noty != 'undefined' && jqXHR.status == '403') {
        $('#UserLoginForm').live('submit', ajaxLogin);

        $.get($("#webroot").text() + "login", [], function(response) {
            if (response.match(/UserLoginForm/)) {
                var response = response.replace('<h1>Login</h1>', '');

                setLoginNotice(noty({
                    text     : '<strong>Error</strong> <br /> Your session has expired. To keep your progress, please login below:<br /><br />' + response,
                    type     : 'error',
                    layout   : 'bottomRight',
                    closeWith: ['button']
                }));

                $('#UserLoginForm').validate();
            }
            else
            {
                noty({
                    text     : '<strong>Error</strong> <br /> Sorry, you do not appear to have access to that function.',
                    type     : 'error',
                    closeWith: ['click']
                });
            }
        });
    }
});

function triggerTimeoutWarning5Min()
{
    var n = noty({
        text     : "<strong>Note</strong> <br /> Your session will expire in less than 5 minutes. Please click on this box to continue your session so you won't lose your progress.",
        type     : 'information',
        layout   : 'bottomRight',
        closeWith: ['click'],
        callback : {
            afterClose: function () {
                $.get($("#webroot").text() + "admin/articles", [], function() {
                    noty({
                        text     : '<strong>Success</strong> <br /> Your session has been kept alive.',
                        type     : 'success',
                        timeout: 5000,
                        closeWith: ['click']
                    });

                    clearTimeout(setTimeout("triggerTimeoutWarning5Min()", 60000 * 25));
                    setTimeout("triggerTimeoutWarning5Min()", 60000 * 25);
                });
            }
        }
    });
}

function triggerTimeoutWarning2Min()
{
    var n = noty({
        text     : "<strong>Note</strong> <br /> Your session will expire in less than 2 minutes! Please click on this box to continue your session so you won't lose your progress.",
        type     : 'alert',
        layout   : 'bottomRight',
        closeWith: ['click'],
        callback : {
            afterClose: function () {
                $.get($("#webroot").text() + "admin/articles", [], function() {
                    noty({
                        text     : '<strong>Success</strong> <br /> Your session has been kept alive.',
                        type     : 'success',
                        timeout: 5000,
                        closeWith: ['click']
                    });

                    clearTimeout(setTimeout("triggerTimeoutWarning5Min()", 60000 * 28));
                    setTimeout("triggerTimeoutWarning5Min()", 60000 * 28);
                });
            }
        }
    });
}

$(document).ready(function() {
	changeRequiredFields();

    var page = window.location.pathname;
    if (page.match(/admin/) && page.match(/add/) || page.match(/admin/) && page.match(/edit/)) {
        setTimeout("triggerTimeoutWarning5Min()", 60000 * 25);
        setTimeout("triggerTimeoutWarning2Min()", 60000 * 28);
    }

	/**
	 * convience function, button class of 'btn btn-info reset-field ArticleTitle'
	 * when clicked, will reset the field with the id of 'ArticleTitle'
	 */
	$(".reset-field").on('click', function() {
		var reset_class = $(this).attr('class');

        if (reset_class)
        {
            var new_class = reset_class.split(' ').slice(-1);
        }

        if (new_class)
        {
		    $("#" + new_class).val('');
        }
    });

	/*
	 *
	*/
	$("input[type='checkbox'].check-all").on('change', function(e) {
		e.preventDefault();

		if (!$(this).attr('checked')) {
			$(this).next().text('Check All');
			$(this).parent().parent().find(':checkbox:not(.check-all)').attr('checked', false);
		} else {
			$(this).next().text('Un-Check All');
			$(this).parent().parent().find(':checkbox:not(.check-all)').attr('checked', true);
		}
	});

	if ($(".admin-validate").length > 0)
	{
		$.each($(".admin-validate"), function() {
			$(this).validate();
		});
	}

	$('.btn-confirm').on('click', function(e) {
		if ($(this).attr('title'))
		{
			var text = $(this).attr('title');
		}
		else
		{
			var text = 'this item';
		}

		return confirm('Are you sure you wish to delete ' + text + '?');
	});

	$("#captcha .refresh").live('click', function(e) {
		e.preventDefault();

		reloadCaptcha($(this).parent().parent().attr('id'));
		$(this).blur(); 
	});

    if ($(".field-desc").length)
    {
        enablePopovers();
    }
});

function enablePopovers()
{
    $(".field-desc").popover({
        trigger: 'hover',
        placement: 'left',
        html: true
    });
}

function reloadCaptcha(id)
{
	if (id && id != 'captcha') {
		var div = $('#captcha_' + id.replace('captcha_', ''));
	} else {
		var div = $('#captcha');
	}

    div.find('#siimage').attr('src', $('#webroot').text() + 'libraries/captcha/securimage_show.php?sid=' + Math.random());
    div.find('.captcha').val('').focus();
}

// Grab all required inputs on page, put in a * to note that it's a required field
function changeRequiredFields() 
{
	$.each($(".required:input"), function(i) {
		var label = $(this).parent().find('label').first();

		// For the article page, this function is called so want to make sure to not have more than one *
		if ($(label).find('i:not(.icon)').length == 0) {
			$(label).append(' <i>*</i>');
		}
	});
}

function getBlockUI()
{
    $.blockUI({
        message: 'Loading, Please Wait...',
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
    });
}

function setLoginNotice(notice)
{
    login_notice = notice;
}

function ajaxLogin(event)
{
    event.preventDefault();

    var user_form = $('#UserLoginForm');

    if (user_form.valid()) {
        $.post($("#webroot").text() + "login", user_form.serialize(), function(response) {
            if (response.match(/UserLoginForm/)) {
                var data = response.replace('<h1>Login</h1>', '');

                login_notice.setText('<strong>Error</strong> <br /> Incorrect username/password entered, please try again:<br /><br />' + data);
            }
            else
            {
                login_notice.close();
                noty({
                    text     : '<strong>Success</strong> <br /> You have been logged in and your progress will be saved.',
                    type     : 'success',
                    timeout: 5000,
                    closeWith: ['click']
                });

                if ($('.admin-validate').find('input[name*="_Token"]').length) {
                    $('body').append('<div id="temporary" class="hidden"></div>');

                    $('#temporary').load(window.location.pathname + ' .admin-validate', function() {
                        $('#temporary').find('form').removeClass('admin-validate');
                        $('#temporary').find('input[type="hidden"]').each(function() {
//                            console.log($('.admin-validate').find('input[name="' + $(this).attr('name') + '"]'));
                            $('.admin-validate').find('input[name="' + $(this).attr('name') + '"]').val($(this).val());
                        });
                        $('#temporary').html('');
                    });
                }
            }
        });
    }
}