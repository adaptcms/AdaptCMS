<?php
if ($useridn) {
$smarty->display($skin.'/header.tpl');
echo "Sorry but you are already logged in - <a href='".$siteurl."'>Return to Homepage</a>";
} else {

if ($_GET['do'] == "activate") {
$prf = sprintf("SELECT email FROM ".$pre."users WHERE username = '%s' LIMIT 1",
mysql_real_escape_string(check($_GET['username'])));
$grab = mysql_fetch_row(mysql_query($prf));
$_GET['username'] = check(mysql_real_escape_string($_GET['username']));

if ($grab[0]) {
if (!$_GET['act']) {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_name = 'user_act_code' AND data = '".check(mysql_real_escape_string($_GET['code']))."' AND item_id = '".$_GET['username']."'")) > 0) {
mysql_query("DELETE FROM ".$pre."data WHERE field_name = 'user_act_code' AND data = '".check(mysql_real_escape_string($_GET['code']))."' AND item_id = '".$_GET['username']."'");
mysql_query("UPDATE ".$pre."users SET act = 'yes' WHERE username = '".$_GET['username']."'");
$smarty->display($skin.'/header.tpl');
echo "Your account is now activated! Feel free to <a href='".url("login")."'>Login</a>";
} else {
$smarty->display($skin.'/header.tpl');
echo "Sorry, the code and username combination is incorrect, could not activate account.";
}
} elseif($_GET['act'] == "enter") {
require_once('inc/recaptchalib.php');
$smarty->display($skin.'/header.tpl');

echo "<form action='".$siteurl."index.php' method='get'><input type='hidden' name='view' value='register'><input type='hidden' name='do' value='activate'><input type='hidden' name='username' value='".$_GET['username']."'><table><tr><td><b>Username</b></td><td>".$_GET['username']."</td></tr><tr><td><b>Code</b></td><td><input type='text' name='code' class='input'></td></tr><tr><td><input type='submit' value='Submit' class='input'></td></tr></table></form>";
} elseif($_GET['act'] == "newcode") {
$act_code = randompass(10);
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'user_act_code', '', '".$act_code."', '".$_GET['username']."')");

$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$ex = explode("@", $grab[0]);
$headers .= "From: ".$setting["sitename"]." <webmaster@".$domain.">" . "\r\n";

mail($grab[0], $setting["sitename"]." - Activate Account", "<html><body><p>Hello <b>".$_POST['username']."</b>,</p><p>Your account at <a href='".$siteurl."'>".$setting["sitename"]."</a> must be activated in order for you to login, post comments and such.</p><p>To activate your account please click on the link below:<br><a href='".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&code=".$act_code."'>".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&code=".$act_code."</a></p><p>Sincerely,<br>".$setting["sitename"]."</p></body></html>", $headers);

}
}
}

if (!$_GET['do']) {
$smarty->display($skin.'/header.tpl');

$js_code = "<script type='text/javascript' src='".$siteurl."inc/js/jquery.js'></script>

<script type='text/javascript' src='".$siteurl."inc/js/jquery.pstrength-min.1.2.js'></script>

<script type='text/javascript'>
//<!---------------------------------+
//  Developed by Roshan Bhattarai 
//  Visit http://roshanbh.com.np for this script and more.
//  This notice MUST stay intact for legal use
// --------------------------------->
$(document).ready(function()
{
	$('#username').blur(function()
	{
		//remove all the class add the messagebox classes and start fading
		$('#msgbox').removeClass().addClass('messagebox').text('Checking...').fadeIn('slow');
		//check the username exists or not from ajax
		$.post('".$siteurl."inc/check_user.php',{ user_name:$(this).val() } ,function(data)
        {
		  if(data=='no') //if username not avaiable
		  {
		  	$('#msgbox').fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Sorry, this username is in use').addClass('messageboxerror').fadeTo(900,1);
			});		
          }
		  else
		  {
		  	$('#msgbox').fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Username available to register').addClass('messageboxok').fadeTo(900,1);	
			});
		  }
				
        });
 
	});
});
</script>

<script type='text/javascript'>
$(function() {
$('.password').pstrength();
});
</script>
<style type='text/css'>
.password {
font-size : 12px;
border : 1px solid #cc9933;

font-family : arial, sans-serif;
}
.pstrength-minchar {
font-size : 10px;
}
</style>


<style type='text/css'>
.messagebox{
	position:absolute;
	width:100px;
	margin-left:30px;
	border:1px solid #c93;
	background:#ffc;
	padding:3px;
}
.messageboxok{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #349534;
	background:#C9FFCA;
	padding:3px;
	font-weight:bold;
	color:#008000;
	
}
.messageboxerror{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #CC0000;
	background:#F7CBCA;
	padding:3px;
	font-weight:bold;
	color:#CC0000;
}
</style>";

$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=register&do=submit' method='post'>");
$smarty->assign("username_input", "<input type='text' id='username' name='username' class='input' size='16'> <span id='msgbox' style='display:none'></span>");
$smarty->assign("password_input", "<input class='password' name='password' type='password' id='password'>");
$smarty->assign("password_input2", "<input type='password' id='password2' name='password2' class='input' size='16'>");
$smarty->assign("email_input", "<input type='text' name='email' class='input' size='16'>");
if ($setting["captcha_login"] == "yes") {
require_once('inc/recaptchalib.php');
$smarty->assign("captcha", recaptcha_get_html($publickey));
}
echo $js_code;
$smarty->display($skin.'/register.tpl');
} elseif($_GET['do'] == "submit") {
if ($_POST['username'] && $_POST['password'] && valid_email($_POST['email']) == TRUE && $_POST['password'] == $_POST['password2']) {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE username = '".$_POST['username']."'")) > 0 or mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE email = '".$_POST['email']."'")) > 0) {
$smarty->display($skin.'/header.tpl');
echo "Sorry but that username and/or email is already in use! Please go back and try again.";
} else {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);


if (!$resp->is_valid) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$pass = mysql_real_escape_string(check($_POST['password']));
$_POST['username'] = mysql_real_escape_string(check($_POST['username']));
$_POST['password']= md5(mysql_real_escape_string(check($_POST['password'])));
$_POST['email'] = mysql_real_escape_string(check($_POST['email']));

if (strtolower($setting["register_verify"]) == "no" && strtolower($setting["register_activate"]) == "no") {
$ver = "yes";
$act = "yes";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$ex = explode("@", $_POST['email']);
$headers .= "From: ".$setting["sitename"]." <webmaster@".$domain.">" . "\r\n";

mail($_POST['email'], $setting["sitename"]." - Registered", "<html><body><p>Hello <b>".$_POST['username']."</b>,</p><p>Your account at <a href='".$siteurl."'>".$setting["sitename"]."</a> has been created and you can post right now!</p><p>Below you will find important information including your username and password, we recommend saving this e-mail message:</p><p><b>Username:</b> ".$_POST['username']."<br><b>Password:</b> ".$pass."</p><p>Sincerely,<br>".$setting["sitename"]."</p></body></html>", $headers);
}

if (strtolower($setting["register_verify"]) == "yes") {
$ver = "no";
$act = "yes";
unset($headers, $ex);
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$ex = explode("@", $_POST['email']);
$headers .= "From: ".$setting["sitename"]." <webmaster@".$domain.">" . "\r\n";

mail($_POST['email'], $setting["sitename"]." - Registered", "<html><body><p>Hello <b>".$_POST['username']."</b>,</p><p>Your account at <a href='".$siteurl."'>".$setting["sitename"]."</a> has been created, however it must be verified in order for you to make posts and topics.</p><p>Below you will find important information including your username and password, we recommend saving this e-amil message:</p><p><b>Username:</b> ".$_POST['username']."<br><b>Password:</b> ".$pass."</p><p>Sincerely,<br>".$setting["sitename"]."</p></body></html>", $headers);
}

if (strtolower($setting["register_activate"]) == "yes") {
$act = "no";
$ver = "yes";
unset($headers, $ex, $act_code);
$act_code = randompass(10);
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'user_act_code', '', '".$act_code."', '".$_POST['username']."')");

$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$ex = explode("@", $_POST['email']);
$headers .= "From: ".$setting["sitename"]." <webmaster@".$domain.">" . "\r\n";

mail($_POST['email'], $setting["sitename"]." - Activate Account", "<html><body><p>Hello <b>".$_POST['username']."</b>,</p><p>Your account at <a href='".$siteurl."'>".$setting["sitename"]."</a> has been created however it must be activated in order for you to login, post comments and such.</p><p>To activate your account please click on the link below:<br><a href='".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&code=".$act_code."'>".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&code=".$act_code."</a></p><p>Sincerely,<br>".$setting["sitename"]."</p></body></html>", $headers);
}

$fetch = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."groups WHERE options = 'default-member' ORDER BY `id` DESC"));
$fetch2 = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."levels WHERE type = 'level' ORDER BY `points` ASC"));
$def_skin = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."skins WHERE skin = '' AND template = 'yes|'"));

$sql = mysql_query("INSERT INTO ".$pre."users VALUES (null, '".$_POST['username']."', '".md5($salt.$_POST['username'].$_POST['password'])."', '".$_POST['email']."', '".$fetch[0]."', '".$fetch2[0]."', '".time()."', '".time()."', '".$act."', '".$ver."', '', 0, '".$def_skin[0]."')");

if ($sql == TRUE) {
setcookie($cookiename."username", $_POST['username'], time()+60*60*24*30);
setcookie($cookiename."password", $_POST['password'], time()+60*60*24*30);

$smarty->display($skin.'/header.tpl');
echo re_direct("1500", $siteurl);
echo "Welcome back ".strip_tags($_POST[username])."! Enjoy your stay. <a href='".$siteurl."'>Return</a>";
} else {
$smarty->display($skin.'/header.tpl');
echo "Sorry but that username and password combo is incorrect. Please try again.";
}

if ($act == "no" && $ver == "no") {
echo "<br><br>To be able to login, you must first active your account and be verified by an administrator. ";
} elseif ($act == "no") {
echo "<br><br>In order to login, you must first active your account. You will receive an e-mail shortly. ";
} elseif ($ver == "no") {
echo "<br><br>In order to login, you must first be verified by an administrator. You will receive an e-mail shortly. ";
}
}
}
}
}
}
?>