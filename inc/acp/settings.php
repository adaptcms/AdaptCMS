<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<ul id='settings' class='shadetabs'>";
$i = 1;
$sql = mysql_query("SELECT * FROM ".$pre."settings WHERE type = 'section' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<li><a href='#' rel='setting".$i."'";
if ($i == 1) {
echo " class='selected'";
}
echo ">".ucwords($r[name])."</a></li>";


$contents .= "<div id='setting".$i."' class='tabcontent'><form action='admin.php?view=settings&do=edit_setting&name=".$r[name]."' method='post'>

<div align='right'><a href='admin.php?view=settings&do=add_setting&section=".$r[name]."'>Add Setting</a>&nbsp;&nbsp;<a href='admin.php?view=del_section&id=".$r[name]."'>Delete Section</a></div>

<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td><b>Setting Name</b></td><td><b>Data</b></td><td><b>Description</b></td><td><b>Delete?</b></tr>";
$i = 0;
$sql2 = mysql_query("SELECT * FROM ".$pre."settings WHERE type = 'setting' AND section = '".$r[name]."' ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql2)) {
if (($i % 2) === 0) {
$contents .= "<tr>";
} else {
$contents .= "<tr>";
}
$contents .= "<td><input type='text' name='name_".$row[id]."' value='".stripslashes($row[name])."' size='16' class='input'></td><td>
<textarea name='data_".$row[id]."' cols='20' rows='2' class='input'>".stripslashes($row[data])."</textarea>
<input type='hidden' name='id[]' value='".$row[id]."'></td><td><textarea name='description_".$row[id]."' cols='23' rows='7' class='input'>".stripslashes($row[description])."</textarea></td><td>";
if ($p[2]) {
$contents .= "<input type='checkbox' name='del_".$row[id]."' value='delete'>";
}
$contents .= "</td></tr>";
$i = $i + 1;
}
$contents .= "<tr><td><tr><td>";
if ($p[1]) {
$contents .= "<input type='submit' value='Update ".ucwords($r[name])." Settings' class='input'>";
}
$contents .= "</td></tr></table></form></div>";


$contents2 .= "<div id='setting".$i."' class='tabcontent'>
".$i." - ".$r[id]."
</div>";
$i = $i + 1;
}
echo "</ul>

<div style='border:1px solid gray; width:96%; margin-bottom: 1em; padding: 10px'>";
echo $contents;
echo "</div>

<script type='text/javascript'>

var countries=new ddtabcontent('settings')
countries.setpersist(true)
countries.setselectedClassTarget('link') //'link' or 'linkparent'
countries.init()

</script>";
}

if ($p[0]) {
if ($_GET['do'] == "add_section") {
echo "<form action='admin.php?view=settings&add_section2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr style='background:url(".$siteurl."inc/images/topbg.jpg) repeat-x;'><tr><td>Name</td><td><input type='text' name='name' size='16' class='input'></td></tr><tr><td><tr><td><input type='submit' value='Add Section' class='input'></td></tr></table></form>";
}

if ($_GET['do'] == "add_section2") {
$query = @mysql_query("INSERT INTO ".$pre."settings VALUES (null, '".addslashes($_POST["name"])."', '', '', 'section', '')");

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=settings&settings=".addslashes($_POST["name"]));
echo "Section <b>".$_GET['name']."</b> has been added. <a href='admin.php?view=settings&settings=".addslashes($_POST["name"])."'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "Section could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "add_setting") {
echo "<form action='admin.php?view=settings&do=add_setting2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr style='background:url(".$siteurl."inc/images/topbg.jpg) repeat-x;'><tr><td>Section</td><td><select name='section' class='input'>";
$sql = mysql_query("SELECT * FROM ".$pre."settings WHERE type = 'section' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($_GET['section'] == $r[name]) {
echo "<option value='".$r[name]."' selected>-- ".$r[name]." --</option>";
} else {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}
}
echo "</select></td></tr><tr><td>Name</td><td><input type='text' name='name' size='16' class='input'></td></tr><tr><td>Data</td><td><input type='text' name='data' size='16' class='input'></td></tr><tr><td>Description</td><td><textarea name='description' cols='60' rows='16' class='input'></textarea></td></tr><tr><td><tr><td><input type='submit' value='Add ".ucwords($_GET['name'])." Setting' class='input'></td></tr></table></form>";
}

if ($_GET['do'] == "add_setting2") {
$_POST["name"] = str_replace(" ", "_", strtolower($_POST["name"]));
$query = @mysql_query("INSERT INTO ".$pre."settings VALUES (null, '".addslashes($_POST["name"])."', '".addslashes($_POST["description"])."', '".addslashes($_POST["data"])."', 'setting', '".addslashes($_POST["section"])."')");

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=settings&settings=".$_POST['section']);
echo "Setting for <b>".$_POST['name']."</b> have been added. <a href='admin.php?view=settings&settings=".$_POST['section']."'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "Setting could not be added. This error has been sent to the <b>AdaptBB</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit_setting") {
while (@list(, $i) = @each ($_POST['id'])) {
if ($_POST["del_$i"]) {
$query = @mysql_query("DELETE FROM ".$pre."settings WHERE id = '".$i."'");
} else {
$query = @mysql_query("UPDATE ".$pre."settings SET data = '".addslashes($_POST["data_$i"])."', name = '".addslashes($_POST["name_$i"])."', description = '".addslashes($_POST["description_$i"])."' WHERE id = '".$i."'");
}
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=settings&settings=".$_GET['name']);
echo "Settings for <b>".$_GET['name']."</b> have been updated/deleted. <a href='admin.php?view=settings&settings=".$_GET['name']."'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "Settings could not be updated/deleted. This error has been sent to the <b>AdaptBB</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "del_section") {
$query = @mysql_query("DELETE FROM ".$pre."settings WHERE name = '".addslashes($_GET["id"])."' AND type = 'section'");

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=settings");
echo "The setting section has been deleted. <a href='admin.php?view=settings'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The setting section could not be deleted. This error has been sent to the <b>AdaptBB</b> support team and you will be contacted soon.";
}
}
}
?>