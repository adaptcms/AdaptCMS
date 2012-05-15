<?php
if ($useridn) {
$smarty->display($skin.'/header.tpl');
echo "Sorry but you are already logged in - <a href='".$siteurl."'>Return to Homepage</a>";
} else {
if (!$_GET['do']) {
$smarty->display($skin.'/header.tpl');

$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=login&do=submit' method='post'>");
$smarty->assign("register_link", url("register"));
$smarty->assign("username_input", "<input type='text' name='username' class='input' size='16'>");
$smarty->assign("password_input", "<input type='password' name='password' class='input' size='16'>");
if ($setting["captcha_login"] == "yes" or !$setting["captcha_login"]) {
require_once('inc/recaptchalib.php');
$smarty->assign("captcha", recaptcha_get_html($publickey));
}
$smarty->assign("forgot_pw", $siteurl."index.php?view=social&do=forgot_password");
$smarty->display($skin.'/login.tpl');
} elseif($_GET['do'] == "submit") {
if ($setting["captcha_login"] == "yes") {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
}


if (!$resp->is_valid && $setting["captcha_login"] == "yes") {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$prf = sprintf("SELECT id,act,ver FROM ".$pre."users WHERE username = '%s' AND password = '%s' LIMIT 1",
mysql_real_escape_string(strip_tags($_POST['username'])),
mysql_real_escape_string(strip_tags(md5($salt.$_POST['username'].md5($_POST['password'])))));

$login_check = mysql_fetch_row(mysql_query($prf));

if (!$login_check[0]) {
$smarty->display($skin.'/header.tpl');
echo "Sorry but that username and password combo is incorrect. Please try again.";
} else {
if (!$login_check[1] or $login_check[1] == "no") {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but your account has not been activated yet - you can either proceed to this page to enter your code - <a href='".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&act=enter'>Activate Account</a><br />
Or you can have the e-mail re-sent with a new code - <a href='".$siteurl."index.php?view=register&do=activate&username=".$_POST['username']."&act=newcode'>Send new Code</a>";
} else {
if (!$login_check[2] or $login_check[2] == "no") {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but your account has not been verified yet - you cannot login until you are. <a href='".$siteurl."'>Homepage</a>";
} else {
mysql_query("UPDATE ".$pre."users SET last_login = '".time()."' WHERE username = '".$_POST['username']."'");

setcookie($cookiename."username", strip_tags($_POST['username']), time()+60*60*24*30);
setcookie($cookiename."password", strip_tags(md5($_POST['password'])), time()+60*60*24*30);

$smarty->display($skin.'/header.tpl');
echo re_direct("1500", $siteurl);
echo "Welcome back ".strip_tags($_POST[username])."! Enjoy your stay. <a href='".$siteurl."'>Return</a>";
}
}
}
}
}
}

?>