<?php
if (!$_GET['act']) {
require_once('inc/recaptchalib.php');

$smarty->assign("username", $_COOKIE[$cookiename."username"]);
$smarty->assign("captcha", recaptcha_get_html($publickey));

$smarty->display($skin.'/admin_login.tpl');
}

if ($_GET['act'] == "login") {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);


if (!$resp->is_valid) {
$smarty->display($skin.'/admin_header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$prf = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s' AND password = '%s' LIMIT 1",
mysql_real_escape_string(strip_tags($_POST['username'])),
mysql_real_escape_string(strip_tags(md5($salt.$_POST['username'].md5($_POST['password'])))));

$login_check = mysql_num_rows(mysql_query($prf));

if ($login_check == "1") {
mysql_query("UPDATE ".$pre."users SET last_login = '".time()."' WHERE username = '".$_POST['username']."'");

$_SESSION[$cookiename."username"] = strip_tags($_POST['username']);
$_SESSION[$cookiename."password"] = strip_tags(md5($_POST['password']));
if (!$_COOKIE[$cookiename."username"] or !$_COOKIE[$cookiename."password"]) {
setcookie($cookiename."username", strip_tags($_POST['username']), time()+60*60*24*30);
setcookie($cookiename."password", strip_tags(md5($_POST['password'])), time()+60*60*24*30);
}
header('location: admin.php');
} else {
$smarty->display($skin.'/admin_header.tpl');
echo "Sorry but that username and password combo is incorrect. Please try again.";
}
}
}
?>