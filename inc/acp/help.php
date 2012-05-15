<?php
if (!$_GET['do']) {
$smarty->display($skin.'/admin_header.tpl');

echo "<link type='text/css' media='screen' rel='stylesheet' href='".$siteurl."inc/js/colorbox.css' />
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
		<script type='text/javascript' src='".$siteurl."inc/js/jquery.colorbox-min.js'></script>
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$(\".help\").colorbox({width:\"40%\", height:\"50%\", iframe:true});
			});
		</script>
		
		<br /><br /><center><a class='help' href='".$siteurl."inc/acp/help.php?do=default'>Launch Help</a><br /><a href='admin.php?view=share&do=help'>Find more help files</a></center>";
} else {
include("../../config.php");
echo "<link rel='stylesheet' href='".$siteurl."inc/folder-tree-static.css' type='text/css'>
	<link rel='stylesheet' href='".$siteurl."inc/context-menu.css' type='text/css'>
	<link rel='stylesheet' href='".$siteurl."inc/style_admin.css' type='text/css'>
	<script type='text/javascript' src='".$siteurl."inc/js/ajax.js'></script>
	<script type='text/javascript' src='".$siteurl."inc/js/folder-tree-static.js'></script>
	<script type='text/javascript' src='".$siteurl."inc/js/context-menu.js'></script>

	<table width='100%' height='100%' cellpadding='5' cellspacing='2'><tr><td style='border-right:1px solid black' height='100%' width='30%' valign='top'><center><h2 class='light'>Menu</h2></center>

		<ul id='dhtmlgoodies_tree' class='tree'><form action='".$siteurl."inc/acp/help.php?do=search' method='get'><input type='hidden' name='do' value='search'>
		<input type='text' name='search' size='18' class='input'> <input type='submit' value='Go' class='input'></form><br />";
$sqls = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '' ORDER BY `field_name` ASC");
while($row = mysql_fetch_array($sqls)) {
unset($data);
echo "<li><a href='".$siteurl."inc/acp/help.php?do=file&id=".$row[id]."' id='node_".$row[id]."'>".stripslashes($row[field_name])."</a><a href='".$siteurl."inc/acp/help.php?do=file&id=".$row[id]."'><img src='".$siteurl."inc/images/goto.png'></a>";
$sqls2 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($row[field_name])."' ORDER BY `field_name` ASC");
while($rows = mysql_fetch_array($sqls2)) {
$check = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($rows[field_name])."'"));
if ($check == 0) {
$data .= "<li class='sheet.gif'>";
} else {
$data .= "<li>";
}
$data .= "<a href='".$siteurl."inc/acp/help.php?do=file&id=".$rows[id]."' id='node_".$rows[id]."'>".stripslashes($rows[field_name])."</a>
";
if ($check > 0) {
$data .= "<a href='".$siteurl."inc/acp/help.php?do=file&id=".$rows[id]."'><img src='".$siteurl."inc/images/goto.png'></a>";
$sqls3 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($rows[field_name])."' ORDER BY `field_name` ASC");
for($i = 0; $rowm = mysql_fetch_assoc($sqls3); $i++) {
if ($i == 0) {
$data .= "
<ul>
";
}
$data .= "<li class='sheet.gif'><a href='".$siteurl."inc/acp/help.php?do=file&id=".$rowm[id]."' id='node_".$rowm[id]."'>".stripslashes($rowm[field_name])."</a></li>";
if ($i == 0) {
$data .= "
</ul></li>";
}
}
}
}
if ($data) {
echo "
<ul>
".$data."
</li></ul>";
}
}
		echo "
	</ul>
	<a href='#' onclick='expandAll(\"dhtmlgoodies_tree\");return false'>Expand all</a>
	<a href='#' onclick='collapseAll(\"dhtmlgoodies_tree\");return false'>Collapse all</a><br /><br />

	<a href='".$siteurl."inc/acp/help.php?do=add'>Add Help Files</a><br />
	<a href='".$siteurl."inc/acp/help.php?do=manage'>Manage Help Files</a>

	<script type='text/javascript'>
	initContextMenu();
	</script>
	</td><td valign='top'>";

if ($_GET['do'] == "add") {
echo wysiwyg();
echo "<form action='".$siteurl."inc/acp/help.php?do=add2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td>Name</td><td><input type='text' name='name' size='16' class='input'></td></tr><tr><td>Section</td><td><select name='section' class='input'><option value=''>- New Help Section -</option>";
$sqls = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '' ORDER BY `field_name` ASC");
while($row = mysql_fetch_array($sqls)) {
echo "<option value='".stripslashes($row[field_name])."'>".stripslashes($row[field_name])."</option>";
$sqls2 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($row[field_name])."' ORDER BY `field_name` ASC");
while($rows = mysql_fetch_array($sqls2)) {
echo "<option value='".stripslashes($rows[field_name])."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($rows[field_name])."</option>";
}
}
echo "</select></td></tr><tr><td>Content</td><td><textarea name='content' cols='15' rows='15' class='input'></textarea></td></tr><tr><td><input type='submit' value='Add Help File' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px;'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
if (strtolower($setting["wysiwyg"]) == "no") {
$text = preg_replace("/\n/", "<br />\n", addslashes($_POST["content"]));
} else {
$text = addslashes($_POST['content']);
}
if (mysql_query("INSERT INTO ".$pre."data VALUES (null, '".addslashes($_POST["name"])."', 'help-file', '".$text."', '".addslashes($_POST['section'])."')") == TRUE) {

echo re_direct("1500", $siteurl."inc/acp/help.php?do=default");
echo "The help file <b>".stripslashes($_POST['name'])."</b> has been added. <a href='".$siteurl."inc/acp/help.php?do=default'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The help file could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "edit") {
echo wysiwyg();
$i = $_GET['id'];
$r = mysql_fetch_row(mysql_query("SELECT field_name,data,item_id FROM ".$pre."data WHERE id = '".$i."'"));
echo "<form action='".$siteurl."inc/acp/help.php?do=manage2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td>Name</td><td><input type='hidden' name='id[]' value='".$i."'><input type='hidden' name='oldname_".$i."' value='".stripslashes($r[0])."'><input type='text' size='16' name='name_".$i."' value='".stripslashes($r[0])."' class='input'></td></tr><tr><td>Section</td><td><select name='section_".$i."' class='input'><option value=''>- Help Section -</option>";
$sqls = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '' ORDER BY `field_name` ASC");
while($row = mysql_fetch_array($sqls)) {
if ($row[field_name] == $r[2]) {
$def = " selected>- ";
} else {
$def = ">";
}
echo "<option value='".stripslashes($row[field_name])."'".$def.stripslashes($row[field_name])."</option>";
$sqls2 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($row[field_name])."' ORDER BY `field_name` ASC");
while($rows = mysql_fetch_array($sqls2)) {
if ($rows[field_name] == $r[2]) {
$def2 = " selected>- ";
} else {
$def2 = ">";
}
echo "<option value='".stripslashes($rows[field_name])."'".$def2."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($rows[field_name])."</option>";
}
}
echo "</select></td></tr><tr><td>Content</td><td><textarea name='content_".$i."' cols='15' rows='15' class='input'>".stripslashes($r[1])."</textarea></td></tr><tr><td><input type='submit' value='Update Help Item' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px;'></td></tr></table></form>";
}

if ($_GET['do'] == "manage") {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

echo "<form action='".$siteurl."inc/acp/help.php?do=manage2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td>Name</td><td>Type</td><td>Section</td><td>Edit</td><td>Delete</td></tr>";
$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
if ($r[item_id]) {
$type = "File";
} else {
$type = "Section";
}

echo "<td><input type='hidden' name='id[]' value='".$r[id]."'><input type='hidden' name='oldname_".$r[id]."' value='".stripslashes($r[field_name])."'><input type='text' size='16' name='name_".$r[id]."' value='".stripslashes($r[field_name])."' class='input'></td><td>".$type."</td><td><select name='section_".$r[id]."' class='input'><option value=''>- Help Section -</option>";
$sqls = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '' ORDER BY `field_name` ASC");
while($row = mysql_fetch_array($sqls)) {
if ($row[field_name] == $r[item_id]) {
$def = " selected>* ";
} else {
$def = ">";
}
echo "<option value='".stripslashes($row[field_name])."'".$def.stripslashes($row[field_name])."</option>";
$sqls2 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($row[field_name])."' ORDER BY `field_name` ASC");
while($rows = mysql_fetch_array($sqls2)) {
if ($rows[field_name] == $r[item_id]) {
$def2 = " selected>* ";
} else {
$def2 = ">";
}
echo "<option value='".stripslashes($rows[field_name])."'".$def2."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($rows[field_name])."</option>";
}
}
echo "</select></td><td><a href='".$siteurl."inc/acp/help.php?do=edit&id=".$r[id]."'><img src='".$siteurl."images/edit.png' title='Edit'></a></td><td><input type='checkbox' name='delete_".$r[id]."' value='yes' class='input'></td></tr>";

$i++;
}
echo "<tr><td><input type='submit' value='Update' class='input'></td></tr></table></form>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'help-file'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {
echo "<center>";

if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$pageurl."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$pageurl."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$pageurl."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($_GET['do'] == "manage2") {
while (list(, $i) = each ($_POST['id'])) {
if ($_POST["delete_$i"]) {
mysql_query("DELETE FROM ".$pre."data WHERE id = '".$i."'");
} else {
if ($_POST["content_$i"]) {
if (strtolower($setting["wysiwyg"]) == "no") {
$text = preg_replace("/\n/", "<br />\n", addslashes($_POST["content_$i"]));
} else {
$text = addslashes($_POST["content_$i"]);
}
$content = ", data = '".$text."'";
}
mysql_query("UPDATE ".$pre."data SET field_name = '".addslashes($_POST["name_$i"])."', item_id = '".addslashes($_POST["section_$i"])."'".$content." WHERE id = '".$i."'");
mysql_query("UPDATE ".$pre."data SET item_id = '".addslashes($_POST["name_$i"])."' WHERE item_id = '".addslashes($_POST["oldname_$i"])."'");
}
}
echo re_direct("1500", $siteurl."inc/acp/help.php?do=default");
echo "The help file(s) have been updated. <a href='".$siteurl."inc/acp/help.php?do=default'>Return</a>";
}

if ($_GET['do'] == "file" && $_GET['id']) {
$r = mysql_fetch_row(mysql_query("SELECT field_name,data,item_id FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
echo "<div align='right'><a href='".$siteurl."inc/acp/help.php?do=edit&id=".$_GET['id']."'><img src='".$siteurl."images/edit.png' title='Edit'></a></div>
<table cellpadding='5' cellspacing='0' border='0' width='100%' align='left'><tr><td>
<h2>".stripslashes($r[0])."</h2>
".stripslashes($r[1])."</td></tr></table>";
}

if ($_GET['do'] == "search" && $_GET['search']) {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

echo "<form action='".$siteurl."inc/acp/help.php?do=manage2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td>Name</td><td>View</td><td>Type</td><td>Section</td><td>Edit</td><td>Delete</td></tr>";
$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND field_name LIKE '%".addslashes($_GET['search'])."%' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
if ($r[item_id]) {
$type = "File";
} else {
$type = "Section";
}

echo "<td><input type='hidden' name='id[]' value='".$r[id]."'><input type='hidden' name='oldname_".$r[id]."' value='".stripslashes($r[field_name])."'><input type='text' size='16' name='name_".$r[id]."' value='".stripslashes($r[field_name])."' class='input'></td><td><a href='".$siteurl."inc/acp/help.php?do=file&id=".$r[id]."'>Link</a></td><td>".$type."</td><td><select name='section_".$r[id]."' class='input'><option value=''>- Help Section -</option>";
$sqls = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '' ORDER BY `field_name` ASC");
while($row = mysql_fetch_array($sqls)) {
if ($row[field_name] == $r[item_id]) {
$def = " selected>* ";
} else {
$def = ">";
}
echo "<option value='".stripslashes($row[field_name])."'".$def.stripslashes($row[field_name])."</option>";
$sqls2 = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".addslashes($row[field_name])."' ORDER BY `field_name` ASC");
while($rows = mysql_fetch_array($sqls2)) {
if ($rows[field_name] == $r[item_id]) {
$def2 = " selected>* ";
} else {
$def2 = ">";
}
echo "<option value='".stripslashes($rows[field_name])."'".$def2."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($rows[field_name])."</option>";
}
}
echo "</select></td><td><a href='".$siteurl."inc/acp/help.php?do=edit&id=".$r[id]."'><img src='".$siteurl."images/edit.png' title='Edit'></a></td><td><input type='checkbox' name='delete_".$r[id]."' value='yes' class='input'></td></tr>";

$i++;
}
echo "<tr><td><input type='submit' value='Update' class='input'></td></tr></table></form>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'help-file'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {
echo "<center>";

if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$pageurl."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$pageurl."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$pageurl."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

echo "</td></tr></table>";
}
?>