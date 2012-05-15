<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<a href='admin.php?view=social&do=blogs'><h2>User Blogs</h2></a>You can manage user blogs here either editing them or deleting them. Blogs are in each user's profile and all registered users can add them.<br /><br /><a href='admin.php?view=social&do=avatar'><h2>Avatars</h2></a>Here you can manage all avatars uploaded by staff or custom ones users have uploaded for use.<br /><br />";
}

$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = 'Users'"));
$p = explode("|", $ps[0]);

if ($_GET['do'] == "blogs" && !$_GET['go']) {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Title</b></td><td><b>Author</b></td><td><b>Posted</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-blog' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {

$row = explode("|||||", $r[data]);

if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><a href='".url("blogs", "", $r[id], $row[0])."'>".stripslashes($row[0])."</a></td><td>".get_user($r[item_id])."</td><td>".timef($r[field_name])."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=social&do=blogs&go=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=social&do=blogs&go=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png'></a>";
}
echo "</td></tr>";

$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S', 'N', 'S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'social-blog'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=social&do=blogs&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=social&do=blogs&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=social&do=blogs&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[1]) {
if ($_GET['do'] == "blogs" && $_GET['go'] == "edit") {
$row = mysql_fetch_row(mysql_query("SELECT data,item_id FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
$r = explode("|||||", $row[0]);
echo wysiwyg();

echo "<form action='admin.php?view=social&do=blogs&go=edit2&id=".$_GET['id']."' method='post' id='test' name='post' target='_self'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Author</p></td><td>".get_user($row[1])."</td></tr><tr><td><p>Title</p></td><td><input type='text' name='title' class='addtitle' value='".$r[0]."'></td></tr><tr><td><p>Blog</p></td><td><textarea name='blog' cols='60%' rows='15%' class='textarea'>".stripslashes($r[1])."</textarea></td></tr><tr><td><input type='submit' value='Update Blog' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "edit2") {
if (mysql_query("UPDATE ".$pre."data SET data = '".addslashes($_POST['title'])."|||||".addslashes($_POST['blog'])."', field_name = '".time()."' WHERE id = '".$_GET['id']."'") == TRUE) {
echo re_direct("1500", "admin.php?view=social&do=blogs");
echo "The blog <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=social&do=blogs'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The blog could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "blogs" && $_GET['go'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."data WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=social&do=blogs");
echo "The blog has been deleted. <a href='admin.php?view=social&do=blogs'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The blog could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($_GET['do'] == "avatar" && !$_GET['go']) {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Type</b></td><td><b>Author</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-avatar' OR field_type = 'social-avatar-custom' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {

if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><a href='".$siteurl.$setting["upload_folder"]."avatar/".$r[data]."'>".stripslashes($r[field_name])."</a></td><td>";
if ($r[field_type] == "social-avatar-custom") {
echo "User Uploaded";
} else {
echo "Default";
}
echo "</td><td>".get_user($r[item_id])."</td><td>";
if ($p[2]) {
echo "<a href='admin.php?view=social&do=avatar&go=delete&id=".$r[id]."&file=".$r[data]."' onclick='return confirmDelete();'><img src='images/delete.png'></a>";
}
echo "</td></tr>";

$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S', 'N', 'S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'social-avatar'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=social&do=avatar&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=social&do=avatar&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=social&do=avatar&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "avatar" && $_GET['go'] == "add") {
$ext = explode(",", $setting["file_extensions"]);
while (list($k, $i) = each ($ext)) {
if ($k == 0) {
$accept .= $i;
} else {
$accept .= "|".$i;
}
}

echo "<form action='admin.php?view=social&do=avatar&go=add2' method='post' enctype='multipart/form-data'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center' id='form'>

<tr><td><p>Avatar</p></td><td><input type='file' name='file' size='16' class='select'></td></tr><tr><td><p>Name</p></td><td><input type='text' name='name' class='addtitle'></td></tr><tr><td><p>Re-Size</p></td><td><input type='title' name='resize1' size='2'> / <input type='text' name='resize2' size='2'></td></tr>

<tr><td><br /><input type='submit' value='Upload' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "avatar" && $_GET['go'] == "add2") {
if ($_FILES["file"]["name"]) {
copy ($_FILES["file"]["tmp_name"], $sitepath.$setting["upload_folder"]."avatar/".$_FILES["file"]["name"]);
$filename = $_FILES["file"]["name"];
}
if (!$_POST['name']) {
$ex = explode(".", $filename);
$name = $ex[0];
} else {
$name = addslashes($_POST['name']);
}

if ($filename) {
$query = mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$name."', 'social-avatar', '".$filename."', '".$useridn."')");
}
if ($_POST["resize1"] && $_POST["resize2"]) {
if (!extension_loaded('gd') && !extension_loaded('gd2'))
    {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }

list($width,$height,$image_type)=getimagesize($filedir.$filename);

    switch ($image_type) {
        case 1: $src = imagecreatefromgif($filedir.$filename); break;
        case 2: $src = imagecreatefromjpeg($filedir.$filename);  break;
        case 3: $src = imagecreatefrompng($filedir.$filename); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }

$max_width = $_POST["resize1"];
$max_height = $_POST["resize2"];

$x_ratio = $max_width / $width;
$y_ratio = $max_height / $height;

    $tn_width = $max_width;
    $tn_height = $max_height;

$tmp=imagecreatetruecolor($tn_width,$tn_height);

    if ($image_type == 1 or $image_type == 3) {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    switch ($image_type) {
        case 1: imagegif($tmp, $filedir.$filename); break;
        case 2: imagejpeg($tmp, $filedir.$filename, 100);  break; // best quality
        case 3: imagepng($tmp, $filedir.$filename, 0); break; // no compression
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }

imagedestroy($src);
imagedestroy($tmp);
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=social&do=avatar");
echo "The avatar have been uploaded. <a href='admin.php?view=social&do=avatar'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The avatar could not be uploaded. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}

}
}

if ($p[2]) {
if ($_GET['do'] == "avatar" && $_GET['go'] == "delete") {
if (@file_exists($sitepath.$setting["upload_folder"]."avatar/".$_GET['file'])) {
@unlink($sitepath.$setting["upload_folder"]."avatar/".$_GET['file']);
}
if (mysql_query("DELETE FROM ".$pre."data WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=social&do=avatar");
echo "The avatar has been deleted. <a href='admin.php?view=social&do=avatar'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The avatar could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>