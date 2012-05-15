<?php
$plugin = mysql_fetch_row(mysql_query("SELECT url,status FROM ".$pre."plugins WHERE name = '".strtolower(str_replace("_", " ", $_GET['plugin']))."'"));
if ($plugin[1] == "Off") {
echo "Sorry, but the <b>".ucwords($_GET['plugin'])."</b> Plugin is offline";
} else {
$module = $_GET['module'];
include ($sitepath."plugins/".$plugin[0]);
}
?>