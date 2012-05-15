<?php
include("../config.php");

if ($_POST['status']) {
$new_status = substr(mysql_real_escape_string(preg_replace("/\n/", "<br />\n", badwords(addslashes(check($_POST['status']))))),0,$setting["status_char_limit"]);
if ($new_status) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".time()."', 'status-update', '".$new_status."', '".$useridn."')");
}
mysql_query("UPDATE ".$pre."users SET status = '".$new_status."', status_time = '".time()."' WHERE id = '".$useridn."'");
}
?>