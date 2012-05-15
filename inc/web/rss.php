<?php
header("Content-type: text/xml");

if ($_GET['section']) {
$section = str_replace("/","",str_replace("-"," ", mysql_real_escape_string(check(str_replace("_"," ", $_GET['section'])))));
}
if ($_GET['field'] or $_GET['data']) {
$_GET['field'] = str_replace("_", " ", str_replace("-"," ", stripslashes(htmlspecialchars(check($_GET['field'])))));
$_GET['data'] = str_replace("_", " ", str_replace("-"," ", stripslashes(htmlspecialchars(check($_GET['data'])))));
$ex = explode(",", $_GET['data']);

if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."sections WHERE name = '".$_GET['field']."'")) == 0) {
while (list($k,$i) = each($ex)) {
if ($k == 0) {
} else {
$fdata .= " OR";
}
$fdata .= " field_name = '".$_GET['field']."' AND data LIKE '%". $i ."%'";
}
} else {
while (list($k,$i) = each($ex)) {
$gid = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."content WHERE section = '".$_GET['field']."' AND name LIKE '%" . addslashes($i) . "%' AND status = ''"));
if ($k == 0) {
} else {
$fdata .= " OR";
}
$fdata .= " field_name = '".$_GET['field']."' AND data LIKE '%". $gid[0] ."%'";
}
unset($gid);
}
}

echo "<?xml version=\"1.0\" encoding=\"US-ASCII\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
<channel>
<atom:link href=\"".$pageurl."\" rel=\"self\" type=\"application/rss+xml\" />";

echo "
<title>".$setting["sitename"]."</title>
<link>".substr_replace($siteurl,"",-1)."</link>
<description>".$setting["sitename"]." - ".$siteurl."</description>
<webMaster>webmaster@".$domain." (Webmaster)</webMaster>
";

if ($_GET['field'] or $_GET['data']) {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE".$fdata." ORDER BY `id` DESC LIMIT ".$setting["limit"]);
} else {
if ($section == "") {
$sql = mysql_query("SELECT * FROM ".$pre."content WHERE status = '' ORDER BY `date` DESC LIMIT ".$setting["limit"]);
} else {
$sql = mysql_query("SELECT * FROM ".$pre."content WHERE section = '".$section."' AND status = '' ORDER BY `date` DESC LIMIT ".$setting["limit"]);
}
}
while($r = mysql_fetch_array($sql)) {

if ($_GET['field'] or $_GET['data']) {
$get = mysql_fetch_row(mysql_query("SELECT section,id,username,name,views,date FROM ".$pre."content WHERE id = '".$r[aid]."'"));
$r[section] = $get[0];
$r[id] = $get[1];
$r[username] = $get[2];
$r[name] = $get[3];
$r[views] = $get[4];
$r[date] = $get[5];
}

$s = "";
$fetch = "";
$s = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."fields WHERE type = 'textarea' AND section = '".$r[section]."' ORDER BY `id` ASC LIMIT 1"));
$fetch = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name= '".$s[0]."' AND item_id = '".$r[id]."'"));

if ($fetch[0]) {
$fetch[0] = str_replace('a href=\"article-', "a href=\"".$siteurl."/article-", $fetch[0]);
$ex = explode("<p>", $fetch[0]);
if ($ex[1] == "") {
$des = stripslashes(htmlentities($fetch[0]));
} else {
if (strlen($fetch[0]) > 300) {
$ex2 = explode("<br />", $fetch[0]);
$des = stripslashes(htmlentities($ex2[0]));
} else {
$des = stripslashes(htmlentities($ex[1]));
}
}
}

echo "
<item>
<title>".stripslashes($r[name])."</title>
<guid isPermaLink='true'>".url("content", $r[id], $r[name], $r[section])."</guid>
<link>".url("content", $r[id], $r[name], $r[section])."</link>
<description>".$des."&lt;/p&gt;</description>
<pubDate>".date("D, d M Y h:i:s T", $r[date])."</pubDate>
</item>
";
}
echo "
</channel></rss>";
?>