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
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Date</b></td><td><b>Votes</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."polls WHERE type = 'poll' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr>";
} else {
echo "<tr>";
}
echo "<td>".$r[id]."</td><td>".stripslashes($r[name])."</td><td>".date($setting["date_format"], $r[date])."</td><td>".$r[votes]."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=polls&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=polls&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N','N','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."polls WHERE type = 'poll'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=polls&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=polls&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=polls&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo '<script type="text/javascript">
function addEvent() {
  var ni = document.getElementById(\'options\');
  var numi = document.getElementById(\'default\');
  var num = (document.getElementById("default").value -1)+ 2;
  numi.value = num;
  var divIdName = "my"+num+"Div";
  var newdiv = document.createElement(\'div\');
  newdiv.setAttribute("id",divIdName);
  newdiv.innerHTML = "<p>Option <span class=\'drop\'>#"+num+"</span></p> <input type=\'text\' name=\'options[] value=\'\' class=\'title\'> <a href=\"javascript:;\" onclick=\"removeElement(\'"+divIdName+"\')\">X</a>";
  ni.appendChild(newdiv);
}

function removeElement(divNum) {
  var d = document.getElementById(\'options\');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
}
</script>';
echo "<form action='admin.php?view=polls&do=add2' method='post'><input type='hidden' value='1' id='default' /><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Poll</p><input type='text' name='poll_name' size='25' class='addtitle'><br /><small>The Poll Question</small></td></tr><tr><td><p>Multiple options <span class='drop'>selectable?</span></p><input type='checkbox' name='multiple' value='yes' class='select'><br /><small>Checked, someone can select more than one option for the poll</small></td></tr><tr><td><p>Custom <span class='drop'>options?</span></p><input type='checkbox' name='custom' value='yes' class='select'><br /><small>Checked, a user can enter in a custom poll option.</small></td></tr></table><br /><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td>";

echo "<div id='options'><p>Option <span class='drop'>#1</span></p> <input type='text' name='options[]' value='' class='title'></div>";

echo "<br /></td><td></td></tr><tr><td><input type='submit' value='Add Poll' class='addContent-button'></td><td><input name='anotherbutton' id='anotherbutton' value='Add Option' onclick='addEvent();' type='button' class='addContent-button' /></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
$opta = $_POST['multiple'].",".$_POST['custom'];

if (mysql_query("INSERT INTO ".$pre."polls VALUES (null, 0, '".addslashes(urldecode($_POST['poll_name']))."', 'poll', '".$opta."', 0, 0, '".time()."')") == TRUE) {
$id = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."polls WHERE poll_id = '' AND article_id = '' ORDER BY `id` DESC"));

while (list(, $i) = each ($_POST['options'])) {
if ($i) {
$query = mysql_query("INSERT INTO ".$pre."polls VALUES (null, 0, '".$i."', 'option', '', '".$id[0]."', 0, '".time()."')");
}
}
mysql_query("UPDATE ".$pre."polls SET poll_id = '".$id[0]."' WHERE id = '".$id[0]."'");

//echo re_direct("1500", "admin.php?view=polls");
echo "The poll <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=polls'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The poll could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT id,name,options,article_id FROM ".$pre."polls WHERE id = '".$_GET['id']."' AND type = 'poll'"));
$ex = explode(",", $r[2]);

echo '<script type="text/javascript">
function addEvent() {
  var ni = document.getElementById(\'options\');
  var numi = document.getElementById(\'default\');
  var num = (document.getElementById("default").value -1)+ 2;
  numi.value = num;
  var divIdName = "my"+num+"Div";
  var newdiv = document.createElement(\'div\');
  newdiv.setAttribute("id",divIdName);
  newdiv.innerHTML = "<p>New Option <span class=\'drop\'>#"+num+"</span></p> <input type=\'text\' name=\'options[] value=\'\' class=\'title\'> <a href=\"javascript:;\" onclick=\"removeElement(\'"+divIdName+"\')\">X</a>";
  ni.appendChild(newdiv);
}

function removeElement(divNum) {
  var d = document.getElementById(\'options\');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
}
</script>';
echo "<form action='admin.php?view=polls&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><input type='hidden' value='1' id='default' /><input type='hidden' name='article_id' value='".$r[3]."'><tr><td><p>Poll</p><input type='text' name='poll_name' size='25' class='addtitle' value=\"".stripslashes($r[1])."\"><br /><small>The Poll Question</small></td></tr><tr><td><p>Multiple options <span class='drop'>selectable?</span></p><input type='checkbox' name='multiple' value='yes' class='select'";
if ($ex[0]) {
echo " checked";
}
echo "><br /><small>If checked someone can select more than one option for the poll</small></td></tr><tr><td><p>Custom <span class='drop'>options?</span></p><input type='checkbox' name='custom' value='yes' class='select'";
if ($ex[1]) {
echo " checked";
}
echo "><br /><small>If checked a user can enter in a custom poll option.</small></td></tr></table><br /><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'>";

$sql = mysql_query("SELECT * FROM ".$pre."polls WHERE type = 'option' AND poll_id = '".$r[0]."' ORDER BY `id` ASC");
for($i = 1; $row = mysql_fetch_assoc($sql); $i++) {
echo "<input type='hidden' name='ids[]' value='".$row[id]."'><tr><td><p>Option <span class='drop'>#".$i."</span></p><input type='text' name='option_".$row[id]."' value=\"".htmlspecialchars(stripslashes($row[name]))."\" class='addtitle'>&nbsp;&nbsp;Delete? <input type='checkbox' name='del_".$row[id]."' value='delete' class='select'></td></tr>";
}

echo "</table><br /><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td>";

echo "<div id='options'><p>New Option <span class='drop'>#1</span></p><input type='text' name='options[]' value='' class='title'></div>";

echo "<br /></td><td></td></tr><tr><td><input type='submit' value='Edit Poll' class='addContent-button'>&nbsp;&nbsp;<input name='anotherbutton' id='anotherbutton' value='Add Option' onclick='addEvent();' type='button' class='addContent-button' />&nbsp;&nbsp;<input type='submit' name='delete_poll' value='Delete Poll' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
$opt = $_POST['multiple'].",".$_POST['custom'];

$query = mysql_query("UPDATE ".$pre."polls set options = '".$opt."', name = '".addslashes($_POST['poll_name'])."' WHERE id = '".$_GET['id']."'");
while (list(, $i) = each ($_POST['options'])) {
if ($i) {
$query = mysql_query("INSERT INTO ".$pre."polls VALUES (null, '".$_POST['article_id']."', '".$i."', 'option', '', '".$_GET['id']."', 0, '".time()."')");
}
}

while (@list(, $i) = @each ($_POST['ids'])) {
if ($_POST["del_$i"]) {
$query = @mysql_query("DELETE FROM ".$pre."polls WHERE id = '".$i."'");
} else {
$query = @mysql_query("UPDATE ".$pre."polls SET name = '".addslashes($_POST["option_$i"])."' WHERE id = '".$i."'");
}
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=polls");
echo "The poll <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=polls'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The poll could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete" or $_POST['delete_poll']) {
if (mysql_query("DELETE FROM ".$pre."polls WHERE poll_id = '".$_GET['_id']."'") == TRUE) {

mysql_query("ALTER TABLE ".$pre."polls AUTO_INCREMENT =".$_GET['id']);
echo re_direct("1500", "admin.php?view=polls");
echo "The poll has been deleted. <a href='admin.php?view=polls'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The poll could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>