<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
		<col id='col1_6'></col>
		<col id='col1_7'></col>
		<col id='col1_8'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Username</b></td><td><b>E-Mail</b></td><td><b>Group</b></td><td><b>Verified</b></td><td><b>Activated</b></td><td><b>Registered</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

if ($_GET['ver']) {
$ver2 = " WHERE ver = 'no'";
}

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."users".$ver2." ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td>".get_user($r[id])."</td><td>".$r[email]."</td><td>".$r[group]."</td>";
if (strtolower($setting["register_verify"]) == "yes" or $r[ver] == "no") {
if ($r[ver] == "yes") {
echo "<td><img src='images/check.png'></td>";
} elseif ($r[ver] == "no") {
if ($p[1]) {
echo "<td><a href='admin.php?view=users&do=verify&id=".$r[id]."' onclick=\"return confirm('Are you sure you wish to verify this user?');\"><img src='images/attn.png' title='Verify'></a></td>";
}
}
} else {
echo "<td><img src='images/check.png'></td>";
}
if (strtolower($setting["register_activate"]) == "yes" or $r[act] == "no") {
if ($r[act] == "yes") {
echo "<td><img src='images/check.png'></td>";
} elseif ($r[act] == "no") {
if ($p[1]) {
echo "<td><a href='admin.php?view=users&do=activate&id=".$r[id]."' onclick=\"return confirm('Are you sure you wish to activate this user?');\"><img src='images/attn.png' title='Activate'></a></td>";
}
}
} else {
echo "<td><img src='images/check.png'></td>";
}
echo "<td>".date($setting["date_format"], $r[reg_date])."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=users&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=users&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S','S','S', 'S', 'N', 'S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."users".$ver2),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=users&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=users&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=users&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo "<form action='admin.php?view=users&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Username</p><input type='text' name='username' size='12' class='title'></td></tr><tr><td><p><span class='drop'>Password</span></p><input type='text' name='password' size='15' class='title'></td></tr><tr><td><p>E-Mail</p><input type='text' name='email' size='20' class='title'></td></tr><tr><td><p><span class='drop'>Group</span></p><select name='group' class='select'>";
$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
echo "</select></td></tr><tr><td><br /><input type='submit' value='Add User' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
$level = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."levels WHERE type = 'level' ORDER BY `points` ASC LIMIT 1"));
$def_skin = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."skins WHERE skin = '' AND template = 'yes|'"));
if (mysql_query("INSERT INTO ".$pre."users VALUES (null, '".addslashes($_POST["username"])."', '".md5($salt.$_POST['username'].md5($_POST["password"]))."', '".addslashes($_POST["email"])."', '".addslashes($_POST["group"])."', '".$level[0]."', 0, '".time()."', 'yes', 'yes', 'I am a new user to ".addslashes($setting[sitename])."!', '".time()."', '".$def_skin[0]."')") == TRUE) {
echo re_direct("1500", "admin.php?view=users");
echo "The user <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=users'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The user could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT username,email,`group`,skin FROM ".$pre."users WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?view=users&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Username</p><input type='text' name='username' value='".$r[0]."' size='12' class='title'></td></tr><tr><td><p>New <span class='drop'>Password?</span></p><input type='text' name='password' size='15' class='title'></td></tr><tr><td><p>E-Mail</p><input type='text' name='email' value='".$r[1]."' size='20' class='title'></td></tr><tr><td><p><span class='drop'>Group</span></p><select name='group' class='select'>";
$sql = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql)) {
if ($r[2] == $row[name]) {
echo "<option value='".$row[name]."' selected>- ".ucwords($row[name])." -</option>";
} else {
echo "<option value='".$row[name]."'>".ucwords($row[name])."</option>";
}
}
echo "</select></td></tr><tr><td><p>Skin</p><select name='skin' class='select'>";
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `name` ASC");
while($ra = mysql_fetch_array($sql)) {
if ($ra[name] == $r[3]) {
echo "<option value='".$ra[id]."' selected>-- ".$skin." --</option>";
} else {
echo "<option value='".$ra[id]."'>".stripslashes($ra[name])."</option>";
}
}
echo "</select></td></tr><tr><td><br /><input type='submit' value='Update User' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
$sql = "";
$sql .= "UPDATE ".$pre."users SET username = '".addslashes($_POST["username"])."', email = '".addslashes($_POST["email"])."', `group` = '".addslashes($_POST["group"])."', skin = '".addslashes($_POST["skin"])."'";
if ($_POST['password']) {
$sql .= ", password = '".md5($salt.$_POST['username'].md5($_POST['password']))."'";
}
$sql .= " WHERE id = '".$_GET['id']."'";

if (mysql_query($sql) == TRUE) {
echo re_direct("1500", "admin.php?view=users");
echo "The user <b>".stripslashes($_POST['username'])."</b> has been updated. <a href='admin.php?view=users'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The user could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."users WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."users AUTO_INCREMENT =".$_GET['id']);
echo re_direct("1500", "admin.php?view=users");
echo "The user has been deleted. <a href='admin.php?view=users'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The user could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "activate") {
if (mysql_query("UPDATE ".$pre."users SET act = 'yes' WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=users");
echo "The user has been activated. <a href='admin.php?view=users'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The user could not be activated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "verify") {
if (mysql_query("UPDATE ".$pre."users SET ver = 'yes' WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=users");
echo "The user has been verified. <a href='admin.php?view=users'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The user could not be verified. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>