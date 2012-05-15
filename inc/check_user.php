<?php
include("dbinfo.php");
include("functions.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

$check_user = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s'", mysql_real_escape_string(check($_POST['user_name'])));

if (mysql_num_rows(mysql_query($check_user)) > 0 or !$_POST['user_name']) {
echo "no";
} else {
echo "yes";
}
?>