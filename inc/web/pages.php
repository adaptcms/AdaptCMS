<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

if (is_numeric($_GET['id'])) {
$sql = mysql_query("SELECT * FROM ".$pre."pages WHERE id = '".$_GET['id']."'");
} else {
$sql = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `id` DESC");
}
while($r = mysql_fetch_array($sql)) {
$r[views]++;
mysql_query("UPDATE ".$pre."pages SET views=views+1 WHERE id = '".$r[id]."'");
$smarty->assign("name", stripslashes($r[name]));
$smarty->assign("content", stripslashes($r[content]));
$smarty->assign("username", get_user($r[user_id]));
$smarty->assign("views", number_format($r[views]));
$smarty->assign("date", timef($r[date]));

require_once($sitepath.'inc/recaptchalib.php');

$comments_num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'page_".$r[id]."'"));
$cid = 0;
//id,user_id,comment,rating,author,email,website,date
$comm = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'page_".$r[id]."'");
while($com = mysql_fetch_array($comm)) {
unset($crate, $cform);
$cmid = $com[id];

$ex1 = @explode("|", $com[rating]);
$com_rat = round($ex1[1]);

if ($com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 3 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 5) {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
}

if (!$useridn && strtolower($setting["ratings_guests_comment"]) == "yes" && !$_SESSION["rating_comments_$cmid"] or $useridn && !$_SESSION["rating_comments_$cmid"]) {
$cform = "<script>
function rate".$cmid."( value ) {
	new Ajax.Updater( 'rating_".$cmid."', '".$siteurl."inc/rating.php?id=".$cmid."&rat_num=".$ex1[0]."&rat_tot=".$ex1[1]."&type=comments&v='+value );
}
</script>

<div id='rating_".$com[id]."'>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(5)'></img>
</div>
<br/>";
} else {
$cform = "<i>Sorry, you cannot rate this item again</i>";
}

$comments_id[$cid] = $com[id];
$comments_userid[$cid] = get_user($com[user_id]);
if ($com[status]) {
$com[comment] = "<i>Comment has been flagged</i> <a href='#comment".$com[id]."' onclick='var myBox=document.getElementById(\"comment".$com[id]."\");myBox.style.display=(myBox.style.display==\"none\" ? \"block\": \"none\");return false;'>Show/Hide</a><div id='comment".$com[id]."' style='display:none'>".$com[comment]."</div>";
}
if ($useridn) {
if ($p[1] && !$com[status] or !$com[status] && $r[user_id] == $useridn) {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=page_".$r[id]."'>Lock Comment</a>";
} elseif ($p[1] or $r[user_id] == $useridn) {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=page_".$r[id]."&show=1'>Unlock Comment</a>";
} else {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl."?view=social&do=report&report_comment=".$com[id]."'>Report Comment</a>";
}
} else {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]));
}
$comments_rating[$cid] = $crate;
$comments_rating_form[$cid] = $cform;
$comments_email[$cid] = $com[email];
$comments_website[$cid] = "<a href='".$com[website]."'>".$com[website]."</a>";
$comments_date[$cid] = timef($com[date]);

$co[] = $cid;
$cid++;
}
$smarty->assign("comments", $co);
$smarty->assign("comments_id", $comments_id);
$smarty->assign("comments_username", $comments_userid);
$smarty->assign("comments_comment", $comments_comment);
$smarty->assign("comments_rating", $comments_rating);
$smarty->assign("comments_rating_form", $comments_rating_form);
$smarty->assign("comments_email", $comments_email);
$smarty->assign("comments_website", $comments_website);
$smarty->assign("comments_date", $comments_date);

if (!perm("comments", "add")) {
$smarty->assign("comments_form", "Sorry but you cannot post a comment, you do not have the necessary permissions to.");
} else {
$diff = time() - $_SESSION['last_comment_time'];
if ($_SESSION['last_comment_time'] && $diff < $settiong["comment_flood_limit"] or !$_SESSION['last_comment_time']) {
if (strtolower($setting["captcha_comments"]) == "yes") {
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' id='email' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br />".recaptcha_get_html($publickey)."<input type='hidden' name='page_id' value='".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

<script>
function addcomment()
{
  new Ajax.Updater('comments', '".$siteurl."inc/comments.php',
	{
		method: 'post',
		parameters: $('cform').serialize(),
		onSuccess: function() {
			$('comment').value = '';
		}
	} );
}
</script>");
} else {
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br /><input type='hidden' name='page_id' value='".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

<script>
function addcomment()
{
  new Ajax.Updater('comments', '".$siteurl."inc/comments.php',
	{
		method: 'post',
		parameters: $('cform').serialize(),
		onSuccess: function() {
			$('comment').value = '';
		}
	} );
}
</script>");
}

} else {
$smarty->assign("comments_form", "You have reached the comment flood limit of ".$_SESSION['last_comment_time']." seconds. Please wait ".$diff." more seconds to comment again");
}
}

$smarty->display($skin.'/page.tpl');
}
?>