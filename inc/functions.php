<?php
$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler", "Yeti", "google", "bing", "Baiduspider");

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 {php}echo get_gravatar($email);{/php} */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = true, $atts = array() ) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

function stats($type) {
global $pre;

if ($type == "users") {
echo mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users"));
}

}

/**
 * Converts a MySQL date to a human-readable date
 *
 * @param string $date The MySQL date
 * @param string $granularity
 * @return string The formatted string
 */
function timef($date, $granularity = 2)
{
global $setting;

if ($setting["date_type"] == "new") {

    $difference = time() - $date;
    $periods = array(
        'decade' => 315360000,
        'year'   => 31536000,
        'month'  => 2628000,
        'week'   => 604800, 
        'day'    => 86400,
        'hour'   => 3600,
        'minute' => 60,
        'second' => 1);
    
    $retval = '';
    if ($difference < 1)
    {
        $retval = "less than 1 second";
    }
    else
    {
        foreach ($periods as $key => $value)
        {
            if ($difference >= $value)
            {
                $time = floor($difference/$value);
                $difference %= $value;
                $retval .= ($retval ? ' ' : '').$time.' ';
                $retval .= (($time > 1) ? $key.'s' : $key);
                $granularity--;
            }

            if ($granularity == '0')
            {
                break;
            }
        }
    }
    return $retval.' ago';      
} else {
return date($setting["date_format"], $date);
}
}

function generateSalt() {
return substr(md5(uniqid(rand(), true)), 0, 12);
}

function insertnewSalt() {
$new_salt = generateSalt();
$hi = file(getcwd()."/inc/dbinfo.php");
foreach ($hi as $line_num => $line) {
$code .= stripslashes($line);
}

$pab[0] = "'salt'";
$rab[0] = "'".$new_salt."'";

$code2 = str_replace($pab, $rab, $code);

$fp = fopen(getcwd()."/inc/dbinfo.php", "w");
fwrite($fp,stripslashes($code2));
fclose($fp);
}

function users_online($mins, $url = NULL) {
global $pre;
global $botlist;

if (!$mins or $mins == 0) {
$mins = 15;
}
$min = $mins * 60 + 1;
$time1 = time() - $min;
$time2 = time() + 1;
$timev = 0;
if ($url) {
$urls = " AND page = '".$url."'";
}
$sqlv = mysql_query("SELECT * FROM ".$pre."stats WHERE time_last_visit < ".$time2." AND time_last_visit > ".$time1." AND user_id != ''".$urls);
while($r = mysql_fetch_array($sqlv)) {
unset($bot_count);
if (strpos($datav, $r[ip]) === FALSE) {
$timev = $timev + 1;

$fet = mysql_fetch_row(mysql_query("SELECT username FROM ".$pre."users WHERE id = '".$r[user_id]."'"));
if ($fet[0]) {
if ($timev == 1) {
$data .= get_user($r[user_id]);
} else {
$data .= ", ".get_user($r[user_id]);
}
} else {
foreach($botlist as $bot) {
if (preg_match("/".$r[referer_name]."/", $bot)) {
$bot_count = "1";
}
}

if ($bot_count) {
if ($timev == 1) {
$data .= "<b>".stripslashes($r[referer_name])."</b>";
} else {
$data .= ", <b>".stripslashes($r[referer_name])."</b>";
}
}
}

}
$datav .= $r[ip];
}
return $data;
}

function online($type, $mins, $url = NULL) {
global $pre;
if (!$mins or $mins == 0) {
$mins = 15;
}
if ($type == "all" or $type == "guests") {
$min = $mins * 60 + 1;
$time1 = time() - $min;
$time2 = time() + 1;
$timev = 0;
if ($url) {
$urls = " AND page = '".$url."'";
}
$sqlv = mysql_query("SELECT * FROM ".$pre."stats WHERE time_last_visit < ".$time2." AND time_last_visit > ".$time1." AND user_id != ''".$urls);
while($r = mysql_fetch_array($sqlv)) {
if (strpos($datav, $r[ip]) === FALSE) {
$timev = $timev + 1;
}
$datav .= $r[ip];
}
return $timev;
} else {
$min = $mins * 60 + 1;
$time1 = time() - $min;
$time2 = time() + 1;
$timev = 0;
$sqlv = mysql_query("SELECT * FROM ".$pre."stats WHERE time_last_visit < ".$time2." AND time_last_visit > ".$time1." AND user_id != ''".$urls);
while($r = mysql_fetch_array($sqlv)) {
if (strpos($datav, $r[ip]) === FALSE) {
$timev = $timev + 1;
}
$datav .= $r[ip];
}
return $timev;
}
}

function paginate($query, $page, $limit = NULL) {
global $pre;
global $siteurl;
global $setting;

if (!$_GET['page']) {
$_GET['page'] = 1;
}

if (!$limit) {
$limit = $setting["admin_limit"];
}
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$query),0);
$total_pages = ceil($total_results / $limit);

if ($total_pages > "1") {
echo "<center>";

if($_GET['page'] > 1){
    $prev = ($_GET['page'] - 1);
    echo "<a href=\"".$page."page=$prev\"><< Prev</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($_GET['page']) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$page."page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($_GET['page'] < $total_pages){
    $next = ($_GET['page'] + 1);
    echo "<a href=\"".$page."page=$next\">Next >></a>";
}
echo "</center>";
}
}

function help($name, $type) {
global $pre;
global $siteurl;

if ($name) {
$url = $name;
} elseif ($_GET['view'] != "help" && $_GET['view']) {
$url = strtolower($_GET['view'])."&do2=".$_GET['do'];
if (!$_GET['do']) {
$a2 = ucfirst($_GET['view']);
} else {
$a1 = ucfirst($_GET['view']);
if ($_GET['do'] == "edit") {
$a2 = "Manage ".ucfirst($_GET['view']);
} else {
$a2 = ucfirst($_GET['do']);
}
}
$look = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."data WHERE field_type = 'help-file' AND item_id = '".$a1."' AND field_name = '".$a2."'"));
} elseif (!$_GET['view']) {
$url = "default";
}

echo "<link type='text/css' media='screen' rel='stylesheet' href='".$siteurl."inc/js/colorbox.css' />
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
		<script type='text/javascript' src='".$siteurl."inc/js/jquery.colorbox-min.js'></script>
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$(\".help\").colorbox({width:\"40%\", height:\"50%\", iframe:true});
			});
		</script>
		
		
		<a class='help' href='".$siteurl."inc/acp/help.php";
		if ($look[0]) {
		echo "?do=file&id=".$look[0];
		} else {
		echo "?do=".$url;
		}
		echo "'>";
		if ($type == "q") {
		echo "<img src='".$siteurl."inc/images/help.png'></a>";
		} elseif ($type == "!") {
		echo "<img src='".$siteurl."inc/images/attn.png'></a>";
		} elseif (!$type) {
		echo "Help</a>";
		}
}

function admin_bar() {
global $pre;
global $siteurl;
global $useridn;
global $group;
global $smarty;
global $pageurl;
global $admin_info;
global $page_type;
global $setting;

//if ($admin_info[1]) {
//if ($page_type == "acp" or 1 == 1) {
$contents .= "<table width='100%' cellpadding='5' cellspacing='3'><tr>";

if (perm("content",1)) {
$contents .= "<td valign='top'><table cellspacing='4' cellpadding='2'><tr><td><b>Latest Content</b> <a href='".$siteurl."admin.php?view=content'><img src='".$siteurl."images/content.png'></a></td></tr>";

$sql = mysql_query("SELECT * FROM ".$pre."content ORDER BY `id` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[section]."'"));
$p = explode("|", $ps[0]);

$contents .= "<tr><td><a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a></td>";
if ($p[1] or $p[0] && $r[user_id] == $useridn) {
$contents .= "<td><a href='".$siteurl."admin.php?view=content&do=edit&id=".$r[id]."'><img src='".$siteurl."images/edit.png' title='Edit'></a></td>";
}
if ($p[2] or $p[0] && $r[user_id] == $useridn) {
$contents .= "<td><a href='".$siteurl."admin.php?view=content&do=delete&id=".$r[id]."' onclick=\"return confirm('Are you sure you wish to delete this content item?');\"><img src='".$siteurl."images/delete.png'></a></td>";
}
$contents .= "<td>".date("m/d/y, h:i a", $r[date])."</td></tr>";
}
$contents .= "</table></td>";
$adm_con = 1;
}

if (perm("comments",1)) {
$contents .= "<td valign='top'><table cellspacing='3' cellpadding='2'><tr><td><b>Latest Comments</b> <a href='".$siteurl."admin.php?view=comments'><img src='".$siteurl."images/comments.png'></a></td></tr>";

$sql = mysql_query("SELECT * FROM ".$pre."comments ORDER BY `id` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
$user = mysql_fetch_row(mysql_query("SELECT username FROM ".$pre."users WHERE id = '".$r[user_id]."'"));
if (!$user[0]) {
$user[0] = "Guest";
}
$art = mysql_fetch_row(mysql_query("SELECT name,section FROM ".$pre."content WHERE id = '".$r[article_id]."'"));
$contents .= "<tr><td><a href='".url("content", $r[article_id], $art[0], $art[1])."#comment".$r[id]."'>".stripslashes($user[0])."</a></td><td><a href='".$siteurl."admin.php?view=content&do=edit&id=".$r[id]."'><img src='".$siteurl."images/edit.png' title='Edit'></a></td><td>".date("m/d/y, h:i a", $r[date])."</td></tr>";
}
$contents .= "</table></td>";
}

if (perm("users",1)) {
$contents .= "<td valign='top'><table cellspacing='3' cellpadding='2'><tr><td><b>Latest Users</b> <a href='".$siteurl."admin.php?view=users'><img src='".$siteurl."images/users.png'></a></td></tr>";

$sql = mysql_query("SELECT * FROM ".$pre."users ORDER BY `id` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
$contents .= "<tr><td>".get_user($r[id])."</td>";

if (strtolower($setting["register_verify"]) == "yes" && $r[ver] == "no") {
$contents .= "<td><a href='".$siteurl."admin.php?view=users&do=verify&id=".$r[id]."' onclick=\"return confirm('Are you sure you wish to verify this user?');\"><img src='".$siteurl."images/attn.png' title='Verify'></a></td>";
}
if (strtolower($setting["register_activate"]) == "yes" && $r[act] == "no") {
$contents .= "<td><a href='".$siteurl."admin.php?view=users&do=activate&id=".$r[id]."' onclick=\"return confirm('Are you sure you wish to activate this user?');\"><img src='".$siteurl."images/attn.png' title='Activate'></a></td>";
}
$contents .= "<td><a href='".$siteurl."admin.php?view=users&do=edit&id=".$r[id]."'><img src='".$siteurl."images/edit.png' title='Edit'></a></td><td><a href='".$siteurl."admin.php?view=users&do=delete&id=".$r[id]."'><img src='".$siteurl."images/delete.png' title='Delete' onclick=\"return confirm('Are you sure you wish to delete this user?');\"></a></td><td>".date("m/d/y, h:i a", $r[reg_date])."</td></tr>";
}
$contents .= "</table></td>";
$adm_usr = 1;
}

$contents .= "<td valign='top'>

<table cellspacing='3' cellpadding='2'><tr><td><b>Notices</b> <img src='".$siteurl."images/attn.png'></td></tr>";
$msg_new = messages("new");
if ($msg_new > 0) {
$contents .= "<tr><td><a href='".$siteurl."messages'>".$msg_new." new messages!</a></td></tr>";
}
if ($adm_con) {
$cont = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."content WHERE status != ''"));
if ($cont > 0) {
$contents .= "<tr><td><a href='".$siteurl."admin.php?view=content&status=1'>".$cont." non-published content items</a></td></tr>";
}
}
if ($adm_usr) {
$unver = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE ver != 'yes'"));
if ($unver > 0) {
$contents .= "<tr><td><a href='".$siteurl."admin.php?view=users&ver=1'>".$unver." non-verified users</a></td></tr>";
}
}
$contents .= "</table></td>";
if ($adm_con or $adm_usr) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(visits_num) FROM ".$pre."stats WHERE tday = '".date("z")."' AND year = '".date("Y")."' AND visit_type = 'view'"));
$uniques = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE tday = '".date("z")."' AND year = '".date("Y")."' AND visit_type = 'unique'"));
$contents .= "<td valign='top'>

<table cellspacing='3' cellpadding='2'><tr><td><b>Today's Stats</b> <a href='".$siteurl."admin.php?view=stats'><img src='".$siteurl."images/stats.png'></a></td></tr>

<tr><td>Views - ".$views[0]."</td></tr><tr><td>Uniques - ".$uniques."</td></tr></table>

</td>";
}
$contents .= "</tr></table>";
//}
$smarty->assign("acp_bar", "<link rel='stylesheet' type='text/css' href='".$siteurl."inc/js/dddropdownpanel.css' />

<script type='text/javascript' src='".$siteurl."inc/js/dddropdownpanel.js'>/***********************************************
* DD Drop Down Panel- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/</script>

<div id='mypanel' class='ddpanel'>
<div id='mypanelcontent' class='ddpanelcontent'><p style='padding:10px'>

".$contents."

</p>
<br style='clear: left' />
<img src='{$siteurl}images/cancel.png' class='closepanel'> <b class='closepanel'>Close</b>
</div>
<div id='mypaneltab' class='ddpaneltab'>
<a href='#'><span>Toggle</span></a>
</div>

</div>");

//} else {
//$smarty->assign("acp_bar", "");
//}
}

function valid_email($email) {
  $result = TRUE;
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
    $result = FALSE;
  }
  return $result;
}

function perm($name, $type) {
global $pre;
global $group;
global $useridn;

if ($group && is_numeric($useridn)) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$name."'"));
$p = explode("|", $ps[0]);

if ($type == 0 or $type == "add") {
return $p[0];
} elseif ($type == 1 or $type == "edit") {
return $p[1];
} elseif ($type == 2 or $type == "delete") {
return $p[2];
} elseif ($type == 3 or $type == "verify") {
return $p[3];
}
}
}

function badwords($str) { // http://www.programmingtalk.com/showthread.php?t=34225
global $setting;

	$badwords = explode(",", $setting['word_filter']);
    $replacements = "*";
    
    foreach ($badwords AS $badword)
    {
          $str = eregi_replace($badword, str_repeat('*', strlen($badword)), $str);
    }  
    
    return $str;
}  

function poll($id = NULL, $limit = 10) {
global $pre;
global $setting;
global $cookiename;
global $siteurl;
global $smarty;
global $skin;

if ($id) {
$poll_sql = mysql_query("SELECT * FROM ".$pre."polls WHERE poll_id = '".$id."' AND type = 'poll'");
} else {
$poll_sql = mysql_query("SELECT * FROM ".$pre."polls WHERE type = 'poll' ORDER BY `id` DESC LIMIT ".$limit);
}
while($poll = mysql_fetch_array($poll_sql)) {
unset($options, $options_data);

if ($poll[name]) {
$poll_id = $poll[poll_id];
$poll_name = $poll[name];
if ($_GET['poll_id']) {
$total = $poll[votes] + 1;
} else {
$total = $poll[votes];
}
$num = $total;

$ex = explode(",", $poll[options]);
$multi = $ex[0];
$custom = $ex[1];

if ($_SESSION["poll_".$poll_id] == "" && $_GET['results'] == "") {
if ($multi) {
$other_stuff .= "<input type='hidden' name='multi' value='yes'>";
}
if ($custom) {
$other_stuff .= "<input type='hidden' name='custom' value='yes'>";
}
$smarty->assign("poll_header", "<form action='".$_SERVER['REQUEST_URI']."' method='post' name='form1'><input type='hidden' name='article_id' value='".$poll[article_id]."'><input type='hidden' name='poll_id' value='".$id."'>".$other_stuff);
}

$sql = mysql_query("SELECT * FROM ".$pre."polls WHERE poll_id = '".$poll_id."' AND type = 'option' OR poll_id = '".$poll_id."' AND type = 'custom_option' ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($_GET['vote']) {
if ($r[id] == $_GET['vote']) {
$r[votes] = $r[votes] + 1;
}
}

if ($num == "0") {
$num = "1";
}
@$nu = @$r[votes]/$num;

if (($num == $r[votes]) && ($r[votes] > "0")) {
$nua = "100";
} else {
if ($r[votes] == "0") {
$nua = "0";
} else {
$nua = round($nu);
}
}

if (($nu < "10") && ($nu > "0")) {
$nua = $nu * 100;
}

if (($num == $r[votes]) && ($r[votes] == "0")) {
$nua = "0";
}

$options[] = stripslashes($r[name]);

if ($setting["poll_type"] == "graphic" or !$setting["poll_type"]) {
if ($nua > "0") {
$img = "<img src='".$siteurl."images/poll.jpg' width='".substr($nua,0,4)."%' height='10'>";
} else {
$img = "<img src='".$siteurl."images/poll.jpg' width='1%' height='10'>";
}
} else {
$img = substr($nua,0,4)."%";
}

if ($_SESSION["poll_".$poll_id] == "" && $_GET['results'] == "") {
if ($multi) {
$options_data[] = "<input type='checkbox' name='vote[]' value='".$r[id]."'>";
} else {
$options_data[] = "<input type='radio' onclick='getVote(this.value)' name='vote' value='".$r[id]."'>";
}
} else {
$options_data[] = substr($nua,0,4)."%<br>".$img;
}
}

$smarty->assign("options", $options);
$smarty->assign("options_data", $options_data);

if ($_SESSION["poll_".$poll_id] == "" && $_GET['results'] == "") {
if ($custom) {
if ($_COOKIE[$cookiename."username"] or strtolower($setting["custom_poll_guest"]) == "yes") {
$cunum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."polls WHERE name = '".$poll_name."' AND type = 'custom_option'"));
@$diff = $setting["custom_poll_limit"] - $cunum;
if ($diff > 0) {
$options[] = "<input type='text' name='custom_vote' size='14' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'>";
}
}
}

$temp1 = template("poll_vote");
} else {
$temp1 = template("poll_results");
}
$sab[] = "{question}";
$sab[] = "{options}";
$sab[] = "{submit}";
$sab[] = "{vote_total}";

$tab[] = stripslashes($poll_name);
$tab[] = $poll_options;
if ($_SESSION["poll_".$poll_id] == "" && $_GET['results'] == "") {
$tab[] = "<input type='submit' value='Vote' class='input'>";
} else {
$tab[] = "";
}
$tab[] = $total;

$smarty->assign("question", stripslashes($poll_name));
$smarty->assign("submit", "<input type='submit' value='Vote' class='input'>");
$smarty->assign("vote_total", $total);

if ($_SESSION["poll_".$poll_id] == "" && $_GET['results'] == "") {
$smarty->display($skin.'/poll_vote.tpl', "poll-".$poll_id);
} else {
$smarty->display($skin.'/poll_results.tpl', "poll-".$poll_id);
}
}
}

}

function wysiwyg() {
global $siteurl;
return "<script type='text/javascript' src='".$siteurl."inc/js/tiny_mce/tiny_mce.js'></script>
<script type='text/javascript'>
	tinyMCE.init({
		// General options
		mode : 'textareas',
		theme : 'advanced',
		elements : 'abshosturls',
		plugins : 'spellchecker,preview,searchreplace,emotions,media,contextmenu,wordcount,autosave,pagebreak,tinyautosave',

		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect',
		theme_advanced_buttons2 : 'bullist,numlist,|,undo,redo,|,link,unlink,image,cleanup,code,preview,replace,spellchecker,emotions,media,pagebreak,autosave,tinyautosave',
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
	});
</script>";
}

function parse_text($str) {
    $str = @htmlentities($str);

    $simple_search = array(
                '/\[b\](.*?)\[\/b\]/is',                                
                '/\[i\](.*?)\[\/i\]/is',                                
                '/\[u\](.*?)\[\/u\]/is',                                
                '/\[url\=(.*?)\](.*?)\[\/url\]/is',                         
                '/\[url\](.*?)\[\/url\]/is',                             
                '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',    
                '/\[img\](.*?)\[\/img\]/is',                            
                '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',                    
                '/\[mail\](.*?)\[\/mail\]/is',                            
                '/\[font\=(.*?)\](.*?)\[\/font\]/is',                    
                '/\[size\=(.*?)\](.*?)\[\/size\]/is',                    
                '/\[color\=(.*?)\](.*?)\[\/color\]/is',        
                );

    $simple_replace = array(
                '<strong>$1</strong>',
                '<em>$1</em>',
                '<u>$1</u>',
                '<a href="$1">$2</a>',
                '<a href="$1">$1</a>',
                '<div style="text-align: $1;">$2</div>',
                '<img src="$1" />',
                '<a href="mailto:$1">$2</a>',
                '<a href="mailto:$1">$1</a>',
                '<span style="font-family: $1;">$2</span>',
                '<span style="font-size: $1;">$2</span>',
                '<span style="color: $1;">$2</span>',
                );

    // Do simple BBCode's
    $str = preg_replace ($simple_search, $simple_replace, $str);
    // Do <blockquote> BBCode
    $str = text_quote($str);
 
    return stripslashes(stripslashes(html_entity_decode($str)));
}

function text_quote ($str) {
    $open = '<blockquote>';
    $close = '</blockquote>';

    // How often is the open tag?
    preg_match_all('/\[quote\]/i', $str, $matches);
    $opentags = count($matches['0']);

    // How often is the close tag?
    preg_match_all ('/\[\/quote\]/i', $str, $matches);
    $closetags = count($matches['0']);

    // Check how many tags have been unclosed
    // And add the unclosing tag at the end of the message
    $unclosed = $opentags - $closetags;
    for ($i = 0; $i < $unclosed; $i++) {
        $str .= '</blockquote>';
    }

    // Do replacement
    $str = str_replace('[' . 'quote]', $open, $str);
    $str = str_replace('[/' . 'quote]', $close, $str);

    return $str;
}

function soc_bookmark($link, $title, $topic, $des, $icon = 'Y') {
	global $siteurl;
    $link = rawurlencode($link);
    $title = rawurlencode($title);
    $icon_folder = $siteurl."images/social_icons/";
    $bookmark = array(
        #'BlinkBits'=>'http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url='.$link.'&amp;title='.$title,
        ##'Del.icio.us'=>'http://del.icio.us/post?v=2&amp;url='.$link.'&amp;title='.$title,
        'Digg'=>'http://digg.com/submit?phase=2&amp;url='.$link.'&amp;title='.$title.'&amp;topic='.@htmlentities($topic).'&amp;bodytext='.@htmlentities($des),
		'Facebook'=>'http://www.facebook.com/sharer.php?u='.$link.'&amp;t='.$title,
        ##'Fark'=>'http://cgi.fark.com/cgi/fark/submit.pl?new_url='.$link.'&amp;title='.$title,
        'Furl'=>'http://www.furl.net/savedialog.jsp?p=1&t='.$title.'&amp;v=1&amp;u='.$link,
        ##'Google'=>'http://www.google.com/bookmarks/mark?op=add&amp;bkmk='.$link.'&amp;title='.$title,
        ##'Ma.gnolia'=>'http://ma.gnolia.com/bookmarklet/add?url='.$link.'&amp;title='.$title,
        #'MyWeb'=>'http://myweb2.search.yahoo.com/myresults/bookmarklet?t='.$title.'&amp;u='.$link,
        'Netscape'=>'http://www.netscape.com/submit/?U='.$link.'&amp;T='.$title,
        ##'NetVouz'=>'http://netvouz.com/action/submitBookmark?url='.$link.'&amp;title='.$title.'&amp;description='.htmlentities($des).'&amp;popup=no',
        'Newsvine'=>'http://www.newsvine.com/_tools/seed&save?u='.$link.'&amp;h='.$title,
        'Reddit'=>'http://reddit.com/submit?url='.$link.'&amp;title='.$title,
        #'Shadows'=>'http://www.shadows.com/features/tcr.htm?url='.$link.'&amp;title='.$title,
        'Simpy'=>'http://simpy.com/simpy/LinkAdd.do?title='.$title.'&amp;href='.$link,
        ##'Slashdot'=>'http://slashdot.org/bookmark.pl?url='.$link.'&amp;title='.$title,
        'Spurl'=>'http://www.spurl.net/spurl.php?v=3&amp;url='.$link.'&amp;title='.$title,
		'StumbleUpon'=>'http://www.stumbleupon.com/submit?url='.$link.'&amp;title='.$title,
        #'Technorati'=>'http://technorati.com/faves?add='.$link.'&amp;title='.$title,
    );
    foreach($bookmark as $key=>$value) {
        $link_text = $icon == 'Y' ? '<img src="'.$icon_folder.str_replace(".", '', strtolower($key)).'.jpg" alt="Post to '.$key.'" />' : $key;
        $a .= '<a href="'.$value.'" title="Post to '.$key.'">'.$link_text.'</a> ';
    }
	return $a;
}

function file_type($file, $show_type) {
global $siteurl;

$video_list = array("3gp","avi","dvx","flv","mkv","mp4","mpg","mpeg","mov","rm","swf","tivo","vob","wmv","wtv");
$image_list = array("3dm","ai","bmp","jpg","png","gif","drw","dwg","dxf","eps","indd","jpeg","pct","pln","ps","psd","psp","qxd","qxp","rels","svg","thm","tif");
$music_list = array("mp3","aac","aif","iff","m3u","mid","midi","mpa","ra","wav","wma");
$other_list = array("html", "zip", "rar", "file", "txt", "word", "tor", "pdf", "php");


foreach($image_list as $r) {
if(ereg($r, $file)) {
$found = 1;
$file_type = "image";
if ($show_type == "image") {
if ($r == "bmp") {
$img = $siteurl."images/file_types/bmp.png";
} elseif ($r == "jpg" or $r == "jpeg") {
$img = $siteurl."images/file_types/jpg.png";
} elseif ($r == "png") {
$img = $siteurl."images/file_types/png.png";
} elseif ($r == "psd") {
$img = $siteurl."images/file_types/psd.png";
} else {
$img = $siteurl."images/file_types/photo.png";
}
}
}
}

if (!$found) {
foreach($video_list as $r) {
if(ereg($r, $file)) {
$found = 1;
$file_type = "video";
if ($show_type == "image") {
if ($r == "dvx") {
$img = $siteurl."images/file_types/divx.png";
} elseif ($r == "flv") {
$img = $siteurl."images/file_types/flv.png";
} elseif ($r == "mov") {
$img = $siteurl."images/file_types/mov.png";
} elseif ($r == "wav") {
$img = $siteurl."images/file_types/wav.png";
} else {
$img = $siteurl."images/file_types/movie.png";
}
}
}
}
}

if (!$found) {
foreach($music_list as $r) {
if(ereg($r, $file)) {
$found = 1;
$file_type = "music";
if ($show_type == "image") {
if ($r == "mp3") {
$img = $siteurl."images/file_types/mp3.png";
} else {
$img = $siteurl."images/file_types/music.png";
}
}
}
}
}

if (!$found) {
foreach($other_list as $r) {
if(ereg($r, $file)) {
$found = 1;
$file_type = "other";
if ($show_type == "image") {
if ($r == "html" or $r == "php") {
$img = $siteurl."images/file_types/html.png";
} elseif ($r == "zip" or $r == "rar") {
$img = $siteurl."images/file_types/zip.png";
} elseif ($r == "word") {
$img = $siteurl."images/file_types/word.png";
} elseif ($r == "torrent") {
$img = $siteurl."images/file_types/tor.png";
} elseif ($r == "pdf") {
$img = $siteurl."images/file_types/pdf.png";
} elseif ($r == "txt") {
$img = $siteurl."images/file_types/txt.png";
} else {
$img = $siteurl."images/file_types/file.png";
}
}
}
}
}

if ($img && $show_type == "image") {
return $img;
} else {
return $file_type;
}
}

function media($type, $template, $limit, $id = NULL, $sort = NULL, $sortby = NULL, $name = NULL, $pag = NULL) {
global $pre;
global $lev;
global $group;
global $setting;
global $siteurl;
global $skin;
global $sitepath;
global $smarty;
global $pageurl;
global $useridn;

if (!$limit) {
$limit = "12";
}
if (!$sort) {
$sort = "`id`";
} else {
if ($sort == "RAND()") {
$sort = "RAND()";
} else {
$sort = "`".$sort."`";
}
}
if (!$sortby) {
$sortby = "DESC";
}
if ($pag) {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $limit) - $limit);
$froms = $from.", ";
}


if ($type == "file_view") {
if ($id) {
if (is_numeric($id)) {
$idvar = " WHERE id = '".$id."'";
} else {
$ex = explode(",", $id);
if (count($ex) == 1) {
$idvar = " WHERE id = '".$ex[0]."'";
} else {
$idvar .= " WHERE";
while (list($k,$i) = @each($ex)) {
if ($k != 0) {
$idvar .= " OR";
}
$idvar .= " id = '".$i."'";
}
}
}
}
$sql = @mysql_query("SELECT * FROM ".$pre."files".$idvar);
while($r = @mysql_fetch_array($sql)) {
$id = $r[id];

$sqls = mysql_query("SELECT * FROM ".$pre."media WHERE id = '".$r[media_id]."'");
while($row = mysql_fetch_array($sqls)) {
$row[name] = stripslashes($row[name]);

$file = mysql_fetch_row(mysql_query("SELECT filedir,filename FROM ".$pre."files WHERE media_id = '".$row[id]."' ORDER BY `id` DESC LIMIT 1"));
if (!$file[0]) {
$media_image = "<img src='".$siteurl."images/nopreview.jpg' class='input'>";
} else {
if ($file[0] == $setting["upload_folder"]) {
$info = pathinfo($siteurl.$file[0].$file[1]);
} else {
$info = pathinfo($file[0].$file[1]);
}
if (file_type($info["extension"], "") == "image") {
$media_image = "<img src='";
if ($file[0] == $setting["upload_folder"]) {
$media_image .= $siteurl.$file[0]."thumbs/".$file[1];
} else {
$media_image .= $file[0].$file[1];
}
$media_image .= "' class='input'>";
} else {
$media_image = "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
}
}

$smarty->assign("media_name", $row[name]);
$smarty->assign("media_image", $media_image);
$smarty->assign("media_url", url("media-gallery", $row[id], $row[name]));
$smarty->assign("media_link", "<a href='".url("media-gallery", $row[id], $row[name])."'>".$row[name]."</a>");
}

if ($_SESSION["rating_file_$id"]) {
$ex = @explode("|", $r[rating]);
$cur_rat = round($ex[1]);

if ($cur_rat == 0) {
$cur_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$cur_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$cur_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$cur_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$cur_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$cur_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
}
} else {

if (!$useridn && strtolower($setting["ratings_guests_content"]) == "yes" or $useridn) {
$cur_rating = "<script>
function rate( value ) {
	new Ajax.Updater( 'rating', '".$siteurl."inc/rating.php?id=".$r[id]."&rat_num=".$ex[0]."&rat_tot=".$ex[1]."&type=content&v='+value );
}
</script>

<div id='rating'>
<img src='".$siteurl."images/star_off.gif' onclick='rate(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(5)'></img>
</div>
<br/>";
}
}

$smarty->assign("file_rating", $cur_rating);
$smarty->assign("file_name", $r[filename]);
if (stristr($r[filedir], "http")) {
$info = pathinfo($$r[filedir].$r[filename]);
$file_type = file_type($info["extension"], "");

if ($file_type == "image") {
list($width, $height, $type, $attr) = getimagesize($r[filedir].$r[filename]);
if ($width > 480) {
$smarty->assign("file_code", "<a href='".$r[filedir].$r[filename]."'><img src='".$r[filedir].$r[filename]."' width='480'></a>");
} else {
$smarty->assign("file_code", "<a href='".$r[filedir].$r[filename]."'><img src='".$r[filedir].$r[filename]."'></a>");
}
} elseif ($file_type == "video" or $file_type == "music") {
$smarty->assign("file_code", "<script type='text/javascript' src='".$siteurl."inc/mediaplayer/swfobject.js'></script>
 
<div id='mediaspace'>This text will be replaced</div>
 
<script type='text/javascript'>
  var so = new SWFObject('".$siteurl."inc/mediaplayer/player.swf','mpl','470','320','9');
  so.addParam('allowfullscreen','true');
  so.addParam('allowscriptaccess','always');
  so.addParam('wmode','opaque');
  so.addVariable('file','".$r[filedir].$r[filename]."');
  so.addVariable('image','".file_type($info["extension"], "image")."');
  so.write('mediaspace');
</script>");
} else {
$smarty->assign("file_code", "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>");
}
$smarty->assign("file_url", $r[filedir].$r[filename]);
$smarty->assign("file_thumb", $r[filedir]."thumbs/".$r[filename]);
} else {
$info = pathinfo($siteurl.$r[filedir].$r[filename]);
$file_type = file_type($info["extension"], "");

if ($file_type == "image") {
list($width, $height, $type, $attr) = getimagesize($siteurl.$r[filedir].$r[filename]);
if ($width > 480) {
$smarty->assign("file_code", "<a href='".$siteurl.$r[filedir].$r[filename]."'><img src='".$siteurl.$r[filedir].$r[filename]."' width='480'></a>");
} else {
$smarty->assign("file_code", "<a href='".$siteurl.$r[filedir].$r[filename]."'><img src='".$siteurl.$r[filedir].$r[filename]."'></a>");
}
} elseif ($file_type == "video" or $file_type == "music") {
$smarty->assign("file_code", "<script type='text/javascript' src='".$siteurl."inc/mediaplayer/swfobject.js'></script>
 
<div id='mediaspace'>This text will be replaced</div>
 
<script type='text/javascript'>
  var so = new SWFObject('".$siteurl."inc/mediaplayer/player.swf','mpl','470','320','9');
  so.addParam('allowfullscreen','true');
  so.addParam('allowscriptaccess','always');
  so.addParam('wmode','opaque');
  so.addVariable('file','".$siteurl.$r[filedir].$r[filename]."');
  so.addVariable('image','".file_type($info["extension"], "image")."');
  so.write('mediaspace');
</script>");
} else {
$smarty->assign("file_code", "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>");
}
$smarty->assign("file_url", $siteurl.$r[filedir].$r[filename]);
$smarty->assign("file_thumb", $siteurl.$r[filedir]."thumbs/".$r[filename]);
}

$smarty->assign("file_caption", stripslashes($r[caption]));
$smarty->assign("file_date", timef($r[date]));
$smarty->assign("file_view", url("media-file", $r[id], $r[filename]));

unset($cur_rating);
}
} elseif ($type == "media_page") {
if ($id) {
if (is_numeric($id)) {
$idvar = " WHERE id = '".$id."'";
} else {
$ex = explode(",", $id);
if (count($ex) == 1) {
$idvar = " WHERE id = '".$ex[0]."'";
} else {
$idvar .= " WHERE";
while (list($k,$i) = @each($ex)) {
if ($k != 0) {
$idvar .= " OR";
}
$idvar .= " id = '".$i."'";
}
}
}
}
$row = mysql_fetch_row(mysql_query("SELECT id,name FROM ".$pre."media".$idvar." ORDER BY ".$sort." ".$sortby));
$row[1] = stripslashes($row[1]);

$smarty->assign("media_name", $row[1]);
$smarty->assign("media_image", $media_image);
$smarty->assign("media_url", url("media-gallery", $row[0], $row[1]));
$smarty->assign("media_link", "<a href='".url("media-gallery", $row[0], $row[1])."'>".$row[1]."</a>");

$file = mysql_fetch_row(mysql_query("SELECT filedir,filename FROM ".$pre."files WHERE media_id = '".$row[0]."' ORDER BY `id` DESC LIMIT 1"));
if (!$file[0]) {
$media_image = "<img src='".$siteurl."images/nopreview.jpg' class='input'>";
} else {
if ($file[0] == $setting["upload_folder"]) {
$info = pathinfo($siteurl.$file[0].$file[1]);
} else {
$info = pathinfo($file[0].$file[1]);
}
if (file_type($info["extension"], "") == "image") {
$media_image = "<img src='";
if ($file[0] == $setting["upload_folder"]) {
$media_image .= $siteurl.$file[0]."thumbs/".$file[1];
} else {
$media_image .= $file[0].$file[1];
}
$media_image .= "' class='input'>";
} else {
$media_image = "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
}
}

$rand = randompass(3);
$echo .= "<link type='text/css' media='screen' rel='stylesheet' href='".$siteurl."inc/js/colorbox.css' />
		<script type='text/javascript' src='".$siteurl."inc/js/jquery.colorbox-min.js'></script>
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$(\"a[rel='media_".$rand."']\").colorbox({slideshow:true,transition:\"fade\", width:\"50%\", height:\"50%\"});
				$(\".media2_".$rand."\").colorbox({width:\"50%\", inline:true, href:\"#inline\"});
			});
		</script>";

$cid = 0;
unset($sql, $r, $file_code);
$sql = mysql_query("SELECT * FROM ".$pre."files".str_replace(" id ", " media_id ", $idvar)." ORDER BY ".$sort." ".$sortby." LIMIT ".$froms.$limit);
$pag_sql = $pre."files".str_replace(" id ", " media_id ", $idvar);
while($r = mysql_fetch_array($sql)) {
$id = $r[id];

if ($_SESSION["rating_file_$id"]) {
$ex = @explode("|", $r[rating]);
$cur_rat = round($ex[1]);

if ($cur_rat == 0) {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$file_rating[$cid] .= "<img src='".$siteurl."images/star_off.gif'></img>";
}
} else {

if (!$useridn && strtolower($setting["ratings_guests_content"]) == "yes" or $useridn) {
$file_rating[$cid] = "<script>
function rate( value ) {
	new Ajax.Updater( 'rating', '".$siteurl."inc/rating.php?id=".$r[id]."&rat_num=".$ex[0]."&rat_tot=".$ex[1]."&type=content&v='+value );
}
</script>

<div id='rating'>
<img src='".$siteurl."images/star_off.gif' onclick='rate(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(5)'></img>
</div>
<br/>";
}
}

$file_name[$cid] = $r[filename];
if (stristr($r[filedir], "http")) {
$info = pathinfo($r[filedir].$r[filename]);
$file_url[$cid] = $r[filedir].$r[filename];
$file_thumb[$cid] = $r[filedir]."thumbs/".$r[filename];

if (file_type($info["extension"], "") == "image") {
$file_code[$cid] = "<img src='".$r[filedir].$r[filename]."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."'>";
} else {
$file_code[$cid] = "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
}
} else {
$info = pathinfo($siteurl.$r[filedir].$r[filename]);
$file_url[$cid] = $siteurl.$r[filedir].$r[filename];
$file_thumb[$cid] =  $siteurl.$r[filedir]."thumbs/".$r[filename];

if (file_type($info["extension"], "") == "image") {
$file_code[$cid] = "<img src='".$siteurl.$r[filedir].$r[filename]."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."'>";
} else {
$file_code[$cid] = "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
}
}
$file_caption[$cid] = stripslashes($r[caption]);
$file_date[$cid] = timef($r[date]);
//url("media-file", $r[id], $r[filename])
if (file_type($info["extension"], "") == "video" or file_type($info["extension"], "") == "music") {
$echo .= "<div style='display:none'>

			<div id='inline' style='padding:10px; background:#fff;'><script type='text/javascript' src='".$siteurl."inc/mediaplayer/swfobject.js'></script>
 
<div id='mediaspace'>This text will be replaced</div>
 
<script type='text/javascript'>
  var so = new SWFObject('".$siteurl."inc/mediaplayer/player.swf','mpl','470','320','9');
  so.addParam('allowfullscreen','true');
  so.addParam('allowscriptaccess','always');
  so.addParam('wmode','opaque');
  ";
  if (stristr($r[filedir], "http")) {
  $echo .= "so.addVariable('file','".$r[filedir].$r[filename]."');";
  } else {
  $echo .= "so.addVariable('file','".$siteurl.$r[filedir].$r[filename]."');";
  }

  $echo .= "
  so.addVariable('image','".file_type($info["extension"], "image")."');
  so.write('mediaspace');
</script></div>
		</div>";
$file_view[$cid] = $file_url[$cid]."' title=\"".$r[caption]."\" class='media2_".$rand;
} else {
$file_view[$cid] = $file_url[$cid]."' title=\"".$r[caption]."\" rel='media_".$rand;
}
$file_view_page[$cid] = url("media-file", $r[id], $r[filename]);


$co[] = $cid;
$cid++;
}

$smarty->assign("file", $co);
$smarty->assign("file_name", $file_name);
$smarty->assign("file_url", $file_url);
$smarty->assign("file_thumb", $file_thumb);
$smarty->assign("file_view", $file_view);
$smarty->assign("file_code", $file_code);
$smarty->assign("file_view_page", $file_view_page);
$smarty->assign("file_caption", $file_caption);
$smarty->assign("file_rating", $file_rating);
$smarty->assign("file_date", $file_date);
} elseif ($type == "media_list") {
if ($id) {
if (is_numeric($id)) {
$idvar = " WHERE id = '".$id."'";
} else {
$ex = explode(",", $id);
if (count($ex) == 1) {
$idvar = " WHERE id = '".$ex[0]."'";
} else {
$idvar .= " WHERE";
while (list($k,$i) = @each($ex)) {
if ($k != 0) {
$idvar .= " OR";
}
$idvar .= " id = '".$i."'";
}
}
}
}
$cid = 0;
$sql = mysql_query("SELECT * FROM ".$pre."media".$idvar." ORDER BY ".$sort." ".$sortby." LIMIT ".$froms.$limit);
$pag_sql = $pre."media".$idvar;
while($r = mysql_fetch_array($sql)) {
$r[name] = stripslashes($r[name]);
$file = mysql_fetch_row(mysql_query("SELECT filedir,filename FROM ".$pre."files WHERE media_id = '".$r[id]."' ORDER BY `id` DESC LIMIT 1"));
if (!$file[0]) {
$media_image[$cid] = "<img src='".$siteurl."images/nopreview.jpg' class='input'>";
} else {
if ($file[0] == $setting["upload_folder"]) {
$info = pathinfo($siteurl.$file[0].$file[1]);
} else {
$info = pathinfo($file[0].$file[1]);
}
if (file_type($info["extension"], "") == "image") {
$media_image[$cid] = "<img src='";
if ($file[0] == $setting["upload_folder"]) {
$media_image[$cid] .= $siteurl.$file[0]."thumbs/".$file[1];
} else {
$media_image[$cid] .= $file[0].$file[1];
}
$media_image[$cid] .= "' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
} else {
$media_image[$cid] = "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
}
}

$media_url[$cid] = url("media-gallery", $r[id], $r[name]);
$media_link[$cid] = "<a href='".url("media-gallery", $r[id], $r[name])."'>".$r[name]."</a>";
$media_name[$cid] = $r[name];

$co[] = $cid;
$cid++;
}
$smarty->assign("media", $co);
$smarty->assign("media_name", $media_name);
$smarty->assign("media_image", $media_image);
$smarty->assign("media_url", $media_url);
}
echo $echo;
$smarty->display($skin."/".$template.".tpl", $type."-".$row[name]);
if ($pag) {
unset($build_url);
if ($_GET['view']) {
$build_url .= "view=".$_GET['view']."&";
}
if ($_GET['do']) {
$build_url .= "do=".$_GET['do']."&";
}
if ($_GET['id']) {
$build_url .= "id=".$_GET['id']."&";
}
if ($_GET['section']) {
$build_url .= "section=".$_GET['section']."&";
}
if ($_GET['username']) {
$build_url .= "username=".$_GET['username']."&";
}
if ($_GET['search']) {
$build_url .= "search=".$_GET['search']."&";
}

paginate($pag_sql, $siteurl."index.php?".$build_url, $limit);
}
}

function valid_url($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function content($template, $section, $limit, $pag = NULL, $id = NULL, $sort = NULL, $sortby = NULL, $field = NULL, $dataf = NULL) {
global $pre;
global $lev;
global $group;
global $setting;
global $siteurl;
global $skin;
global $sitepath;
global $smarty;
global $pageurl;
global $useridn;
global $buildurl;

global $publickey;
global $email;

require_once($sitepath.'inc/recaptchalib.php');
$search_count = 0;

if ($id) {
if (is_array($id) == FALSE && is_numeric($id)) {
$idvar = " AND id = '".$id."'";
} elseif (is_array($id) == FALSE && !is_numeric($id)) {
$idvar = " AND name LIKE '%".$id."%'";
} else {
$ex = explode(",", $id);
if (count($ex) == 1) {
$idvar = " AND id = '".$ex[0]."'";
} else {
while (list($k,$i) = @each($ex)) {
if ($k != 0) {
$idvar .= " OR";
}
$idvar .= " id = '".$i."'";
}
}
}
}

if ($field or $dataf) {
$field = str_replace("_", " ", str_replace("-"," ", stripslashes(htmlspecialchars(check($field)))));
$dataf = str_replace("_", " ", str_replace("-"," ", stripslashes(htmlspecialchars(check($dataf)))));
$ex = explode(",", $dataf);

if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."sections WHERE name = '".$field."'")) == 0) {
while (list($k,$i) = @each($ex)) {
if ($k == 0) {
} else {
$fddata .= " OR";
}
$fddata .= " field_name = '".$field."' AND data LIKE '%". $i ."%'";
}
} else {
while (list($k,$i) = @each($ex)) {
$gid = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."content WHERE section = '".$field."' AND name LIKE '%" . addslashes($i) . "%' AND status = ''"));
if ($k == 0) {
} else {
$fddata .= " OR";
}
if (!$gid[0]) {
$fddata .= " field_name = '".$field."' AND data = '".addslashes($i)."'";
} else {
$fddata .= " field_name = '".$field."' AND data LIKE '%". $gid[0] ."%'";
}
}
unset($gid);
}
}

if ($limit == "") {
$limit = "5";
}
if ($sort == "") {
$sort = "`id`";
} else {
if ($sort == "RAND()") {
$sort = "RAND()";
} else {
$sort = "`".$sort."`";
}
}
if ($sortby == "") {
$sortby = "DESC";
}

if ($pag) {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $limit) - $limit);
$froms = $from.", ";
}

if ($field or $dataf) {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE".$fddata." ORDER BY ".$sort." ".$sortby." LIMIT ".$froms.$limit);
$pag_sql = $pre."data WHERE".$fddata;
} else {
if ($section) {
$sectionss = explode(",", $section);
while (list($i, $mb) = @each ($sectionss)) {
if ($i == 0) {
} else {
$sections .= " OR";
}
$sections .= " section = '".$mb."' AND status = ''";
}
$sql = mysql_query("SELECT * FROM ".$pre."content WHERE".$sections." ORDER BY ".$sort." ".$sortby." LIMIT ".$froms.$limit);
$pag_sql = $pre."content WHERE".$sections;
} else {
$sql = mysql_query("SELECT * FROM ".$pre."content WHERE status = ''".$idvar." ORDER BY ".$sort." ".$sortby." LIMIT ".$froms.$limit);
$pag_sql = $pre."content WHERE status = ''".$idvar;
}
}
while($r = mysql_fetch_array($sql)) {
if ($_GET['view'] == "search") {
$search_count = 1;
}
unset($data, $datas, $pab, $rab, $name1, $link1, $n, $m, $y, $x, $i, $name, $relations_id, $relations_sec, $s, $fetch, $get, $dats, $fname, $lid, $lids, $b, $sqlst, $k, $data23, $check, $tags, $tagg, $rel_ids, $rel_sec, $rel_sections, $relate_check, $date, $current_rating);

if ($field or $dataf) {
$get = mysql_fetch_row(mysql_query("SELECT section,id,user_id,name,views,date FROM ".$pre."content WHERE id = '".$r[item_id]."'"));
$r[section] = $get[0];
$r[id] = $get[1];
$r[user_id] = $get[2];
$r[name] = $get[3];
$r[views] = $get[4];
$r[date] = $get[5];
}

if ($dataf && !$section or $section == $r[section] && $dataf or !$dataf) {

// gets default data (like to show part of a news article on the front page, for example)
if (!$section && $r[name] or $section && $fddata && $section == $r[section] && $r[name] or $section && !$fddata && $r[name]) {
$s = mysql_query("SELECT * FROM ".$pre."fields WHERE type = 'textarea' AND section = '".$r[section]."' ORDER BY `id` ASC LIMIT 1");
while($x = mysql_fetch_array($s)) {
$fetch = mysql_fetch_row(mysql_query("SELECT data,field_name FROM ".$pre."data WHERE field_name = '".$x[name]."' AND item_id = '".$r[id]."'"));
if ($fetch[0]) {
$datas[] = stripslashes($fetch[0]);
}
}
if ($datas) {
asort($datas);
while (list($k,$i) = each($datas)) {
if ($i) {
$data = $i;
}
}
} else {
$data = "No Description";
}
}
// end

$comments_num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$r[id]."'"));
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[section]."'"));
$p = explode("|", $ps[0]);
$tags = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = 'tags' AND item_id = '".$r[id]."'"));
$rand = rand();
if ($_GET['id'] == $r[id] && $_GET['view'] == "content") {
$ends .= "<script type='text/javascript'>";
} elseif ($p[1] or $r[user_id] == $useridn) {
$end .= "
	$('name|".$r[id]."').editInPlace({
		form_type: 'text'
	});
";
}

if ($tags[0]) {
$ex = explode(",", $tags[0]);
$cn = count($ex) - 1;
while (list($b, $a) = @each ($ex)) {
if ($b != $cn) {
$tagg .= "<a href='".$siteurl."tag/".$a."'>".$a."</a>, ";
} else {
$tagg .= "<a href='".$siteurl."tag/".$a."'>".$a."</a>";
}
}
}
$cid = 0;
//id,user_id,comment,rating,author,email,website,date
$comm = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$r[id]."'");
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
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=".$r[id]."'>Lock Comment</a>";
} elseif ($p[1] or $r[user_id] == $useridn) {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=".$r[id]."&show=1'>Unlock Comment</a>";
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
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' id='email' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br />".recaptcha_get_html($publickey)."<input type='hidden' name='article_id' value='".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

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
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br /><input type='hidden' name='article_id' value='".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

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

$ex = @explode("|", $r[rating]);
$cur_rat = round($ex[1]);

if ($cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
}

$id = $r[id];
$smarty->assign("current_rating", $current_rating);
if (!$useridn && strtolower($setting["ratings_guests_content"]) == "yes" && !$_SESSION["rating_content_$id"] or $useridn && !$_SESSION["rating_content_$id"]) {
$smarty->assign("rating_form", "<script>
function rate( value ) {
	new Ajax.Updater( 'rating', '".$siteurl."inc/rating.php?id=".$r[id]."&rat_num=".$ex[0]."&rat_tot=".$ex[1]."&type=content&v='+value );
}
</script>

<div id='rating'>
<img src='".$siteurl."images/star_off.gif' onclick='rate(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(5)'></img>
</div>
<br/>");
} else {
$smarty->assign("rating_form", "<i>Sorry, you cannot rate this item again</i>");
}
$rand = rand();
$idm .= $r[id]."_";
if ($p[1] or $r[user_id] == $useridn) {
$quick_edit = 1;

$smarty->assign("link", "<a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a>&nbsp;&nbsp;");
$smarty->assign($r[section]."_link", "<a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a>&nbsp;&nbsp;");
} else {
$smarty->assign("link", "<a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a>");
$smarty->assign($r[section]."_link", "<a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a>");
}
$smarty->assign("date", timef($r[date]));
$smarty->assign($r[section]."_date", timef($r[date]));
if ($p[1] && $_GET['id'] != $r[id] or $r[user_id] == $useridn && $_GET['id'] != $r[id]) {
$smarty->assign("story", "".parse_text($data));
$smarty->assign($r[section]."_story", "".parse_text($data));
} else {
$smarty->assign("story", stripslashes(html_entity_decode(stripslashes($data))));
$smarty->assign($r[section]."_story", stripslashes(html_entity_decode(stripslashes($data))));
}
$smarty->assign("comments_link", "<a href='".url("content", $r[id], $r[name], $r[section])."#comments'>Comments</a>");
$smarty->assign($r[section]."_comments_link", "<a href='".url("content", $r[id], $r[name], $r[section])."#comments'>Comments</a>");
$smarty->assign("comments_num", $comments_num);
$smarty->assign($r[section]."_comments_num", $comments_num);
$smarty->assign("author", get_user($r[user_id]));
$smarty->assign($r[section]."_author", get_user($r[user_id]));
$smarty->assign("username", get_user($r[user_id]));
$smarty->assign($r[section]."_username", get_user($r[user_id]));
$smarty->assign("section", $r[section]);
$smarty->assign($r[section]."_section", $r[section]);
$smarty->assign("category", $r[section]);
$smarty->assign($r[section]."_category", $r[section]);
$smarty->assign("url", url("content", $r[id], $r[name], $r[section]));
$smarty->assign($r[section]."_url",  url("content", $r[id], $r[name], $r[section]));
$smarty->assign("title", stripslashes($r[name]));
$smarty->assign($r[section]."_title", stripslashes($r[name]));
$smarty->assign("subject", stripslashes($r[name]));
$smarty->assign($r[section]."_subject", stripslashes($r[name]));
if ($p[1] or $r[user_id] == $useridn) {
$smarty->assign("name", "".stripslashes($r[name]));
$smarty->assign($r[section]."_name", "".stripslashes($r[name]));
} else {
$smarty->assign("name", stripslashes($r[name]));
$smarty->assign($r[section]."_name", stripslashes($r[name]));
}
$smarty->assign("id", $r[id]);
$smarty->assign($r[section]."_id", $r[id]);
$smarty->assign("views", number_format($r[views]));
$smarty->assign($r[section]."_views", number_format($r[views]));
$smarty->assign("rating", $r[rating]); // temp
$smarty->assign($r[section]."_rating", $r[rating]); // temp
$smarty->assign("social_icons", soc_bookmark(url("content", $r[id], $r[name], $r[section]), $r[name], "", $data, 'Y'));
$smarty->assign($r[section]."_social_icons", soc_bookmark(url("content", $r[id], $r[name], $r[section]), $r[name], "", $data, 'Y'));
$smarty->assign("tags", $tagg);
$smarty->assign($r[section]."_tags", $tagg);

// start - custom fields
$name = "";$data = "";$row = "";
$sql_cf = mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$r[section]."' OR section = 'user-profile'");
while ($row = mysql_fetch_array($sql_cf)) {
$name = "$row[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$name."' AND item_id = '".$r[id]."'"));
$fdata[$name] = $data[0];

if (!$data[0]) {
$smarty->assign($name, "");
$smarty->assign($r[section]."_".$name, "");
} else {
if (valid_url($data[0]) == 1 or is_numeric($data[0])) {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($r[section]."_".$name, stripslashes(html_entity_decode($data[0])));
} else {
if ($p[1] or $r[user_id] == $useridn or $useridn && $row[editable]) {
if ($row[type] == "textarea") {
$rand = rand();
$smarty->assign($name, parse_text($data[0]));
$smarty->assign($r[section]."_".$name, parse_text($data[0]));
$end .= "
	$('".$row[name]."|".$r[id]."|".$row[type]."').editInPlace({
		form_type: 'textarea'
	});

		$('".$row[name]."|".$r[id]."|".$row[type]."|".$rand."').editInPlace({
		form_type: 'textarea'
	});
";
} elseif ($row[type] == "textfield") {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($r[section]."_".$name, stripslashes(html_entity_decode($data[0])));
$end .= "
	$('".$row[name]."|".$r[id]."').editInPlace({
		form_type: 'text'
	});
";
} elseif ($row[type] == "radio" or $row[type] = "checkbox" or $row[type] == "select") {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($r[section]."_".$name, stripslashes(html_entity_decode($data[0])));
unset($ex,$a,$options);
$ex = explode(",", $row[info]);
sort($ex);
while (list(, $a) = @each ($ex)) {
$options .= "
		'".$a."':		'".ucwords($a)."',";
}
$options = substr_replace($options,"",-1);
$end .= "
	$('".$row[name]."|".$r[id]."|".$row[type]."').editInPlace({
		form_type: 'select',
		select_options: {".$options."
		}
	});
";
}


} else {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($r[section]."_".$name, stripslashes(html_entity_decode($data[0])));
}
}
}
}
// end - custom fields


$ends .= "</script>";

$relate_check = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."content WHERE section != '".$r[section]."' AND name LIKE '%" . addslashes($r[name]) . "%' AND status = ''"));
if ($relate_check[0]) {
$rel_ids[] = $relate_check[0];
}

$sql_rels = mysql_query("SELECT * FROM ".$pre."sections");
while ($q = mysql_fetch_array($sql_rels)) {
if ($r[section] != $q[name]) {
$rel_sec = 0;
$secname = $q[name];

$sql_rel = mysql_query("SELECT * FROM ".$pre."data WHERE field_name = '".$q[name]."' AND item_id ='".$r[id]."'");
while ($rel = mysql_fetch_array($sql_rel)) {
$rel_ids[$secname] = $rel[data];
$rel_sec = 1;
}
if ($rel_sec = 1) {
$rel_sections[] = $q[name];
}

}
}

// assigning values to related items, normal fields and then custom fields
unset($i,$q,$sec,$k,$i);
if ($rel_ids) {
while (list($k,$i) = each($rel_ids)) {
$q = mysql_fetch_row(mysql_query("SELECT section,id,user_id,name,views,date,rating FROM ".$pre."content WHERE id = '".$i."'"));
$sec = $q[0];

$comments_num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$i."'"));
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$q[0]."'"));
$p = explode("|", $ps[0]);
$tags = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = 'tags' AND item_id = '".$i."'"));

$cid = 0;
//id,user_id,comment,rating,author,email,website,date
$comm = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$i."'");
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

<div id='rating_".$cmid."'>
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
$comments_comment[$cid] = badwords(stripslashes($com[comment]));
$comments_rating[$cid] = $crate;
$comments_rating_form[$cid] = $cform;
$comments_email[$cid] = $com[email];
$comments_website[$cid] = "<a href='".$com[website]."'>".$com[website]."</a>";
$comments_date[$cid] = timef($com[date]);

$co[] = $cid;
$cid++;
}
$smarty->assign($sec."_comments", $co);
$smarty->assign($sec."_comments_id", $comments_id);
$smarty->assign($sec."_comments_username", $comments_userid);
$smarty->assign($sec."_comments_comment", $comments_comment);
$smarty->assign($sec."_comments_rating", $comments_rating);
$smarty->assign($sec."_comments_rating_form", $comments_rating_form);
$smarty->assign($sec."_comments_email", $comments_email);
$smarty->assign($sec."_comments_website", $comments_website);
$smarty->assign($sec."_comments_date", $comments_date);

if (!perm("comments", "add")) {
$smarty->assign($sec."_comments_form", "Sorry but you cannot post a comment, you do not have the necessary permissions to.");
} else {
$diff = time() - $_SESSION['last_comment_time'];
if ($_SESSION['last_comment_time'] && $diff < $settiong["comment_flood_limit"] or !$_SESSION['last_comment_time']) {
if (strtolower($setting["captcha_comments"]) == "yes") {
$smarty->assign($sec."_comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' id='email' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br />".recaptcha_get_html($publickey)."<input type='hidden' name='article_id' value='".$i."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

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
$smarty->assign($sec."_comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br /><input type='hidden' name='article_id' value='".$i."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

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
$smarty->assign($sec."_comments_form", "You have reached the comment flood limit of ".$_SESSION['last_comment_time']." seconds. Please wait ".$diff." more seconds to comment again");
}
}

$ex = @explode("|", $q[6]);
$cur_rat = round($ex[1]);

if ($cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$current_rating .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$current_rating .= "<img src='".$siteurl."images/star_off.gif'></img>";
}

$smarty->assign($sec."_current_rating", $current_rating);
if (!$useridn && strtolower($setting["ratings_guests_content"]) == "yes" && !$_SESSION["rating_content_$i"] or $useridn && !$_SESSION["rating_content_$i"]) {
$smarty->assign($sec."_rating_form", "<script>
function rate( value ) {
	new Ajax.Updater( 'rating', '".$siteurl."inc/rating.php?id=".$i."&rat_num=".$ex[0]."&rat_tot=".$ex[1]."&type=content&v='+value );
}
</script>

<div id='rating'>
<img src='".$siteurl."images/star_off.gif' onclick='rate(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate(5)'></img>
</div>
<br/>");
}

if ($p[1] && $_GET['id'] != $i or $q[2] == $useridn && $_GET['id'] != $i) {
$smarty->assign($sec."_link", "<a href='".url("content", $i, $q[3], $q[0])."'>".stripslashes($q[3])."</a>&nbsp;&nbsp;");
} else {
$smarty->assign($sec."_link", "<a href='".url("content", $i, $q[3], $q[0])."'>".stripslashes($q[3])."</a>");
}
$smarty->assign($sec."_date", timef($q[5]));
if ($p[1] && $_GET['id'] != $i or $q[2] == $useridn && $_GET['id'] != $i) {
$smarty->assign($sec."_story", parse_text($data));
} else {
$smarty->assign($sec."_story", stripslashes(html_entity_decode(stripslashes($data))));
}
$smarty->assign($sec."_comments_link", "<a href='".url("content", $i, $q[3], $q[0])."#comments'>Comments</a>");
$smarty->assign($sec."_comments_num", $comments_num);
$smarty->assign($sec."_author", get_user($q[2]));
$smarty->assign($sec."_username", get_user($q[2])); 
$smarty->assign($sec."_section", $q[0]);
$smarty->assign($sec."_category", $q[0]);
$smarty->assign($sec."_url",  url("content", $i, $q[3], $q[0]));
$smarty->assign($sec."_title", stripslashes($q[3]));
$smarty->assign($sec."_subject", stripslashes($q[3]));
if ($p[1] && $_GET['id'] != $i or $q[2] == $useridn && $_GET['id'] != $i) {
$smarty->assign($sec."_name", stripslashes($q[3]));
} else {
$smarty->assign($sec."_name", stripslashes($q[3]));
}
$smarty->assign($sec."_id", $i);
$smarty->assign($sec."_views", number_format($q[4]));
$smarty->assign($sec."_rating", $q[rating]); // temp
$smarty->assign($sec."_social_icons", soc_bookmark(url("content", $i, $q[3], $q[0]), $q[3], "", $data, 'Y'));
$smarty->assign($sec."_tags", $tagg);

// start related items - custom fields
$name = "";$data = "";$row = "";
$sql_cf = mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$q[0]."' OR section = 'user-profile'");
while ($row = mysql_fetch_array($sql_cf)) {
$name = "$row[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$row[name]."' AND item_id = '".$i."'"));
$fdata[$name] = $data[0];

if (!$data[0]) {
$smarty->assign($sec."_".$name, "");
} else {
if (valid_url($data[0]) == 1 or is_numeric($data[0])) {
$smarty->assign($sec."_".$name, stripslashes(html_entity_decode($data[0])));
} else {
if ($p[1] && $_GET['id'] != $i or $r[user_id] == $useridn && $_GET['id'] != $i or $row[editable] && $useridn && $_GET['id'] != $i) {
if ($row[type] == "textarea") {
$smarty->assign($sec."_".$name, parse_text($data[0]));
$end .= "
	$('".$row[name]."|".$i."|".$row[type]."').editInPlace({
		form_type: 'textarea'
	});
";
} elseif ($row[type] == "textfield") {
$smarty->assign($sec."_".$name, stripslashes(html_entity_decode($data[0])));
$end .= "
	$('".$row[name]."|".$i."').editInPlace({
		form_type: 'text'
	});
";
} elseif ($row[type] == "radio" or $row[type] = "checkbox" or $row[type] == "select") {
$smarty->assign($sec."_".$name, stripslashes(html_entity_decode($data[0])));
unset($ex,$a,$options);
$ex = explode(",", $row[info]);
sort($ex);
while (list(, $a) = each ($ex)) {
$options .= "
		'".$a."':		'".ucwords($a)."',";
}
$options = substr_replace($options,"",-1);
$end .= "
	$('".$row[name]."|".$i."|".$row[type]."').editInPlace({
		form_type: 'select',
		select_options: {".$options."
		}
	});
";
}


} else {
$smarty->assign($sec."_".$name, stripslashes(html_entity_decode($data[0])));
}
}
}
}
// end - custom fields

}
}
echo "<script type='text/javascript'>".$end."</script>";
$smarty->display($skin."/".$template.".tpl", "articles - ".$idm);
}
}
$smarty->clear_all_assign();
if ($_GET['view'] == "search" && $search_count == 0) {
echo "<p>Sorry, there are no results for '<b>".$_GET['q']."</b>'</p><br />";
}

if ($pag) {
paginate($pag_sql, $siteurl.$buildurl, $limit);
}
}

function get_search_phrase($referer){
  
  $key_start = 0;
  $search_phrase = "";
 
  // used by dogpile, excite, webcrawler, metacrawler
  if (strpos($referer, '/search/web/') !== false) $key_start = strpos($referer, '/search/web/') + 12;
  
  // used by chubba             
  if (strpos($referer, 'arg=') !== false) $key_start = strpos($referer, 'arg=') + 4;
  
  // used by dmoz              
  if (strpos($referer, 'search=') !== false) $key_start = strpos($referer, 'query=') + 7;
  
  // used by looksmart              
  if (strpos($referer, 'qt=') !== false) $key_start = strpos($referer, 'qt=') + 3;
  
  // used by scrub the web          
  if (strpos($referer, 'keyword=') !== false) $key_start = strpos($referer, 'keyword=') + 8;
  
  // used by overture, hogsearch            
  if (strpos($referer, 'keywords=') !== false) $key_start = strpos($referer, 'keywords=') + 9;
  
  // used by mamma, lycos, kanoodle, snap, whatuseek              
  if (strpos($referer, 'query=') !== false) $key_start = strpos($referer, 'query=') + 6;
  
  // don't allow encrypted key words by aol            
  if (strpos($referer, 'encquery=') !== false) $key_start = 0; 
  
  // used by ixquick              
  if (strpos($referer, '&query=') !== false) $key_start = strpos($referer, '&query=') + 7;
  
  // used by aol
  if (strpos($referer, 'qry=') !== false) $key_start = strpos($referer, 'qry=') + 4;
  
  // used by yahoo, hotbot
  if (strpos($referer, 'p=') !== false) $key_start = strpos($referer, 'p=') + 2;

  // used by google, msn, alta vista, ask jeeves, all the web, teoma, wisenut, search.com
  if (strpos($referer, 'q=') !==  false) $key_start = strpos($referer, 'q=') + 2;
  
  // if present, get the search phrase from the referer
  if ($key_start > 0){    
    if (strpos($referer, '&', $key_start) !== false){
      $search_phrase = substr($referer, $key_start, (strpos($referer, '&', $key_start) - $key_start));
      
    } elseif (strpos($referer, '/search/web/') !== false){
    
        if (strpos($referer, '/', $key_start) !== false){
          $search_phrase = urldecode(substr($referer, $key_start, (strpos($referer, '/', $key_start) - $key_start)));
        } else {
          $search_phrase = urldecode(substr($referer, $key_start));
        }
        
    } else {
      $search_phrase = substr($referer, $key_start);
    } 
  } 
  
  $search_phrase = urldecode($search_phrase);
  return $search_phrase;

}

function detect_bot() {
global $botlist;

foreach($botlist as $bot) {
if(ereg($bot, $_SERVER['HTTP_USER_AGENT'])) {

if($bot == "Googlebot") {
if (substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0, 11) == "216.239.46.") {
$thebot = 1;
return "Googlebot Deep Crawl";
} elseif (substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0,7) == "64.68.8") {
$thebot = 1;
return "Google Freshbot";
}
}
if ($thebot == "") {
return $bot;
}

if ($_SERVER['QUERY_STRING'] != "") {
$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "";
} else {
$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . "";
}

}
}
}

// php.net - Anonymous 04-Aug-2009 10:45 - http://us2.php.net/manual/en/function.mt-rand.php
function randompass($length = 10)
{   
    // Do not modify anything below here
    $underscores = 2; // Maximum number of underscores allowed in password
   
    $p ="";
    for ($i=0;$i<$length;$i++)
    {   
        $c = mt_rand(1,7);
        switch ($c)
        {
            case ($c<=2):
                // Add a number
                $p .= mt_rand(0,9);   
            break;
            case ($c<=4):
                // Add an uppercase letter
                $p .= chr(mt_rand(65,90));   
            break;
            case ($c<=6):
                // Add a lowercase letter
                $p .= chr(mt_rand(97,122));   
            break;
            case 7:
                 $len = strlen($p);
                if ($underscores>0&&$len>0&&$len<($length-1)&&$p[$len-1]!="_")
                {
                    $p .= "_";
                    $underscores--;   
                }
                else
                {
                    $i--;
                    continue;
                }
            break;       
        }
    }
    return $p;
} 

function stats_update($do) {
global $pre;

if ($do == "new_day") {
if (date("N") == 1) {
$week = date("W") - 1;
} else {
$week = date("W");
}
if (date("j") == 1) {
$month = date("n") - 1;
} else {
$month = date("n");
}
if (date("z") == 0) {
if (date("L") == 1) {
$day = 366;
} else {
$day = 365;
}
$year = date("Y") -1;
} else {
$day = date("z") - 1;
$year = date("Y");
}

$stats1_1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE visit_type = 'unique'"));
$stats1_2 = @mysql_fetch_row(mysql_query("SELECT SUM(visits_num),time_last_visit FROM ".$pre."stats WHERE visit_type = 'view'"));

if ($stats1_1 > 0) {

mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'day', '".$day."', '".$week."', '".$month."', '".$year."', '".$stats1_2[0]."', '".$stats1_1."', '".$stats1_2[1]."')");
mysql_query("DELETE FROM ".$pre."stats_archive WHERE views < 5 AND uniques = 1 AND name = 'page' OR page = 'referer' AND views < 5 AND uniques = 1");
mysql_query("DELETE FROM ".$pre."stats WHERE tday = '".$day."'");

mysql_query("OPTIMIZE TABLE ".$pre."stats");
mysql_query("OPTIMIZE TABLE ".$pre."stats_archive");
}
}

if ($do == "new_week") {
if (date("W") == 1) {
$year = date("Y") - 1;
$week = date("W",mktime(0,0,0,12,28,$year));
} else {
$year = date("Y");
$week = date("W") - 1;
}
if (date("j") == 1) {
$month = date("n") - 1;
} else {
$month = date("n");
}
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE week = '".$week."' AND year = '".$year."' AND name = 'week'")) == 0) {

$tot_views = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE year = '".$year."' AND week = '".$week."' AND name = 'day'"));
$tot_uniq = mysql_fetch_row(mysql_query("SELECT SUM(uniques) FROM ".$pre."stats_archive WHERE year = '".$year."' AND week = '".$week."' AND name = 'day'"));

if ($tot_views[0] > 0) {
mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'week' , '', '".$week."', '".$month."', '".$year."', '".$tot_views[0]."', '".$tot_uniq[0]."', '".time()."')");
mysql_query("DELETE FROM ".$pre."stats_archive WHERE name != 'week' AND name != 'day' AND name != 'month' AND name != 'year' AND week = '".$week."' AND year = '".$year."'  ORDER BY `views` DESC LIMIT 10,100000");

mysql_query("TRUNCATE `".$pre."stats`");
mysql_query("OPTIMIZE TABLE ".$pre."stats");
mysql_query("OPTIMIZE TABLE ".$pre."stats_archive");
}
}
}

if ($do == "new_month") {
if (date("W") == 1) {
$year = date("Y") - 1;
$month = 12;
} else {
$year = date("Y");
$month = date("n") - 1;
}

if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE month = '".$month."' AND year = '".$year."' AND name = 'month'")) == 0) {

$tot_views = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE year = '".$year."' AND month = '".$month."' AND name = 'week'"));
$tot_uniq = mysql_fetch_row(mysql_query("SELECT SUM(uniques) FROM ".$pre."stats_archive WHERE year = '".$year."' AND month = '".$month."' AND name = 'week'"));

if ($tot_views[0] > 0) {
mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'month' , '', 0, '".$month."', '".$year."', '".$tot_views[0]."', '".$tot_uniq[0]."', '".time()."')");
mysql_query("DELETE FROM ".$pre."stats_archive WHERE views < 5 AND uniques = 1 AND name = 'page' OR name = 'referer' AND views < 5 AND uniques = 1 OR name = 'keyword' AND views < 5 AND uniques = 1");

mysql_query("TRUNCATE `".$pre."stats`");
mysql_query("OPTIMIZE TABLE ".$pre."stats");
mysql_query("OPTIMIZE TABLE ".$pre."stats_archive");
}
}
}

if ($do == "new_year") {
$year = date("Y") - 1;

if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE year = '".$year."' AND name = 'year'")) == 0) {

$tot_views = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE year = '".$year."' AND name = 'month'"));
$tot_uniq = mysql_fetch_row(mysql_query("SELECT SUM(uniques) FROM ".$pre."stats_archive WHERE year = '".$year."' AND name = 'month"));

if ($tot_views[0] > 0) {
mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'year' , '', '', '', '".date("Y")."', '".$tot_views[0]."', '".$tot_uniq[0]."', '".time()."')");
mysql_query("DELETE FROM ".$pre."stats_archive WHERE views < 5 AND uniques = 1 AND name = 'page' OR name = 'referer' AND views < 5 AND uniques = 1 OR name = 'keyword' AND views < 5 AND uniques = 1");

mysql_query("TRUNCATE `".$pre."stats`");
mysql_query("OPTIMIZE TABLE ".$pre."stats");
mysql_query("OPTIMIZE TABLE ".$pre."stats_archive");
}
}
}
}

function url($type, $id = NULL, $var = NULL, $cat = NULL) {
global $pre;
global $siteurl;
global $setting;

if ($type == "profile") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/".fthrough($var, "", "");
} else {
$url = $siteurl."index.php?view=social&username=".$var;
}
} elseif($type == "page" && $var) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."page/".fthrough($var, "", $id)."/";
} else {
$url = $siteurl."index.php?view=pages&id=".$id;
}
} elseif($type == "section") {
if ($id) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."section/".fthrough($id, "", "")."/";
} else {
$url = $siteurl."index.php?view=section&section=".$id;
}
} else {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."archive/";
} else {
$url = $siteurl."index.php?view=content";
}
}
} elseif($type == "blogs" && $var && !$cat) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/".fthrough($var, "", "")."/blogs/";
} else {
$url = $siteurl."index.php?view=social&do=blogs&username=".$var;
}
} elseif($type == "blogs" && $cat) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/blogs/".fthrough($cat, "", $var)."/";
} else {
$url = $siteurl."index.php?view=social&do=blogs&id=".$cat;
}
} elseif($type == "status" && $var) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/".fthrough($var, "", "")."/status/";
} else {
$url = $siteurl."index.php?view=social&do=status&username=".$var;
}
} elseif($type == "status" && $id) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/status/".fthrough($id, "", "")."/";
} else {
$url = $siteurl."index.php?view=social&do=friends&username=".$id;
}
} elseif($type == "friends" && $var) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/".fthrough($var, "", "")."/friends/";
} else {
$url = $siteurl."index.php?view=social&do=friends&username=".$var;
}
} elseif($type == "friends" && !$var) {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."friends/";
} else {
$url = $siteurl."index.php?view=social&do=friends";
}
} elseif($type == "messages") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."messages/";
} else {
$url = $siteurl."index.php?view=social&do=messages";
}
} elseif($type == "blogs-add") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/blogs/add/";
} else {
$url = $siteurl."index.php?view=social&do=blogs&go=add";
}
} elseif($type == "edit-profile") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/edit/";
} else {
$url = $siteurl."index.php?view=social&do=edit";
}
} elseif($type == "edit-profile-2") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."profile/edit2/";
} else {
$url = $siteurl."index.php?view=social&do=edit2";
}
} elseif($type == "content") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."article/".fthrough($var, $cat, $id);
} else {
$url = $siteurl."index.php?view=content&id=".$id;
}
} elseif($type == "pages") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."pages/";
} else {
$url = $siteurl."index.php?view=pages";
}
} elseif($type == "login") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."login/";
} else {
$url = $siteurl."index.php?view=login";
}
} elseif($type == "logout") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."logout/";
} else {
$url = $siteurl."index.php?view=logout";
}
} elseif($type == "register") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."register/";
} else {
$url = $siteurl."index.php?view=register";
}
} elseif($type == "media") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."media/";
} else {
$url = $siteurl."index.php?view=media";
}
} elseif($type == "media-gallery") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."media/".fthrough($var, "", $id);
} else {
$url = $siteurl."index.php?view=media&do=gallery&id=".$id;
}
} elseif($type == "media-file") {
if (strtolower($setting["mod_rewrite"]) == "yes") {
$url = $siteurl."file/".fthrough($var, "", $id);
} else {
$url = $siteurl."index.php?view=media&do=file&id=".$id;
}
}

return $url;
}

function get_user($userid, $username = NULL) {
global $pre;
global $siteurl;

if (!$userid && !$username or $userid == 0 && !$username) {
return "<b>Guest</b>";
} else {
if ($username) {
$user = mysql_fetch_row(mysql_query("SELECT id,level FROM ".$pre."users WHERE username = '".$username."'"));
$userid = $user[0];
} elseif ($userid) {
$user = mysql_fetch_row(mysql_query("SELECT username,level FROM ".$pre."users WHERE id = '".$userid."'"));
$username = $user[0];
}
if (!$username) {
return "<b>Guest</b>";
} else {
$color = mysql_fetch_row(mysql_query("SELECT color FROM ".$pre."levels WHERE name = '".$user[1]."'"));

return "<a href='".url("profile", "", $username, "")."'><font color='".$color[0]."'><b>".$username."</b></font></a>";
}
}
}

function messages($type) {
	global $pre;
	global $useridn;

if ($type == "new") {
$messages = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE receiver_id = '".$useridn."' AND box != 'sent' AND viewed = 1"));
} else {
$messages = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE receiver_id = '".$useridn."' OR sender_id = '".$useridn."'"));
}

return $messages;
}

function reporterror($page, $error, $domain) {
global $email;
global $setting;

$sub = "[AdaptCMS] Error Reported";
$msg = "The error has been repoted from the ".$domain." website, below is the information on the error\n\nPage: ".$page."\nError: ".$error."\n\nThe error has also been reported to the error database. Thank you.";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$setting["sitename"]." <".$email.">"."\r\n";
$headers .= "Reply-To: ".$email."\r\n";

@mail("errors@adaptcms.com", $sub, $msg, $headers);
return "<iframe src='http://www.adaptcms.com/error.php?domain=".$domain."&error=".urlencode($error)."&page=".$page."' width='0' height='0' frameborder='0'></iframe>";
}

function re_direct($time, $url) {
return "<SCRIPT LANGUAGE='JavaScript'>
redirTime = '".$time."';
redirURL = '".$url."';
function redirTimer() { self.setTimeout('self.location.href = redirURL;',redirTime); }
</script><BODY onLoad='redirTimer()'>";
}

function clean($value) {
if (get_magic_quotes_gpc()) $value = stripslashes($value);

if (!is_numeric($value)) $value = @mysql_real_escape_string($value);

return $value;
}

function check($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   
   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A &#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
   
      // &#x0040 @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // &#00064 @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   
   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base', 'php');
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   //return $val;
   //return trim(strip_tags(mysql_real_escape_string($val), "<br><table><tr><td><p><a><font><strong><img><b><i><u><span><em><div><li><ul><ol><center><blockquote>"));
   return strip_tags($val, "<br><table><tr><td><p><a><font><strong><img><b><i><u><span><em><div><li><ul><ol><center><blockquote>");
} 

function check_domain($url) {	
	$info = parse_url($url);
	if ($info['host'] == "")
	$domain = $info['path'];
	else
	$domain = $info['host'];
	
	if (substr_count($domain, ".") == 1) {
 	$output = $domain;
	} else {
  	
	if (preg_match("#([^\.\/]*\.(co|biz|com|edu|gov|info|mil|net|org)\.(ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|bb|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw))#is", $domain, $output)) {
    $output = $output[1];
   	} else {
    $domain = explode(".", $domain);
    $output = $domain[count($domain)-2].".".$domain[count($domain)-1];
  	}
	}
	return $output;
}

function template($name) {
	global $skin;
	global $pre;
	global $sitedir;
	global $cookiename;
	
$sql = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."skins WHERE name = '".$name."' AND skin = '".$skin."'"));
if (!$sql) {
$skin1 = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."skins WHERE template = 'skin' ORDER BY `id` DESC"));
$skin = $skin1[0];
//setcookie($cookiename."skin", $skin, time()+3600*60*24*14, $sitedir);

$sql = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."skins WHERE name = '".$name."' AND skin = '".$skin."'"));
}
return $js.$sql[0];
}

function fthrough($var, $cat, $id) {
$var = rtrim(trim(stripslashes($var)));
$namepart = str_replace(" ", "-", $var);
$namepart = str_replace("--", "", $namepart);
$namepart = str_replace("_", "-", $namepart);
$namepart = str_replace("?", "", $namepart);
$namepart = str_replace("!", "", $namepart);
$namepart = str_replace(":", "", $namepart);
$namepart = str_replace("'", "", $namepart);
$namepart = str_replace("`", "", $namepart);
$namepart = str_replace("?", "", $namepart);
$namepart = str_replace("(", "", $namepart);
$namepart = str_replace(")", "", $namepart);
$namepart = str_replace("[", "", $namepart);
$namepart = str_replace("]", "", $namepart);
$namepart = str_replace("/", "-", $namepart);
$namepart = str_replace(",", "", $namepart);
$namepart = str_replace(".", "", $namepart);
$namepart = str_replace('"', "", $namepart);
$namepart = str_replace("'", "", $namepart);
$namepart = str_replace("?", "", $namepart);
$namepart = str_replace("@", "at", $namepart);
$namepart = str_replace("?", "", $namepart);
$namepart = str_replace("?", "", $namepart);
$namepart = str_replace("+", "plus", $namepart);
$namepart = str_replace("%", "", $namepart);
$namepart = str_replace("*", "", $namepart);
$namepart = str_replace("$", "", $namepart);
$namepart = str_replace("&", "and", $namepart);
$namepart = str_replace("#", "", $namepart);

$section = str_replace(" ", "-", ucwords(stripslashes($cat)));
$section = str_replace("--", "", $section);
$section = str_replace("_", "-", $section);
$section = str_replace(":", "", $section);
$section = str_replace("'", "", $section);
$section = str_replace("/", "-", $section);
$section = str_replace('"', "", $section);
$section = str_replace("'", "", $section);

if ($id) {
if ($cat) {
return $id."/".$section."/".$namepart."";
} else {
return $id."/".$namepart."";
}
} else {
return $namepart;
}
}

function watermark($SourceFile, $WatermarkFile, $SaveToFile = NULL) {
// copyright bokehman.com
    $watermark = @imagecreatefrompng($WatermarkFile) or exit('Cannot open the watermark file.');

    imageAlphaBlending($watermark, false);
    imageSaveAlpha($watermark, true);

    $image_string = @file_get_contents($SourceFile) or exit('Cannot open image file.');
    $image = @imagecreatefromstring($image_string) or exit('Not a valid image format.');

    $imageWidth=imageSX($image);
    $imageHeight=imageSY($image);

    $watermarkWidth=imageSX($watermark);
    $watermarkHeight=imageSY($watermark);

    $coordinate_X = ( $imageWidth - 5) - ( $watermarkWidth);
    $coordinate_Y = ( $imageHeight - 5) - ( $watermarkHeight);

    imagecopy($image, $watermark, $coordinate_X, $coordinate_Y, 0, 0, $watermarkWidth, $watermarkHeight);

    if(!($SaveToFile)) header('Content-Type: image/jpeg');

    imagejpeg ($image, $SaveToFile, 100);
    imagedestroy($image);
    imagedestroy($watermark);

    if(!($SaveToFile)) exit;
}

function createThumbs($pathToImages, $pathToThumbs, $fname) {
global $setting;

    $info = pathinfo($pathToImages.$fname);
    // continue only if this is a JPEG image
    if (strtolower($info['extension']) == 'jpg')
    {

      // load image and get image size
      $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
      $new_width = $setting["gallery_width"];
      //$new_height = floor( $height * ( $thumbWidth / $width ) );
	  $new_height = $setting["gallery_height"];

      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagejpeg($tmp_img, "{$pathToThumbs}{$fname}", 100);
    }

	if (strtolower($info['extension']) == 'png')
    {

      // load image and get image size
      $img = imagecreatefrompng("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
      $new_width = $setting["gallery_width"];
      //$new_height = floor( $height * ( $thumbWidth / $width ) );
	  $new_height = $setting["gallery_height"];

      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagepng($tmp_img, "{$pathToThumbs}{$fname}", 9);
    }

	if (strtolower($info['extension']) == 'gif')
    {

      // load image and get image size
      $img = imagecreatefromgif("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
      $new_width = $setting["gallery_width"];
      //$new_height = floor( $height * ( $thumbWidth / $width ) );
	  $new_height = $setting["gallery_height"];

      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagegif($tmp_img, "{$pathToThumbs}{$fname}");
    }
}
?>