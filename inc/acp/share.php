<?php
unset($data);
if ($_GET['do'] && $_GET['go'] == "load2" && is_numeric($_GET['id']) && $_POST['name']) {
while (list($k, $i) = each ($_POST)) {
$data .= "&".$k."=".urlencode($i);
}

if ($_GET['do'] == "promote") {
$oktime = time() - 86400;
$a = 0;

$sql = mysql_query("SELECT * FROM ".$pre."content WHERE status = '' AND date > '".$oktime."'");
while($r = mysql_fetch_array($sql)) {
$data .= "&content".$a."=".urlencode($r[name])."&content_time".$a."=".urlencode($r[date]);
$a++;
}
$data .= "&content_total=".$a;

$var = file_get_contents("http://www.insanevisions.com/share/adaptcms2/share.php?sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&share_id=".urlencode($_COOKIE['share_id'])."&domain=".urlencode($domain)."&do=promote".$data);
}

if ($_GET['do'] == "skins") {
$var = file_get_contents("http://www.insanevisions.com/share/adaptcms2/share.php?sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&share_id=".urlencode($_COOKIE['share_id'])."&domain=".urlencode($domain)."&do=skins&go=load3".$data);
}

if ($_GET['do'] == "fields") {
mysql_query("INSERT INTO ".$pre."fields VALUES (null, '".addslashes($_POST["name"])."', '".addslashes($_POST["section"])."', '".$_POST['ftype']."', '".urldecode(addslashes($_POST["description"]))."', '".urldecode(addslashes($_POST["data"]))."', '".$_POST["editable"]."', '".urldecode($_POST["limit"])."', '".$_POST["required"]."')");
}elseif ($_GET['do'] == "help") {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".addslashes($_POST['name'])."', 'help-file', '".urldecode(addslashes($_POST["data"]))."', '".addslashes($_POST["section"])."')");
}elseif ($_POST['type'] == "skin") {
$make = mkdir($sitepath."templates/".$_POST["name"]);
unset($ex, $k, $i, $fh);
$ex = explode("<--NEW-QUERY-->", $var);
while (list($a, $i) = each ($ex)) {
if ($a > 0 && $i) {
$r = explode("{name-to-the-right}", $i);
//echo htmlentities($r[0])." :: ".$r[1]."/".$a."<br><hr><br>";

mysql_query("INSERT INTO ".$pre."skins VALUES (null, '".$r[1]."', '".addslashes($_POST["name"])."', '".$r[0]."', '".time()."')");
$fh = fopen($sitepath."templates/".$_POST['name']."/".$r[1].".tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($r[0]));
fclose($fh);
unset($fh);
}

}
} elseif ($_POST['type'] == "template") {
$ex = explode("<--NEW-QUERY-->", $var);
$r = explode("{name-to-the-right}", $ex[1]);

mysql_query("INSERT INTO ".$pre."skins VALUES (null, '".$r[1]."', '".addslashes($_POST["skin"])."', '".$r[0]."', '".time()."')");
if ($_POST['skin']) {
$skin1 = $_POST['skin']."/";
}
$fh = fopen($sitepath."templates/".$skin1.$r[1].".tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($r[0]));
fclose($fh);
unset($fh);
}

}
if ($_GET['do'] && $_GET['go'] == "load" && is_numeric($_GET['id'])) {
$a = 0;
if ($_GET['do'] == "skins") {
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE template = 'skin'");
while($r = mysql_fetch_array($sql)) {
$data .= "&skin".$a."=".urlencode($r[name]);
$a++;
}
$data .= "&skin_tot=".$a;
} elseif ($_GET['do'] == "help") {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' ORDER BY `field_name` ASC");
while($r = mysql_fetch_array($sql)) {
$data .= "&help".$a."=".urlencode($r[field_name]);
$a++;
}
$data .= "&help_tot=".$a;
} elseif ($_GET['do'] == "fields") {
$sql = mysql_query("SELECT * FROM ".$pre."sections");
while($r = mysql_fetch_array($sql)) {
$data .= "&sec".$a."=".urlencode($r[name]);
$a++;
}
$data .= "&sec_tot=".$a;
}
}
if ($_GET['do'] == "share") {
$a = 0;
$b = 0;
if ($_GET['type'] == "skins") {
$sql = mysql_query("SELECT * FROM ".$pre."skins ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[template] == "skin") {
$data .= "&skin".$a."=".urlencode($r[name]);
$a++;
} else {
$data .= "&tmp".$b."=".urlencode($r[skin])."::".urlencode($r[name]);
$b++;
}
}
$data .= "&skin_tot=".$a;
$data .= "&tmp_tot=".$b;
} elseif ($_GET['type'] == "help") {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' ORDER BY `field_name` ASC");
while($r = mysql_fetch_array($sql)) {
$data .= "&help".$a."=".urlencode($r[field_name])."::".urlencode($r[data]);
$a++;
}
$data .= "&help_tot=".$a;
} elseif ($_GET['type'] == "fields") {
$sql = mysql_query("SELECT * FROM ".$pre."fields ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
$data .= "&field".$a."=".urlencode($r[name])."::".urlencode($r[type])."::".urlencode($r[description])."::".urlencode($r[data])."::".urlencode($r[editable])."::".urlencode($r[limit])."::".urlencode($r[required]);
$a++;
}
$data .= "&fields_tot=".$a;
}
}
if ($_POST['username'] && $_POST['password'] && $_GET['do'] == "login") {
if ($_POST['share_id']) {
setcookie("share_id", md5(strip_tags($_POST['share_id'])), time()+60*60*24*30);
}
$smarty->display($skin.'/admin_header.tpl');
echo file_get_contents("http://www.insanevisions.com/share/adaptcms2/share.php?do=".$_GET['do']."&username=".urlencode(check($_POST['username']))."&password=".urlencode(check(md5($_POST['password'])))."&recaptcha_challenge_field=".urlencode($_POST['recaptcha_challenge_field'])."&recaptcha_response_field=".urlencode($_POST['recaptcha_response_field'])."&sitename=".urlencode(check($setting['sitename']))."&siteurl=".urlencode(check($siteurl))."&share_id=".urlencode($_POST['share_id'])."&domain=".urlencode($domain));
} elseif(!$_GET['do']) {
$smarty->display($skin.'/admin_header.tpl');
echo file_get_contents("http://www.insanevisions.com/share/adaptcms2/share.php?sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&share_id=".urlencode($_COOKIE['share_id'])."&domain=".urlencode($domain));
} elseif($_GET['do'] && $_GET['do'] != "login") {
$smarty->display($skin.'/admin_header.tpl');
while (list($k, $i) = each ($_POST)) {
$data .= "&".$k."=".urlencode($i);
}
unset($i, $k);
while (list($k, $i) = each ($_GET)) {
if ($k != "do" && $k != "view") {
$data .= "&".$k."=".urlencode($i);
}
}
echo file_get_contents("http://www.insanevisions.com/share/adaptcms2/share.php?do=".urlencode(check($_GET['do']))."&type=".urlencode(check($_GET['type']))."&sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&share_id=".urlencode($_COOKIE['share_id'])."&domain=".urlencode($domain).$data);
}
?>