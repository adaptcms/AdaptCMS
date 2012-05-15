<?php
if ($_POST['shoutbox_message']) {
if (strtolower($setting["guests_shoutbox"]) == "yes" && $useridn == "" or $useridn) {
if (md5(strtoupper($_POST['captcha'])) != $_SESSION['captcha']) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again";
} else {
mysql_query("INSERT INTO ".$pre."comments VALUES (null, '', '".mysql_real_escape_string(badwords(addslashes(check($_POST['shoutbox_message']))))."', '".mysql_real_escape_string(stripslashes($_SESSION[adaptcms_username]))."', '".$email."', '', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
}
}
}

function shoutbox($limit) {
global $sitename;
global $siteurl;
global $mrw;
global $mrw_1;
global $pre;
global $setting;

$temp = @mysql_fetch_row(@mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'Shoutbox'"));

echo wysiwyg();
echo "<script language='JavaScript'><!--
ts = ".time().";
--></script><form action='".$_SERVER['REQUEST_URI']."' method='post'><table cellpadding='3' cellspacing='0' border='0' align='center'><tr><td>";
$sql = mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '0' OR aid = '' ORDER BY `id` DESC LIMIT ".$limit);
while($r = mysql_fetch_array($sql)) {
$pab[0] = "{username}";
$pab[1] = "{author}";
$pab[2] = "{message}";
$pab[3] = "{date}";

$rab[0] = "<a href='".$mrw_1["user"].$r[author]."'>".$r[author]."</a>";
$rab[1] = "<a href='".$mrw_1["user"].$r[author]."'>".$r[author]."</a>";
$rab[2] = stripslashes(parse_text($r[comment]));
$rab[3] = timef($r[date]);

$messages .= str_replace($pab, $rab, stripslashes($temp[0]));
}
echo $messages;
echo "</td></tr><tr><td><textarea cols='16' rows='5' name='shoutbox_message'></textarea><br /><img id='captcha_img' src='".$siteurl."includes/captcha.php?id=".time()."' style='border:1px solid #000000' /> <a href='no_matter' onclick='document.getElementById(\"captcha_img\").src = \"".$siteurl."includes/captcha.php?id=\" + ++ts; return false'><img src='".$siteurl."includes/refresh.png' border='0'></a> <input type='text' name='captcha' size='5'><br /><input type='submit' value='Shout!'></td></tr></table></form>";
}
?>