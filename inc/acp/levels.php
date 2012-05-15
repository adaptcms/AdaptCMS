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
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Type</b></td><td><b>Points</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."levels ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><font color='".$r[color]."'><b>".stripslashes($r[name])."</b></font></td><td>".$r[type]."</td><td>".$r[points]."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=levels&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=levels&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S','N','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."levels"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=levels&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=levels&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=levels&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo "<script type='text/javascript' src='inc/js/color/jscolor.js'></script>

<form action='admin.php?view=levels&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='12' class='addtitle'><input type='hidden' name='type' value='level'></td></tr><tr><td><p>Image <span class='drop'>Icon</span></p><input type='text' name='data' size='15' class='title'></td></tr><tr><td><p>Points <span class='drop'>Required</span></p><input type='text' name='points' size='10' class='title'></td></tr><tr><td><p>Group</p><select name='group' class='select'><option value='' selected>- None -</option>";
$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
echo "</select></td></tr><tr><td><p><span class='drop'>Color</span></p><input type='text' name='color' size='14' class='color {adjust:false,hash:true} title'></td></tr><tr><td><br /><input type='submit' value='Add Level' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add_point") {
echo "<form action='admin.php?view=levels&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='20' class='addtitle'><input type='hidden' name='type' value='point'></td></tr><tr><td><p>Reference <span class='drop'>URL</span></p><input type='text' name='data' size='35' class='addtitle' value='index.php?do='></td></tr><tr><td><p>Points <span class='drop'>Amount</span></p><input type='text' name='points' size='10' class='title'></td></tr><tr><td><br /><input type='submit' value='Add Reward' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
if (mysql_query("INSERT INTO ".$pre."levels VALUES (null, '".addslashes($_POST["name"])."', '".addslashes($_POST["type"])."', '".addslashes($_POST["data"])."', '".addslashes($_POST["points"])."', '".addslashes($_POST["group"])."', '".$_POST["color"]."')") == TRUE) {
echo re_direct("1500", "admin.php?view=levels");
echo "The level/point reward <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=levels'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbname, $dbpass)), $domain);
echo "The level/point reward could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,`type`,data,points,`group`,color FROM ".$pre."levels WHERE id = '".$_GET['id']."'"));
echo "<script type='text/javascript' src='inc/js/color/jscolor.js'></script>

<form action='admin.php?view=levels&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'></td></tr>";

if ($r[1] == "level") {
echo "<tr><td><p>Image <span class='drop'>Icon</span></p><input type='text' name='data' size='15' class='title' value='".$r[2]."'></td></tr><tr><td><p>Points <span class='drop'>Required</span></p><input type='text' name='points' size='10' class='title' value='".$r[3]."'></td></tr><tr><td><p>Group</p><select name='group' class='select'><option value=''>- None -</option>";
$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql)) {
if ($r[4] == $row[name]) {
echo "<option value='".$row[name]."' selected>- ".ucwords($row[name])." -</option>";
} else {
echo "<option value='".$row[name]."'>".ucwords($row[name])."</option>";
}
}
echo "</select></td></tr><tr><td><p><span class='drop'>Color</span></p><input type='text' name='color' size='14' class='color {adjust:false,hash:true} title' value='".$r[5]."'></td></tr>";
} elseif ($r[1] == "point") {
echo "<tr><td><p>Reference <span class='drop'>URL</span></p><input type='text' name='data' size='35' class='addtitle' value='".$r[2]."'></td></tr><tr><td><p>Points <span class='drop'>Amount</span></p><input type='text' name='points' size='10' class='title' value='".$r[3]."'></td></tr>";
}
echo "<tr><td><br /><input type='submit' value='Update level' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
$sql = mysql_query("UPDATE ".$pre."levels SET name = '".addslashes($_POST["name"])."', data = '".addslashes($_POST["data"])."', points = '".addslashes($_POST["points"])."', `group` = '".addslashes($_POST["group"])."', color = '".addslashes($_POST["color"])."' WHERE id = '".$_GET['id']."'");

if ($sql == TRUE) {
echo re_direct("1500", "admin.php?view=levels");
echo "The level <b>".stripslashes($_POST['ame'])."</b> has been updated. <a href='admin.php?view=levels'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbname, $dbpass)), $domain);
echo "The level could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."levels WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."levels AUTO_INCREMENT =".$_GET['id']);
echo re_direct("1500", "admin.php?view=levels");
echo "The level has been deleted. <a href='admin.php?view=levels'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbname, $dbpass)), $domain);
echo "The level could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>