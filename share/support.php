<?php
include("config.php");
$pageurl = "http://www.insanevisions.com/share/adaptcms2/";

$siteurl = $_GET['siteurl'];
$sitename = $_GET['sitename'];
$domain = $_GET['domain'];
$share_id = $_GET['share_id'];
$version = $_GET['version'];

$sitecheck = mysql_fetch_row(mysql_query("SELECT sitename,user_id,last_updated,date,secret_id,id FROM adaptcms2_websites WHERE siteurl = '".$siteurl."'"));
if (!$sitecheck[0]) {
mysql_query("INSERT INTO adaptcms2_websites VALUES (null, '".$sitename."', '".$siteurl."', 0, 0, '".time()."', '".time()."', '".random("18", $domain)."-".nrandom("10000000000")."-".arandom("5", $domain)."')");
}

echo "<link rel='stylesheet' href='http://www.insanevisions.com/share/adaptcms2/menu-bar.css' media='screen' type='text/css'>
	<script type='text/javascript' src='http://www.insanevisions.com/share/adaptcms2/menu.js'></script>
	
<div id='js_menu'>
	<ul>
		<li><a href='admin.php?view=support'>Main</a>	
		</li>
		<li><a href='admin.php?view=support'>All Tickets</a>
		</li>
		<li><a href='admin.php?view=support&go=my'>My Tickets</a>
		</li>
		<li><a href='admin.php?view=support&do=add'>Add Ticket</a>
		</li>
		<li><a href='admin.php?view=support&do=search'>Search</a>
		</li>
	</ul>
	</div><br />";

if (!$_GET['do']) {
if ($_GET['go'] && $_GET['recaptcha_challenge_field']) {
require_once('recaptchalib.php');
$privatekey = "6LfLXggAAAAAAN4K3KJi417ETsCg5pCwKU37OGj9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_GET["recaptcha_challenge_field"],
                                $_GET["recaptcha_response_field"]);
$invalid = 1;
}
if (!$resp->is_valid && $_GET['recaptcha_challenge_field']) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {

echo "<table cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td></td><td><b>Ticket</b></td><td><b>Replies</b></td><td><b>Last Update</b></td><td><b>Poster</b></td><td><b>Version</b></td></tr>";

if ($_GET['go'] == "my" && !$_GET['recaptcha_challenge_field']) {
$opt = " AND user_id = '".$sitecheck[1]."'";
} elseif ($_GET['go'] && $_GET['recaptcha_challenge_field']) {
$opt = " AND title LIKE '%".check($_GET['go'])."%' OR ticket_id = 0 AND content LIKE '%".check($_GET['go'])."%'";
}

$sql = mysql_query("SELECT * FROM adaptcms2_support WHERE ticket_id = 0".$opt." ORDER BY `date` DESC");
while($r = mysql_fetch_array($sql)) {
$user = mysql_fetch_row(mysql_query("SELECT username FROM cms_users WHERE id = '".$r[user_id]."'"));
$replies = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_support WHERE ticket_id = '".$r[id]."'"));

if (!$r[status]) {
echo "<tr><td><img src='".$pageurl."images/unlock.png'>";
} else {
echo "<tr style='background-color:red'><td><img src='".$pageurl."images/lock.png'>";
}

echo "</td><td><a href='admin.php?view=support&do=view&id=".$r[id]."'>".stripslashes($r[title])."</a></td><td>".$replies."</td><td>".date("F d, Y - g:i a", $r[date])."</td><td>".$user[0]."</td><td>".$r[version]."</td></tr>";
}
echo "</table>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM adaptcms2_support WHERE ticket_id = 0".$opt),0);
$total_pages = ceil($total_results / 20);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=support&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=support&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=support&page=$next\">Next>></a>";
}
echo "</center>";
}
}
}

if ($_GET['do'] == "search") {
require_once('recaptchalib.php');
$publickey = "6LfLXggAAAAAACD1RBKS6F3Es4nn3b3hiR_1qkd- "; // you got this from the signup page

echo "<form action='admin.php?view=support&do=search2' method='get'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><input type='hidden' name='view' value='support'><tr><td><p><span class='drop'>Search</span></p><input type='text' name='go' class='addtitle'></td></tr><tr><td><p>Captcha</p>".recaptcha_get_html($publickey)."</td></tr><tr><td><br /><input type='submit' value='Submit' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "view" && is_numeric($_GET['id'])) {
$sql = mysql_query("SELECT * FROM adaptcms2_support WHERE ticket_id = '".$_GET['id']."' OR id = '".$_GET['id']."' ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$user = mysql_fetch_row(mysql_query("SELECT username FROM cms_users WHERE id = '".$r[user_id]."'"));
if ($_GET['id'] == $r[id]) {
$status = $r[status];
}

echo "<table cellpadding='12' cellspacing='10' border='0' width='100%' align='center' style='border-bottom: 1px solid #262626'><tr><td><h2>".stripslashes($r[title])."</h2> By: <b>".$user[0]."</b> @ <i>".date("F d, Y", $r[date])."</i><br /></td></tr><tr><td>".str_replace("<p>","",stripslashes($r[content]));
if ($r[options] == 1 && !$status or !$r[options] && !$status) {
echo "&nbsp;&nbsp;<a href='admin.php?view=support&do=reply&id=".$_GET['id']."&go=".$r[id]."'><img src='".$pageurl."images/reply.png'></a>";
}
echo "<br /></td></tr></table>";
}

}

if ($_GET['do'] == "reply" && is_numeric($_GET['go']) && is_numeric($_GET['id'])) {
require_once('recaptchalib.php');
$publickey = "6LfLXggAAAAAACD1RBKS6F3Es4nn3b3hiR_1qkd- "; // you got this from the signup page

$r = mysql_fetch_row(mysql_query("SELECT content,title,version FROM adaptcms2_support WHERE id = '".$_GET['go']."'"));

echo "<script language='javascript' type='text/javascript' src='http://developer.adaptsoftware.org/AdaptCMS2/inc/js/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript'>
	tinyMCE.init({
		theme : 'advanced',
		mode : 'textareas',
		elements : 'abshosturls',
		plugins : 'spellchecker,preview,searchreplace,emotions,media,tinyautosave,contextmenu,codeprotect',

		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect',
		theme_advanced_buttons2 : 'bullist,numlist,|,undo,redo,|,link,unlink,image,cleanup,code,preview,replace,spellchecker,emotions,media,tinyautosave',
		theme_advanced_buttons3 : '',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		remove_linebreaks : false,
        force_p_newlines : false,
		debug : false,
		relative_urls : false,
		remove_script_host : false
	});</script>";

echo "<form action='admin.php?view=support&do=reply2&id=".$_GET['id']."' method='post'><input type='hidden' name='title' value=\"".$r[1]."\"><input type='hidden' name='version' value='".$r[2]."'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Your <span class='drop'>response</span></p><textarea name='content' cols='60' rows='15' class='textarea'></textarea></td></tr><tr><td><p>Captcha</p>".recaptcha_get_html($publickey)."</td></tr><tr><td><br /><input type='submit' value='Submit' class='addContent-button'></td></tr></table></form>";
}


if ($_GET['do'] == "add") {
require_once('recaptchalib.php');
$publickey = "6LfLXggAAAAAACD1RBKS6F3Es4nn3b3hiR_1qkd- "; // you got this from the signup page

echo "<script language='javascript' type='text/javascript' src='http://developer.adaptsoftware.org/AdaptCMS2/inc/js/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript'>
	tinyMCE.init({
		theme : 'advanced',
		mode : 'textareas',
		elements : 'abshosturls',
		plugins : 'spellchecker,preview,searchreplace,emotions,media,tinyautosave,contextmenu,codeprotect',

		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect',
		theme_advanced_buttons2 : 'bullist,numlist,|,undo,redo,|,link,unlink,image,cleanup,code,preview,replace,spellchecker,emotions,media,tinyautosave',
		theme_advanced_buttons3 : '',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		remove_linebreaks : false,
        force_p_newlines : false,
		debug : false,
		relative_urls : false,
		remove_script_host : false
	});</script>";

echo "<form action='admin.php?view=support&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Shortly describe <span class='drop'>issue</span></p><input type='text' name='title' size='12' class='addtitle'></td></tr><tr><td><p>Full <span class='drop'>details</span></p><textarea name='content' cols='60' rows='15' class='textarea'></textarea></td></tr><tr><td><p>Type of <span class='drop'>question</p><select name='type' class='select'><option value='Bug'>Bug</option><option value='Question'>Question</option><option value='Feature Request'>Feature Request</option><option value='Problem'>Problem</option><option value='Other'>Other</option></select></td></tr><tr><td><p>Response <span class='drop'>by?</span></p><select name='options' class='select'><option value='0'>Staff Only</option><option value='1'>Anyone</option></select></td></tr><tr><td><p>Captcha</p>".recaptcha_get_html($publickey)."</td></tr><tr><td><br /><input type='submit' value='Submit' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "reply2" && is_numeric($_GET['id'])) {
require_once('recaptchalib.php');
$privatekey = "6LfLXggAAAAAAN4K3KJi417ETsCg5pCwKU37OGj9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_GET["recaptcha_challenge_field"],
                                $_GET["recaptcha_response_field"]);

if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
if (mysql_query("INSERT INTO adaptcms2_support VALUES (null, 'Re: ".check($_GET['title'])."', '".preg_replace("/\n/", "<br />\n", check($_GET['content']))."', '".$sitecheck[1]."', '".time()."', '".$_GET['id']."', '".check($_GET['version'])."', '', '')") == TRUE) {
echo "Response submitted. <a href='admin.php?view=support'>Return</a>";
}
}
}

if ($_GET['do'] == "add2") {
require_once('recaptchalib.php');
$privatekey = "6LfLXggAAAAAAN4K3KJi417ETsCg5pCwKU37OGj9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_GET["recaptcha_challenge_field"],
                                $_GET["recaptcha_response_field"]);

if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
if (mysql_query("INSERT INTO adaptcms2_support VALUES (null, '[".check($_GET['type'])."] ".check($_GET['title'])."', '".preg_replace("/\n/", "<br />\n", check($_GET['content']))."', '".$sitecheck[1]."', '".time()."', 0, '".$version."', '', '".check($_GET['options'])."')") == TRUE) {
echo "Ticket added. <a href='admin.php?view=support'>Return</a>";
}
}
}

?>