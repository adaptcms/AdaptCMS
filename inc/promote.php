<?php
include("dbinfo.php");
include("functions.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

//print_r($_POST);
if ($_POST['site'] == "Digg") {
header("location: http://digg.com/submit?phase=2&amp;url=".urlencode($_POST['url'])."&amp;topic=music&amp;title=".htmlentities($_POST['title'])."&amp;bodytext=".urlencode($_POST['description']));
} elseif ($_POST['site'] == "Reddit") {
header("location: http://www.reddit.com/submit?url=".urlencode($_POST['url'])."&title=".urlencode($_POST['title']));
} elseif ($_POST['site'] == "Stumbleupon") {
header("location: http://www.stumbleupon.com/submit?url=".urlencode($_POST['url'])."&title=".urlencode($_POST['title']));
} elseif ($_POST['site'] == "Facebook") {
header("location: http://www.facebook.com/sharer.php?u=".urlencode($_POST['url'])."&t=".urlencode($_POST['title']));
} elseif ($_POST['site'] == "N4G") {
header("location: http://n4g.com/tips?url=".urlencode($_POST['url'])."&title=".urlencode($_POST['title']));
}
?>