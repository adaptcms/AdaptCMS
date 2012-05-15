<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><a href='".url("section",$r[name])."'>".stripslashes($r[name])."</a></td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=sections&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=sections&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."sections"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=sections&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=sections&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=sections&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo "<form action='admin.php?view=sections&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='12' class='addtitle'></td></tr><tr><td><input type='submit' value='Add Section' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
if (mysql_query("INSERT INTO ".$pre."sections VALUES (null, '".addslashes($_POST["name"])."')") == TRUE) {
$sql2 = mysql_query("SELECT * FROM ".$pre."skins WHERE template = 'skin' ORDER BY `id` DESC");
while($row = mysql_fetch_array($sql2)) {
mysql_query("INSERT INTO ".$pre."skins VALUES (null, '".addslashes($_POST["name"])."', '".$row[name]."', '', '".time()."')");
}

$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$fet = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$r[name]."' ORDER BY `id` DESC LIMIT 1"));
mysql_query("INSERT INTO ".$pre."permissions VALUES (null, '".$r[name]."', '".addslashes($_POST["name"])."', '".$fet[0]."')");
}

echo re_direct("1500", "admin.php?view=sections");
echo "The section <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=sections'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The section could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."sections WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?view=sections&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr><tr><td><input type='submit' value='Update' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
$query = mysql_query("UPDATE ".$pre."sections SET name = '".addslashes($_POST["name"])."' WHERE id = '".$_GET['id']."'");
mysql_query("UPDATE ".$pre."fields SET section = '".addslashes($_POST["name"])."' WHERE section = '".addslashes($_POST["old_name"])."'");
mysql_query("UPDATE ".$pre."content SET section = '".addslashes($_POST["name"])."' WHERE section = '".addslashes($_POST["old_name"])."'");
mysql_query("UPDATE ".$pre."skins SET name = '".addslashes($_POST["name"])."' WHERE name = '".addslashes($_POST["old_name"])."'");
mysql_query("UPDATE ".$pre."permissions SET name = '".addslashes($_POST["name"])."' WHERE name = '".addslashes($_POST["old_name"])."'");

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=sections");
echo "The section <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=sections'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The section could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."sections WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("DELETE FROM ".$pre."fields WHERE section = '".addslashes($_GET["name"])."'");
mysql_query("DELETE FROM ".$pre."content WHERE section = '".addslashes($_GET["name"])."'");
mysql_query("DELETE FROM ".$pre."skins WHERE name = '".addslashes($_GET["name"])."'");
mysql_query("DELETE FROM ".$pre."permissions WHERE name = '".addslashes($_GET["name"])."'");
mysql_query("ALTER TABLE ".$pre."sections AUTO_INCREMENT =".$_GET['id']);
echo re_direct("1500", "admin.php?view=sections");
echo "The section has been deleted. <a href='admin.php?view=sections'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The section could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>