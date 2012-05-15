<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_POST['check'] == "") {
$files = explode(",", file_get_contents("http://www.adaptcms.com/latest_files_2.txt"));
while (list(, $p) = each ($files)) {
if (stristr($p, "htaccess")) {
$p = ".htaccess";
} else {
$p = str_replace(".txt",".php",$p);
}
$file[$p."_time"] = @date("U",filemtime($p));
$file[$p."_size"] = @filesize($p);
$array .= "&file_".$p."_time=".@date("U",filemtime($p));
$array .= "&file_".$p."_size=".@filesize($p);
}
echo file_get_contents("http://www.adaptcms.com/upgrade2.php?sitename=".urlencode($setting['sitename'])."&id=".$_GET['id']."&do=".$_GET['do']."&siteurl=".urlencode($siteurl)."&version=".urlencode($version)."&domain=".$domain.$array);
} else {
$num = 0;
$data1 = $_POST['check'];
while (list($k, $p) = each ($_POST['check'])) {
$data .= "&file_".$k."=".$p;
$num++;
}
$data .= "&file_tot=".$num;
$var = file_get_contents("http://www.adaptcms.com/upgrade2.php?id=".$_GET['id']."&do=url".$data);
if (!$var) {
$var = "http://www.adaptcms.com/a/files/".$_GET['id']."/";
}

$_POST['check'] = $data1;
echo "<p>You have selected files to be copied over to your website. Starting now...</p><br />";
while (list(, $i) = each ($_POST['check'])) {
$file1 = file_get_contents($var.$i);
if (strlen($file1) == 0) {
echo "The file <b>".$i."</b> could not be retrieved from the AdaptCMS website, please try again later.<br />";
} else {
if (stristr($i, "htaccess")) {
$i = ".htaccess";
} else {
$i = str_replace(".txt",".php",$i);
}
$handle = fopen($sitepath.$i, 'w');
fwrite($handle, $file1);
fclose($handle);
if ($handle == TRUE) {
echo "The file <b>".$i."</b> has been successfully copied to your website.<br />";
} else {
echo "The file <b>".$i."</b> <font color='red'><u>could not</u></font> be copied to your website.<br />";
}
}
}
echo "<br />All file(s) have been copied over, return to the <a href='admin.php'>admin panel</a> or go back to <a href='admin.php?view=file_releases&id=".$_GET['id']."'>file releases</a>.";
}
?>