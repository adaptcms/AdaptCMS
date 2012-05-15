<?php
$protect = "yes";
$plugin_name = "Sitemap";
$plugin_url = "admin.php?view=plugins&do=load&plugin=sitemap";
$plugin_version = "1.0";

$apage = basename($_SERVER['PHP_SELF']);
$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace($apage, "", $_SERVER['PHP_SELF']);
$url = $_GET['url'];

if ($_GET['check'] == "status") {
echo 1;
}

if ($module == "install_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."settings WHERE section = 'Modules' AND name = 'sitemap_update'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already installed. <a href='admin.php?view=plugins&do=install'>Return</a>";
} else {
$data .= "Beginning to install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("INSERT INTO ".$pre."settings VALUES (null, 'sitemap_update', 'How often should a sitemap file be generated? (in days)', '14', 'setting', 'Modules')");
$query2 = mysql_query("INSERT INTO ".$pre."settings VALUES (null, 'sitemap_yahoo_key', 'Put in your yahoo API key (only required for yahoo to be pinged upon sitemap update)', '', 'setting', 'Modules')");
$query3 = mysql_query("INSERT INTO ".$pre."plugins VALUES (null, '".$plugin_name."', '".$_GET['url']."', '".$plugin_version."', 'On')");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row #1 Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row #1 Inserted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row #2 Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row #2 Inserted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='red'>False</font><br />";
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
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."settings WHERE section = 'Modules' AND name = 'sitemap_update'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already un-installed, or not yet installed. <a href='admin.php?view=plugins&do=install'>Return</a>";
} else {
$data .= "Beginning to un-install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("DELETE FROM ".$pre."settings WHERE name = 'sitemap_update' AND section = 'Modules'");
$query2 = mysql_query("DELETE FROM ".$pre."settings WHERE name = 'sitemap_yahoo_key' AND section = 'Modules'");
$query3 = mysql_query("DELETE FROM ".$pre."plugins WHERE name = '".$plugin_name."'");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row #1 Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row #1 Deleted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row #2 Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row #2 Deleted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($tot == 2) {
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
echo "<b>Sitemap Generator ".$plugin_version."</b><br />Welcome to the Sitemap Generator page. This AdaptCMS plugin will automatically generate a <a href='http://en.wikipedia.org/wiki/Site_map'>sitemap</a> to help enable your website get spidered by search engines easier and more effective.<br /><br />You can edit two aspects of this plugin. You can adjust by how many days a sitemap will be generated. For example, if you put in 7, then every 7 days the sitemap will be generated. As well, you can put in an yahoo API key. You can get your key - <a href='http://developer.yahoo.com/wsregapp/'>right here</a>.<br /><br />Both settings can be adjusted at the following link - <a href='admin.php?view=setting&name=Modules'>Click Here</a>.";
}
}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == strtolower($plugin_name)) {
echo "<b>Sitemap Generator ".$plugin_version."</b><br />Welcome to the Sitemap Generator page. This AdaptCMS plugin will automatically generate a <a href='http://en.wikipedia.org/wiki/Site_map'>sitemap</a> to help enable your website get spidered by search engines easier and more effective.<br /><br />You can edit two aspects of this plugin. You can adjust by how many days a sitemap will be generated. For example, if you put in 7, then every 7 days the sitemap will be generated. As well, you can put in an yahoo API key. You can get your key - <a href='http://developer.yahoo.com/wsregapp/'>right here</a>.<br /><br />Both settings can be adjusted at the following link - <a href='admin.php?view=setting&name=Modules'>Click Here</a>.";
}
}
?>