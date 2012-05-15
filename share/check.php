<?php
include("config.php");

$siteurl = $_GET['siteurl'];
$sitename = $_GET['sitename'];
$domain = $_GET['domain'];
$share_id = $_GET['share_id'];
$version = $_GET['version'];

$sitecheck = mysql_fetch_row(mysql_query("SELECT sitename,user_id,last_updated,date,secret_id,id FROM adaptcms2_websites WHERE siteurl = '".$siteurl."'"));
if (!$sitecheck[0]) {
mysql_query("INSERT INTO adaptcms2_websites VALUES (null, '".$sitename."', '".$siteurl."', 0, 0, '".time()."', '".time()."', '".random("18", $domain)."-".nrandom("10000000000")."-".arandom("5", $domain)."')");
}
?>