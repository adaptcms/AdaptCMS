<?php
$protect = "yes";
$plugin_name = "Shoutbox";
$plugin_url = "admin.php?view=load_plugin&plugin=".strtolower($plugin_name);
$plugin_version = "1.0";

$apage = basename($_SERVER['PHP_SELF']);
$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace($apage, "", $_SERVER['PHP_SELF']);
$url = $_GET['url'];

if ($_GET['check'] == "status") {
echo 1;
}

if ($module == "install_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."templates WHERE name = '".$plugin_name."'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already installed. <a href='admin.php?view=install_plugins'>echo</a>";
} else {
$data .= "Beginning to install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("INSERT INTO ".$pre."plugins VALUES (null, '".$plugin_name."', '".$_GET['url']."', '".$plugin_version."', 'On')");
$query2 = mysql_query("INSERT INTO ".$pre."templates VALUES (null, '".$plugin_name."', '<table cellpadding=\'3\' cellspacing=\'0\' border=\'0\'><tr><td>{username} @ <i>{date}</i></td></tr><tr><td>{message}</td></tr>\r\n</table><br clear=\'all\'>', '".time()."')");
$query3 = mysql_query("INSERT INTO `".$pre."settings` VALUES (null, 'guests_shoutbox', 'Can guests post to the Shoutbox?', 'no', 'setting', 'General')");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."templates` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."templates` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($tot == 3) {
$data .= "<br /><b>".$plugin_name."</b> Plugin installed <font color='green'>Sucessfully!</font>. <a href='".$plugin_url."'>".$plugin_name." Plugin</a>";
echo $data;
} else {
$data .= "<br /><b>".$plugin_name."</b> Plugin installed <font color='red'>Un-Sucessfully!</font>. Please check mysql settings and if need be, please submit a <a href='admin.php?view=support'>support ticket</a>.";
echo $data;
}
}
}

if ($module == "uninstall_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."templates WHERE name = '".$plugin_name."'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already un-installed, or not yet installed. <a href='admin.php?view=install_plugins'>echo</a>";
} else {
$data .= "Beginning to un-install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("DELETE FROM ".$pre."plugins WHERE name = '".$plugin_name."'");
$query2 = mysql_query("DELETE FROM ".$pre."templates WHERE name = '".$plugin_name."'");
$query3 = mysql_query("DELETE FROM ".$pre."settings WHERE name = 'guests_shoutbox'");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."templates` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."templates` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($tot == 3) {
$data .= "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='green'>Sucessfully!</font>. <a href='admin.php?view=plugins'>Plugins</a>";
echo $data;
} else {
$data .= "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='red'>Un-Sucessfully!</font>. Please check mysql settings and if need be, please submit a <a href='admin.php?view=support'>support ticket</a>.";
echo $data;
}
}
}

if (basename($_SERVER['PHP_SELF']) == "admin.php") {
if ($module == "") {
$tid = mysql_fetch_row(mysql_query("SELECT * FROM ".$pre."templates WHERE name = '".$plugin_name."'"));
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".ucwords($_GET['plugin'])." Plugin<br /><br /><br />";
echo "<b>Shoutbox v".$plugin_version."</b><p>Welcome to the Shoutbox page. This AdaptCMS Plugin improves the interactiveness of AdaptCMS and your website. Similar to a comment section right on any page, the Shoutbox plugin lets users and visitors (optional) talk about anything they'd like right on the side of your website.</p>

<p>Best of all, you can customize the way it looks through an easily-editable <a href='admin.php?view=edit_template&id=".$tid[0]."'>Shoutbox template</a>. As well, there are several editable settings. You can adjust a word filter (used in comments as well) <a href='admin.php?view=setting&name=Other'>here</a>. You can also set whether visitors can use the Shoutbox plugin <a href='admin.php?view=setting&name=General'>here</a>.</p>";
}
}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == strtolower($plugin_name)) {
echo shoutbox("1000");
}
}
?>