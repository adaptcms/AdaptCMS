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

                    clearTimeout(setTimeout("triggerTimeoutWarning2Min()", 60000 * 28));
                    setTimeout("triggerTimeoutWarning2Min()", 60000 * 28);
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

                    clearTimeout(setTimeout("triggerTimeoutWarning2Min()", 60000 * 28));
                    setTimeout("triggerTimeoutWarning2Min()", 60000 * 28);

                    clearTimeout(setTimeout("triggerTimeoutWarning5Min()", 60000 * 25));
                    setTimeout("triggerTimeoutWarning5Min()", 60000 * 25);
                });
            }
        }
    });
}

function successMessage(message)
{
    var n = noty({
        text     : '<strong>Success</strong> <br /> ' + message,
        type     : 'success',
        timeout: 5000,
        closeWith: ['click']
    });
}

function errorMessage(message)
{
    var n = noty({
        text     : '<strong>Error</strong> <br /> ' + message,
        type     : 'error',
        timeout: 5000,
        closeWith: ['click']
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

    if ($("#UserPasswordConfirm").length == 1) {
        if ($("#UserPasswordConfirm").hasClass('required')) {
            $("#UserPasswordConfirm").rules("add", {
                required: true,
                equalTo: "#UserPassword",
                messages: {
                    equalTo: "Passwords do not match"
                }
            });
        } else {
            $("#UserPasswordConfirm").rules("add", {
                required: false,
                equalTo: "#UserPassword",
                messages: {
                    equalTo: "Passwords do not match"
                }
            });
        }
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

    var deobfuscate = document.getElementsByClassName('deobfuscate');
    if (deobfuscate.length) {
        for(var i = 0; i < deobfuscate.length; i++) {
            deObfuscateEmail( deobfuscate[i] );
        }
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
	$.each($(".required:input,input[required],textarea[required],select[required]"), function(i) {
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

// copyright - http://www.codeforest.net/obfuscate-your-email-address-with-php-javascript-and-css
//Function that will handle email deobfuscation.
//@param element is a reference to html element that will be deobfuscated.
//Deobfuscation is done on text and on href attribute of the element.
//Nevertheless, function will work well with any element you pass in,
//even if href attribute won't be present.
function deObfuscateEmail( element ) {

    //Get the text of the element.
    var text = element.innerHTML,

    //Get href attribute. If there is no href attribute, set href value to be an empty string.
    //Regular expression is an IE Fix.
    //Namely, IE appends obfuscated email to the url (www.domain.com/com.liameym@em).
    //Therefore, the first part of the link needs to be removed (we grab just everything after the last forward slash '/').
        href = element.getAttribute('href').replace(/http:\/\/(.+)\//gi, '') || '',

    //Control variable. if the two @ symbols are present, we will perform deobfuscation,
    //if not, the string is not obfuscated and doesn't have to be deobfuscated.
        textReplace = text.search(/@.+@/),
        hrefReplace = href.search(/@.+@/),

    //This function handles the second layer of deobfuscation.
    //It is called later in the code.
    //Letters of the email are reversed (again) and css direction returned back to ltr.
    //This is called on mouseover event.
        reverseEmails = function(){

            //Only if htef is obfuscated.
            if( hrefReplace > -1 ) {

                //That's the reversing part right here.
                element.setAttribute('href', href.split('').reverse().join('') );

            }

            //Only if text is obfuscated.
            if( textReplace > -1 ) {

                //Reverse the text of the element and
                //return the direction to normal (left to right).
                element.innerHTML = text.split('').reverse().join('');
                element.style.direction = 'ltr';
                element.style.unicodeBidi = 'normal';

            }


            //Letters are replaced and the event isn't needed anymore.
            if( element.removeEventListener ) {

                element.removeEventListener('mouseover', reverseEmails, false);

            } else {

                // IE8-
                element.detachEvent('onmouseover', reverseEmails);

            }


        };
    //End variables and functions definitions.


    //href has to be processed first, because of the strange
    //IE bug that will mix the href and innerHTML values.
    if( hrefReplace > -1 ) {

        href = changeLetters(href);
        element.setAttribute('href', href);

    }

    //Change the direction of the text to show real address
    //to users, instead of an reversed one.
    if( textReplace > -1 ) {

        text = changeLetters( text );
        element.innerHTML = text;
        element.style.direction = 'rtl';
        element.style.unicodeBidi = 'bidi-override';

    }


    //Since we have a rtl text, user can't copy or click on a link.
    //Therefore we'll replace the value as soon as user hovers over the link.
    if( element.addEventListener ) {

        element.addEventListener('mouseover', reverseEmails, false);

    } else {

        element.attachEvent('onmouseover', reverseEmails);

    }
}

// copyright - http://www.codeforest.net/obfuscate-your-email-address-with-php-javascript-and-css
//This is a first layer of deobfuscation.
//Basically a reversed ROT13 algorithm.
function changeLetters(string) {

    //Helper variables.
    var currentLetter,
        currentPos,
        currentString = '',

    //Behold! The one and only counter.
        i = 0,

    //We're going to loop through the obfuscated strings characters, so this will come in handy.
        stringLength = string.length - 1,

    //Characters that will be used when deobfuscating email address.
    //Same as string in PHP obfuscate function (obfuscateEmail).
        characters = '123456789qwertzuiopasdfghjklyxcvbnmMNBVCXYLKJHGFDSAPOIUZTREWQ',
        charactersLength = characters.length;


    //Counter variable has been declared before.
    for( ; i<stringLength; i++ ) {

        //This letter will be deobfuscated.
        currentLetter = string.charAt(i);

        //Position of the letter in our characters string.
        currentPos = characters.indexOf(currentLetter);

        //If character is present in our string, replace it with a character
        //30 places before (opposite from obfuscating).
        //If not, leave it as it is (because character wasn't obfuscated).
        if( currentPos > -1 ) {

            currentPos -= (charactersLength-1) / 2;
            currentPos = currentPos < 0 ? charactersLength + currentPos : currentPos;

        } else {

            currentString += currentLetter;

        }

        //Finally, append a character to our temp string that will be returned.
        currentString += characters.charAt(currentPos);

    }

    return currentString;
}