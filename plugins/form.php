<?php
session_start();

if ($_POST['fields']) {
if ($_SESSION['form_builder']) {
echo "Sorry, but you just sent an email moments ago. Please do not abuse the system. Thank you.<br />";
} else {
if (md5(strtoupper($_POST['captcha'])) != $_SESSION['captcha']) {
echo "Sorry but you put in the wrong answer for the math question. Try again please.";
} else {
$yourName = 'Charlie Page';
$subject = "Contact: ".$_POST['subject']."";
$referringPage = $_SERVER['HTTP_REFERER'];

	while (list(, $i) = each ($_POST['fields'])) {
	$message .= $i.": ".$_POST[$i]."
	\r\n";
	}

	$headers = "From: ".stripslashes($_POST['name'])." <".$_POST['email'].">\r\n";
	$headers .= 'To: '.$_POST['receiver'].'>'."\r\n";
	$mail_it = mail($_POST['receiver'],$subject,$message,$headers);

if ($mail_it == TRUE) {
$_SESSION['form_builder'] = "yes";
echo "Your e-mail has been sent out! We will respond shortly.<br />";
} else {
echo "Sorry but your e-mail could not be sent, we will work on the problem.<br />";
}
}
}
}
?>