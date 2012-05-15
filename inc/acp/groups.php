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
<thead><tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Group</b></td><td><b>Image</b></td><td align='left'><b>Users</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
$users = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE `group` = '".$r[name]."'"));
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><font color='".$r[color]."'><b>".stripslashes($r[name])."</b></font></td><td>";
if ($r[image]) {
echo "<img src='".$r[image]."'>";
} else {
echo "No Image";
}
echo "</td><td>".$users."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=groups&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=groups&do=delete&id=".$r[id]."&name=".urlencode($r[name])."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S','N','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."groups"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=groups&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=groups&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=groups&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo "<script type='text/javascript' src='inc/js/color/jscolor.js'></script>

<form action='admin.php?view=groups&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Group <span class='drop'>Name</span></p><input type='text' name='name' size='12' class='addtitle'></td></tr><tr><td><p>Default <span class='drop'>Settings</span></p><select name='level' size='4' class='select'><option value='1'>Administrator</option><option value='2'>Staff</option><option value='3'>Member</option><option value='4'>Guest, Non-Registered</option></select></td></tr><tr><td><p>Color</p><input type='text' name='color' size='14' class='color {adjust:false,hash:true} title'></td></tr><tr><td><p><span class='drop'>Image</span></p><select name='image' class='select'><option value='' selected></option>";

if ($handle = @opendir("inc/rank_img")) {
while (false !== ($file = @readdir($handle))) {
if ($file != '.' and $file != '..' and $file != 'index.html') {
echo "<option value='".$siteurl."inc/rank_img/".$file."'>".$file."</option>";
}
}
}

echo "</select></td></tr><tr><td><p>Default?</p><select name='default' class='select'><option value=''></option><option value='default-guest'>Default Guest Group</option><option value='default-member'>Default Member Group</option></select></td></tr><tr><td><br /><input type='submit' value='Add Group' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
if (mysql_query("INSERT INTO ".$pre."groups VALUES (null, '".addslashes($_POST["name"])."', '".$_POST['color']."', '".$_POST['image']."', '".$_POST['default']."')") == TRUE) {

$array = array("media","fields","content","comments","groups","plugins","sections","levels","settings","skins","users","tools","polls","pages","files");
while (list(, $i) = each ($array)) {
if ($_POST['level'] == 1) {
$var2 = 1;
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$i."', '".$var2."|".$var2."|".$var2."')");
} elseif ($_POST['level'] == 2) {
if ($i == "media" or $i == "content" or $i == "comments" or $i == "polls" or $i == "files") {
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$i."', '1||')");
} else {
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$i."', '||')");
}
} else {
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$i."', '||')");
}
}

$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($_POST['level'] == 1 or $_POST['level'] == 2) {
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$r[name]."', '1|1|1|')");
} elseif ($_POST['level'] == 3) {
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".$r[name]."', '1|||1')");
}
}

echo re_direct("1500", "admin.php?view=groups");
echo "The group <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=groups'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The group could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,color,image,options FROM ".$pre."groups WHERE id = '".$_GET['id']."'"));
$name = $r[0];
echo "<script type='text/javascript' src='inc/js/color/jscolor.js'></script>

<form action='admin.php?view=groups&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Group</p><input type='text' name='name' value='".stripslashes($r[0])."' size='12' class='addtitle'></td></tr>
<tr><td><p><span class='drop'>Color</span></p><input type='text' name='color' value='".$r[2]."' size='14' class='color {adjust:false,hash:true} title'></td></tr><tr><td><p>Image</p><select name='image' class='select'><option value='' selected></option>";

if ($handle = @opendir("inc/rank_img")) {
while (false !== ($file = @readdir($handle))) {
if ($file != '.' and $file != '..' and $file != 'index.html') {
if ($r[3] == $siteurl."inc/rank_img/".$file) {
echo "<option value='".$siteurl."inc/rank_img/".$file."' selected>- ".$file." -</option>";
} else {
echo "<option value='".$siteurl."inc/rank_img/".$file."'>".$file."</option>";
}
}
}
}

echo "</select></td></tr><tr><td><p><span class='drop'>Default?</span></p><select name='default' class='select'><option value=''></option>";
if ($r[3] == "default-guest") {
echo "<option value='default-guest' checked>- Default Guest Group -</option>";
} else {
echo "<option value='default-guest'>Default Guest Group</option>";
}
if ($r[3] == "default-member") {
echo "<option value='default-member' checked>- Default Member Group -</option>";
} else {
echo "<option value='default-member'>Default Member Group</option>";
}
echo "</select></td></tr><tr><td><p>Permissions</p><table cellpadding='5' cellspacing='2' border='0' width='100%'><tr><td><b>Admin</b></td></tr><tr><td></td><td>Add</td><td>Edit</td><td>Delete</td></tr>";
$array = array("media","fields","content","comments","groups","plugins","sections","levels","settings","skins","users","tools","polls","pages","files");
while (list(, $i) = each ($array)) {
$count = "";
$count = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$i."'"));
if ($count == 1) {
$qs = mysql_fetch_row(mysql_query("SELECT data,id FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$i."'"));
$q = explode("|", $qs[0]);
}
if ($count == 2) {
mysql_query("DELETE FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$i."' LIMIT 1");
$qs = mysql_fetch_row(mysql_query("SELECT data,id FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$i."'"));
$q = explode("|", $qs[0]);
}

echo "<input type='hidden' name='count_".$i."' value='".$count."'><input type='hidden' name='id[]' value='".$i."'><tr><td>";

echo ucwords($i)."</td><td><input type='checkbox' name='add_".$i."' class='input' value='1'";
if ($q[0] == 1) {
echo " checked";
}
echo "></td><td><input type='checkbox' name='edit_".$i."' class='input' value='1'";
if ($q[1] == 1) {
echo " checked";
}
echo "></td><td><input type='checkbox' name='delete_".$i."' class='input' value='1'";
if ($q[2] == 1) {
echo " checked";
}
echo "></td></tr><input type='hidden' name='theid_".$i."' value='".$qs[1]."'>";
}

echo "<tr><td></td></tr><tr><td><br /><b>Content</b></td></tr><tr><td></td><td>Add</td><td>Edit</td><td>Delete</td><td>Verify</td></tr>";
unset($r, $q, $qs);
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
unset($count, $q, $qs);
$count = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$r[name]."'"));
if ($count == 1) {
$qs = mysql_fetch_row(mysql_query("SELECT data,id FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$r[name]."'"));
$q = explode("|", $qs[0]);
}
if ($count == 2) {
mysql_query("DELETE FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$r[name]."' LIMIT 1");
$qs = mysql_fetch_row(mysql_query("SELECT data,id FROM ".$pre."permissions WHERE `group` = '".$name."' AND name = '".$r[name]."'"));
$q = explode("|", $qs[0]);
}

echo "<input type='hidden' name='count_".str_replace(" ","_",$r[name])."' value='".$count."'><input type='hidden' name='id[]' value='".str_replace(" ","_",$r[name])."'><tr><td>";

echo $r[name]."</td><td><input type='checkbox' name='add_".str_replace(" ","_",$r[name])."' class='input' value='1'";
if ($q[0] == 1) {
echo " checked";
}
echo "></td><td><input type='checkbox' name='edit_".str_replace(" ","_",$r[name])."' class='input' value='1'";
if ($q[1] == 1) {
echo " checked";
}
echo "></td><td><input type='checkbox' name='delete_".str_replace(" ","_",$r[name])."' class='input' value='1'";
if ($q[2] == 1) {
echo " checked";
}
echo "></td><td><input type='checkbox' name='ver_".str_replace(" ","_",$r[name])."' class='input' value='1'";
if ($q[3] == 1) {
echo " checked";
}
echo "></td></tr><input type='hidden' name='theid_".str_replace(" ","_",$r[name])."' value='".$qs[1]."'>";
}
echo "</table></td></tr><tr><td><br /><input type='submit' value='Update Group' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
if (mysql_query("UPDATE ".$pre."groups SET name = '".addslashes($_POST["name"])."', color = '".addslashes($_POST["color"])."', image = '".addslashes($_POST["image"])."', options = '".$_POST["default"]."' WHERE id = '".$_GET['id']."'") == TRUE) {

while (@list(, $i) = @each ($_POST['id'])) {
if ($_POST["count_$i"] == "0") {
@mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$_POST["name"]."', '".str_replace("_"," ",$i)."', '".$_POST["add_$i"]."|".$_POST["edit_$i"]."|".$_POST["delete_$i"]."')");
} else {
@mysql_query("UPDATE ".$pre."permissions SET data = '".$_POST["add_$i"]."|".$_POST["edit_$i"]."|".$_POST["delete_$i"]."|".$_POST["ver_$i"]."', `group` = '".$_POST['name']."' WHERE id = '".$_POST["theid_$i"]."'");
}
}

echo re_direct("1500", "admin.php?view=groups");
echo "The group <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=groups'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The group could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."groups WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."groups AUTO_INCREMENT =".$_GET['id']);
mysql_query("DELETE FROM ".$pre."permissions WHERE `group` = '".urldecode($_GET['name'])."'");
echo re_direct("1500", "admin.php?view=groups");
echo "The group has been deleted. <a href='admin.php?view=groups'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The group could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>