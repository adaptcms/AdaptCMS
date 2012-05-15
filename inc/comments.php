<?php
include("../config.php");

require_once('recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);


if (!$resp->is_valid && strtolower($setting["captcha_comments"]) == "yes") {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
if (is_numeric($_POST['article_id']) && $_POST['comment'] or $_POST['comment'] && is_numeric($_POST['page_id'])) {
$text = preg_replace("/\n/", "<br />\n", check(addslashes($_POST['comment'])));
if ($_POST['article_id']) {
$id = $_POST['article_id'];
} else {
$id = "page_".$_POST['page_id'];
}
if ($_POST['website'] == "http://") {
unset($_POST['website']);
}
mysql_query("INSERT INTO ".$pre."comments VALUES (null, '".mysql_real_escape_string($id)."', '".$useridn."', '".mysql_real_escape_string($text)."', '0|0', '".check($_POST['email'])."', '".mysql_real_escape_string(check($_POST['website']))."', '".$_SERVER['REMOTE_ADDR']."', '', '".time()."')");
echo "Your comment has been posted <a href='".mysql_real_escape_string($_POST['url'])."'>Refresh page to see comment</a>";
}
}
?>