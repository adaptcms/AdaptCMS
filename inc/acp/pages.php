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
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Views</b></td><td><b>Author</b></td><td><b>Last Modified</b></td><td></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
$cnum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'page_".$r[id]."'"));

echo "<td>".$r[id]."</td><td><a href='".url("page", $r[id], $r[name])."'>".stripslashes($r[name])."</a></td><td>".number_format($r[views])."</td><td>".get_user($r[user_id])."</td><td>".timef($r[date])."</td><td style='padding-right:15px'><img src='images/comments.png'> <a href='".url("page", $r[id], $r[name])."#comments'><b>".$cnum."</b></a>&nbsp;&nbsp;</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=pages&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=pages&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N','N','S','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."pages"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=pages&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=pages&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=pages&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add") {
echo "		<script src='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js' type='text/javascript'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js' type='text/javascript'></script>
		<script type='text/javascript' src='inc/js/fabtabulous.js'></script>
		<script type='text/javascript' src='inc/js/validation.js'></script>

		<script language='javascript' type='text/javascript' src='inc/js/edit_area/edit_area_full.js'></script>
<script language='Javascript' type='text/javascript'>
		editAreaLoader.init({
			id: 'content'	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: 'both'
			,allow_toggle: true
			,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
			,syntax_selection_allow: 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas'
			,word_wrap: true
			,language: 'en'
			,syntax: 'html'	
		});
</script>";
echo "<form action='admin.php?view=pages&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='12' class='required addtitle'></td></tr><tr><td><p>Page <span class='drop'>Content</span></p><textarea name='content' id='content' style='height: 300px; width: 100%' class='required textarea'></textarea></td></tr><tr><td><input type='submit' value='Add Page' class='addContent-button'></td></tr></table></form>

<script type='text/javascript'>
						function formCallback(result, form) {
							window.status = \"valiation callback for form '\" + form.id + \"': result = \" + result;
						}
						
						var valid = new Validation('form', {immediate : true, useTitles:true, onFormValidate : formCallback});
					</script>";
}

if ($_GET['do'] == "add2") {
$content = addslashes($_POST['content']);

if (mysql_query("INSERT INTO ".$pre."pages VALUES (null, '".addslashes($_POST["name"])."', '".$content."', '".$useridn."', '".time()."', 0)") == TRUE) {

echo re_direct("1500", "admin.php?view=pages");
echo "The page <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=pages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The page could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,content FROM ".$pre."pages WHERE id = '".$_GET['id']."'"));
echo "<script language='javascript' type='text/javascript' src='inc/js/edit_area/edit_area_full.js'></script>
<script language='Javascript' type='text/javascript'>
		editAreaLoader.init({
			id: 'content'	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: 'both'
			,allow_toggle: true
			,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
			,syntax_selection_allow: 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas'
			,word_wrap: true
			,language: 'en'
			,syntax: 'html'	
		});
</script>";
echo "<form action='admin.php?view=pages&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' value=\"".stripslashes($r[0])."\" size='12' class='addtitle'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr><tr><td><p>Page <span class='drop'>Content</span></p><textarea id='content' name='content' style='height: 300px; width: 100%' class='textarea'>".str_replace("</textarea>", "&lt;/textarea&gt;", stripslashes($r[1]))."</textarea></td></tr><tr><td><input type='submit' value='Update Page' class='addContent-button'></td></tr></table></form>";

if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'page_".$_GET['id']."'")) > 0 && $p[1]) {
echo "<center><h2>Related Comments</h2></center><form action='admin.php?view=pages&do=comments' method='post'><table id='mytable' cellpadding='3' cellspacing='1' border='0' width='100%' align='center' style='border-collapse: collapse'><input type='hidden' name='user_id' value='".$r[2]."'><tr style='border-bottom: 1px solid #262626'><td><b>Username</b></td><td><b>Comment</b></td><td><b>Website</b></td><td><b>Delete?</b></td></tr>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'page_".$_GET['id']."' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<input type='hidden' name='comments[]' value='".$r[id]."'><td>".get_user($r[user_id])."</td><td><textarea name='comment_".$r[id]."' cols='30' rows='10' class='input'>".stripslashes($r[comment])."</textarea></td>";
if (1 == 2) {
if ($r[user_id]) {
echo $r[email]."<input type='hidden' name='email_".$r[id]."' value='".$r[email]."'>";
} else {
echo "<input type='text' name='email_".$r[id]."' value='".$r[email]."' class='input'>";
}
}
echo "<td><input type='text' name='website_".$r[id]."' value='".$r[website]."' class='input'></td><td>".timef($r[date])."</td><td><input type='checkbox' name='delete_".$r[id]."' value='yes' class='input'></td></tr>";
$i = $i + 1;
}
echo "<tr><td><input type='submit' value='Update' class='addContent-button'></table></form><br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."pages"),0);
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
}

if ($_GET['do'] == "edit2") {
$content = addslashes($_POST['content']);

if (mysql_query("UPDATE ".$pre."pages SET name = '".addslashes($_POST["name"])."', content = '".$content."', date = '".time()."' WHERE id = '".$_GET['id']."'") == TRUE) {
echo re_direct("1500", "admin.php?view=pages");
echo "The page <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=pages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The page could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($_GET['do'] == "comments") {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = 'comments'"));
$p = explode("|", $ps[0]);
if ($_POST['user_id'] == $useridn or $p[1]) {

while (list(, $i) = each ($_POST['comments'])) {
if ($_POST["delete_$i"]) {
mysql_query("DELETE FROM ".$pre."comments WHERE id = '".$i."'");
$myql = 1;
} else {
mysql_query("UPDATE ".$pre."comments SET comment = '".$_POST["comment_$i"]."', email = '".$_POST["email_$i"]."', website = '".$_POST["website_$i"]."' WHERE id = '".$i."'");
$myql = 1;
}
}
}

if ($myql == 1) {
echo re_direct("1500", "admin.php?view=content");
echo "The comments have been updated/deleted. <a href='admin.php?view=pages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The comments could not be updated/deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."pages WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=pages");
echo "The page has been deleted. <a href='admin.php?view=pages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The page could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>