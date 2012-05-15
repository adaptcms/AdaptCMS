<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Type</b></td><td><b>Section</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."fields ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td>".stripslashes($r[name])."</td><td>".$r[type]."</td><td>".$r[section]."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=fields&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=fields&do=delete&id=".$r[id]."&name=".urlencode($r[name])."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S', 'S', 'S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."fields"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=fields&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=fields&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=fields&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo wysiwyg();
echo "<form action='admin.php?view=fields&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='16' class='addtitle'></td></tr><tr><td><p><span class='drop'>Section</span></p><select name='sections[]' class='select' size='5' multiple>";
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}
echo "<option value='web-profile'>User Profiles</option></select></td></tr>

<tr><td><p>Type</p><select name='type' class='select'><option value='textfield'>Textfield</option><option value='textarea'>Textarea</option><option value='select'>Dropdown Box</option><option value='radio'>Radiobox(s)</option><option value='checkbox'>Checkbox(s)</option><option value='file'>File</option></select></td></tr>

<tr><td><p><span class='drop'>Data</span></p><textarea name='data' cols='55' rows='12' class='textarea'></textarea><br /><small>For example, a dropdown box you would put in 'option1,option2' and the same with radio/checkbox<br /><br />Not used for textfield or textarea</small></td></tr>

<tr><td><p>Description</p><textarea name='description' cols='60' rows='16' class='textarea'></textarea></td></tr>

<tr><td><p><span class='drop'>Editable?</span></p><input type='checkbox' name='editable' class='select' value='yes'><br /><small>(editable by registered users?)</small></td></tr>

<tr><td><p>Max/Min Character Limit</p><input type='title' name='limit1' size='3' class='input'> / <input type='title' name='limit2' size='3' class='input'></td></tr>

<tr><td><p><span class='drop'>Required?</span></p><input type='checkbox' name='required' class='select' value='yes'><br /><small>(MUST field have data for content to be added?)</small></td></tr>";

echo "<tr><td><input type='submit' value='Add Field' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
while (list(, $i) = each ($_POST['sections'])) {
$fname = strtolower(str_replace(" ", "_", addslashes($_POST["name"])));
$query = @mysql_query("INSERT INTO ".$pre."fields VALUES (null, '".$fname."', '".$i."', '".addslashes($_POST["type"])."', '".addslashes($_POST["description"])."', '".addslashes($_POST["data"])."', '".$_POST["editable"]."', '".addslashes($_POST["limit1"])."/".addslashes($_POST["limit2"])."', '".$_POST["required"]."')");
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=fields");
echo "The field <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=fields'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The field could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,section,type,data,description,editable,`limit`,required FROM ".$pre."fields WHERE id = '".$_GET['id']."'"));
$lim = explode("/", $r[6]);
echo wysiwyg();
echo "<form action='admin.php?view=fields&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'><input type='hidden' name='oldname' value='".$r[0]."'></td></tr><tr><td><p><span class='drop'>Section</span></p><select name='sections[]' class='select' multiple>";
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($i = mysql_fetch_array($sql)) {
echo "<option value='".$i[name]."'";
if ($r[1] == $i[name]) {
echo " selected";
}
echo ">".$i[name]."</option>";
}
echo "<option value='web-profile'>User Profiles</option></select></td></tr><tr><td><p>Type</p><select name='type' class='select'><option value='textfield'";
if ($r[2] == "textfield") {
echo " selected";
}
echo ">Textfield</option><option value='textarea'";
if ($r[2] == "textarea") {
echo " selected";
}
echo ">Textarea</option><option value='select'";
if ($r[2] == "select") {
echo " selected";
}
echo ">Dropdown Box</option><option value='radio'";
if ($r[2] == "radio") {
echo " selected";
}
echo ">Radiobox(s)</option><option value='checkbox'";
if ($r[2] == "checkbox") {
echo " selected";
}
echo ">Checkbox(s)</option><option value='file'";
if ($r[2] == "file") {
echo " selected";
}
echo ">File</option></select></td></tr><tr><td><p><span class='drop'>Data</span></p><textarea name='data' cols='55' rows='12' class='textarea'>".stripslashes($r[3])."</textarea><br /><small>For example, a dropdown box you would put in 'option1,option2' and the same with radio/checkbox<br /><br />Not used for textfield or textarea</small></td></tr><tr><td><p>Description</p><textarea name='description' cols='60' rows='16' class='textarea'>".stripslashes($r[4])."</textarea></td></tr><tr><td><p><span class='drop'>Editable?</span></p><input type='checkbox' name='editable' class='select' value='yes'";
if ($r[5]) {
echo " checked";
}
echo "><br /><small>(editable by registered users?)</small></td></tr><tr><td><p>Max/Min Character Limit</p><input type='text' name='limit1' size='3' class='title' value='".$lim[0]."'> / <input type='text' name='limit2' size='3' class='title' value='".$lim[1]."'></td></tr><tr><td><p><span class='drop'>Required?</span></p><input type='checkbox' name='required' class='select' value='yes'";
if ($r[7]) {
echo " checked";
}
echo "><br /><small>(MUST field have data for content to be added?)</small></td></tr>";

echo "<tr><td><input type='submit' value='Update Field' class='addContent-button'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
while (list(, $i) = each ($_POST['sections'])) {
$fname = strtolower(str_replace(" ", "_", addslashes($_POST["name"])));
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$i."' AND name = '".$_POST['oldname']."'")) == 1) {
$query = @mysql_query("UPDATE ".$pre."fields SET name = '".$fname."', section = '".$i."', type = '".addslashes($_POST["type"])."', description = '".addslashes($_POST["description"])."', data = '".addslashes($_POST["data"])."', editable = '".$_POST["editable"]."', `limit` = '".addslashes($_POST["limit1"])."/".addslashes($_POST["limit2"])."', required = '".$_POST["required"]."' WHERE id = '".$_GET['id']."'");
@mysql_query("UPDATE ".$pre."data SET field_name = '".$fname."' WHERE field_name = '".$_POST['old_name']."'");
} else {
$query = @mysql_query("INSERT INTO ".$pre."fields VALUES (null, '".$fname."', '".$i."', '".addslashes($_POST["type"])."', '".addslashes($_POST["description"])."', '".addslashes($_POST["data"])."', '".$_POST["editable"]."', '".addslashes($_POST["limit1"])."/".addslashes($_POST["limit2"])."', '".$_POST["required"]."')");
}
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=fields");
echo "The field <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=fields'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The field could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."fields WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."fields AUTO_INCREMENT =".$_GET['id']);
mysql_query("DELETE FROM ".$pre."data WHERE field_name = '".urldecode($_GET['name'])."'");
echo re_direct("1500", "admin.php?view=fields");
echo "The field has been deleted. <a href='admin.php?view=fields'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The field could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>