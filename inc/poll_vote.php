<?php
include("dbinfo.php");
include("functions.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));
$vote = explode("_", $_GET['vote']);

$_GET['vote'] = $vote[0];
$_GET['poll_id'] = $vote[1];
$_GET['results'] = "yes";
echo poll($vote[2]);

	if ($vote[0] && is_numeric($vote[1]) or $vote[0]) {
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE id = '".htmlentities(urldecode($vote[0]))."' AND poll_id = '".htmlentities(urldecode($vote[1]))."'");
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE poll_id = '".htmlentities(urldecode($vote[1]))."' AND id = '".htmlentities(urldecode($vote[1]))."'");
	$poll_id = htmlentities(stripslashes($vote[1]));
	$_SESSION["poll_".$poll_id] = htmlentities(stripslashes($vote[0]));
	}
	$op = "";$poll_id = "";$i = "";
?>