<?php
$smarty->display($skin.'/admin_header.tpl');

if (!$_GET['do']) {
echo "<link rel='stylesheet' type='text/css' media='all' href='inc/js/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
<link rel='stylesheet' type='text/css' media='all' href='inc/js/validate.css' />
<script type='text/javascript' src='inc/js/calendar/calendar.js'></script>
<script type='text/javascript' src='inc/js/calendar/lang/calendar-en.js'></script>
<script type='text/javascript' src='inc/js/calendar/calendar-setup.js'></script>
<script type='text/javascript' src='inc/js/validate.js'></script>";
echo '<script language="javascript">function jump(targ,selObj,restore){
eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
if (restore) selObj.selectedIndex=0;
}</script>';

echo "<form name='form1' action='admin.php' method='get'><input type='hidden' name='view' value='stats'><br />";

echo "<b>Select Date:</b>&nbsp;&nbsp;Day <input type='hidden' name='day' id='f_date_e' /><img
src=\"inc/js/calendar/img.gif\" id=\"f_trigger_e\" style=\"cursor: pointer; border: 1px solid
red;\" title=\"Date selector\" onmouseover=\"this.style.background='red';\"
onmouseout=\"this.style.background=''\" />

<script type='text/javascript'>
    Calendar.setup({
        inputField     :    'f_date_e',     // id of the input field
        ifFormat       :    '%j/%Y',     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    'show_e',       // ID of the span where the date is to be shown
        daFormat       :    '%A, %B %d, %Y - %I:%M %p',// format of the displayed date
        button         :    'f_trigger_e',  // trigger button (well, IMG in our case)
        align          :    'Tl',           // alignment (defaults to 'Bl')
        singleClick    :    true
    });
</script>&nbsp;&nbsp;<input type='submit' value='Go'>&nbsp;&nbsp;Week <select name='week' class='input' onChange=\"jump('parent',this,0)\"><option value=''></option><option value='admin.php?view=stats&week=".str_replace("0","",date("W"))."&year=".date("Y")."&type=week'>Week ".date("W (M) - Y")."</option>";
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'week' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='admin.php?view=stats&week=".$r[week]."&year=".$r[year]."&type=week'>Week ".$r[week]." (".date("M", $r[date]).") - ".$r[year]."</option>";
}
echo "</select>&nbsp;&nbsp;Month <select name='month' class='input' onChange=\"jump('parent',this,0)\"><option value=''></option><option value='admin.php?view=stats&month=".str_replace("0","",date("m"))."&year=".date("Y")."&type=month'>".date("F Y")."</option>";
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'month' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='admin.php?view=stats&month=".$r[month]."&year=".$r[year]."&type=month'>(".date("F", $r[date]).") ".$r[year]."</option>";
}
echo "</select>&nbsp;&nbsp;Year <select name='year' class='input' onChange=\"jump('parent',this,0)\"><option value=''></option><option value='admin.php?view=stats&year=".date("Y")."&type=year'>".date("Y")."</option>";
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'year' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='admin.php?view=stats&year=".$r[year]."&type=year'>".$r[year]."</option>";
}
echo "</select><br /><br />";

echo "<table cellspacing='0' cellpadding='5' border='0' align='center' width='100%'><tr><td><a href='admin.php?view=stats'><u>Stats</u></a></td><td><a href='admin.php?view=stats&do=page'>[Pages]</a></td><td><a href='admin.php?view=stats&do=referer'>[Referrals]</a></td><td><a href='admin.php?view=stats&do=keyword'>[Keywords]</a></td><td><a href='admin.php?view=stats&do=bot'>[Bots]</a></td><td><a href='admin.php?view=stats&do=operating_system'>[Operating Systems]</a></td><td><a href='admin.php?view=stats&do=browser'>[Browsers]</a></td></tr></table><br />";

if (!$_GET['type'] && !$_GET['day']) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(visits_num) FROM ".$pre."stats WHERE tday = '".date("z")."' AND year = '".date("Y")."' AND visit_type = 'view'"));
$uniques = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE tday = '".date("z")."' AND year = '".date("Y")."' AND visit_type = 'unique'"));

echo "<center><h2>Stats for - ".date("m/d/y")."</h2></center><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td>Basic Stats</td><td></td></tr><tr><td><b>Views</b></td><td>".$views[0]."</td></tr><tr><td><b>Uniques</b></td><td>".$uniques."</td></tr></table></form>";
} elseif ($_GET['day']) {
$ex = explode("/", $_GET['day']);
$ex[0] = $ex[0] - 1;

$views = mysql_fetch_row(mysql_query("SELECT views,uniques,date FROM ".$pre."stats_archive WHERE data = '".$ex[0]."' AND year = '".$ex[1]."' AND name = 'day'"));
$views[2] = $views[2] - 10800;
$where = " AND week = '".date("W", $views[2])."' AND year = '".$ex[1]."'";
if (!$views[0] && !$views[1]) {
echo "<center>No data for this day</center>";
} else {
echo "<center><h2>Stats for - ".date("m/d/y", $views[2])."</h2></center><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td>Basic Stats</td><td></td></tr><tr><td><b>Views</b></td><td>".$views[0]."</td></tr><tr><td><b>Uniques</b></td><td>".$views[1]."</td></tr></table></form>";
}
} elseif ($_GET['type'] == "week") {
if ($_GET['week']."/".$_GET['year'] == date("W/Y")) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(views),SUM(uniques),month FROM ".$pre."stats_archive WHERE week = '".$_GET['week']."' AND year = '".$_GET['year']."' AND name = 'day'"));
$stats1_1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE visit_type = 'unique'"));
$stats1_2 = mysql_fetch_row(mysql_query("SELECT SUM(visits_num) FROM ".$pre."stats WHERE visit_type = 'view'"));
$where = " AND week = '".$_GET['week']."' AND year = '".$_GET['year']."'";

$views[0] = $stats1_1 + $views[0];
$views[1] = $stats1_2[0] + $views[1];
} else {
$views = mysql_fetch_row(mysql_query("SELECT views,uniques,month FROM ".$pre."stats_archive WHERE week = '".$_GET['week']."' AND year = '".$_GET['year']."' AND name = 'week'"));
}
if (!$views[0]) {
echo "<center>No data for this week</center>";
} else {
echo "<center><h2>Stats for - Week ".$_GET['week']." ".date("F Y", mktime(0, 0, 0, $views[2], 15, $_GET['year']))."</h2></center><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td>Basic Stats</td><td></td></tr><tr><td><b>Views</b></td><td>".$views[0]."</td></tr><tr><td><b>Uniques</b></td><td>".$views[1]."</td></tr></table></form>";
}

} elseif ($_GET['type'] == "month") {
if ($_GET['month']."/".$_GET['year'] == date("n/Y")) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(views),SUM(uniques) FROM ".$pre."stats_archive WHERE month = '".$_GET['month']."' AND year = '".$_GET['year']."' AND name = 'week'"));
if (!$views[0]) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(views),SUM(uniques) FROM ".$pre."stats_archive WHERE month = '".$_GET['month']."' AND year = '".$_GET['year']."' AND name = 'day'"));
}
$where = " AND month = '".$_GET['month']."' AND year = '".$_GET['year']."'";

$stats1_1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE visit_type = 'unique'"));
$stats1_2 = mysql_fetch_row(mysql_query("SELECT SUM(visits_num) FROM ".$pre."stats WHERE visit_type = 'view'"));

$views[0] = $stats1_2[0] + $views[0];
$views[1] = $stats1_1 + $views[1];
} else {
$views = mysql_fetch_row(mysql_query("SELECT views,uniques FROM ".$pre."stats_archive WHERE month = '".$_GET['month']."' AND year = '".$_GET['year']."' AND name = 'month'"));
}
if (!$views[0]) {
echo "<center>No data for this month</center>";
} else {
echo "<center><h2>Stats for - ".date("F Y", mktime(0, 0, 0, $_GET['month'], 15, $_GET['year']))."</h2></center><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td>Basic Stats</td><td></td></tr><tr><td><b>Views</b></td><td>".$views[0]."</td></tr><tr><td><b>Uniques</b></td><td>".$views[1]."</td></tr></table></form>";
}
} elseif ($_GET['type'] == "year") {
if ($_GET['year'] == date("Y")) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(views),SUM(uniques) FROM ".$pre."stats_archive WHERE year = '".$_GET['year']."' AND name = 'month'"));
if (!$views[0]) {
$views = mysql_fetch_row(mysql_query("SELECT SUM(views),SUM(uniques) FROM ".$pre."stats_archive WHERE year = '".$_GET['year']."' AND name = 'week'"));
}
$where = " AND year = '".$_GET['year']."'";

$stats1_1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE visit_type = 'unique'"));
$stats1_2 = mysql_fetch_row(mysql_query("SELECT SUM(visits_num) FROM ".$pre."stats WHERE visit_type = 'view'"));

$views[0] = $stats1_2[0] + $views[0];
$views[1] = $stats1_1 + $views[1];
} else {
$views = mysql_fetch_row(mysql_query("SELECT views,uniques FROM ".$pre."stats_archive WHERE year = '".$_GET['year']."' AND name = 'year'"));
}
if (!$views[0]) {
echo "<center>No data for this year</center>";
} else {
echo "<center><h2>Stats for - ".$_GET['year']."</h2></center><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td>Basic Stats</td><td></td></tr><tr><td><b>Views</b></td><td>".$views[0]."</td></tr><tr><td><b>Uniques</b></td><td>".$views[1]."</td></tr></table></form><br /><br />";
}
}

echo "<br /><br /><table width='100% cellpadding='5' cellspacing='3' align='center'><tr><td valign='top'>";

echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Pages</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'page'".$where." ORDER BY `views` DESC LIMIT 10");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$names .= $r[data].",";
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
echo "<tr><td><a href='".$r[data]."'>";
$r[data] = str_replace($siteurl,"/",$r[data]);
if (strlen($r[data]) > 64) {
echo substr(urldecode($r[data]), 0, 61)."...";
} else {
echo substr(urldecode($r[data]), 0, 64);
}
echo "</a></td><td>".number_format($num[0])."</td></tr>";
}
}
echo "</table></td><td valign='top'><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Referrals</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'referer'".$where." ORDER BY `views` DESC LIMIT 10");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
$num1 = 1;
echo "<tr><td>";
if (stristr($r[data], $siteurl) === FALSE) {
if (file_exists("http://www.".check_domain($r[data])."/favicon.ico")) {
echo "<img src='http://www.".check_domain($r[data])."/favicon.ico'> ";
}
}
echo "<a href='".$r[data]."'>";
$r[data] = str_replace($siteurl,"/",$r[data]);
if (strlen($r[data]) > 64) {
echo substr(urldecode($r[data]), 0, 61)."...";
} else {
echo substr(urldecode($r[data]), 0, 64);
}
echo "</a></td><td>".number_format($num[0])."</td></tr>";
$names .= $r[data].",";
}
}
if (!$num1) {
echo "<tr><td>No Referrals</td><td></td></tr>";
}
echo "</table></td><td valign='top'><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Keywords</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'keyword'".$where." ORDER BY `views` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
$num2 = 1;
echo "<tr><td>".$r[data]."</td><td>".number_format($num[0])."</td></tr>";
$names .= $r[data].",";
}
}
if (!$num2) {
echo "<tr><td>No Keywords</td><td></td></tr>";
}
echo "</table></td></tr><tr style='padding-top:5px'><td valign='top'>";


echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Browsers</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'browser'".$where." ORDER BY `views` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
echo "<tr><td>";
if (stristr($r[data], "firefox")) {
echo "<img src='".$siteurl."inc/images/firefox.png'> ";
} elseif (stristr($r[data], "chrome")) {
echo "<img src='".$siteurl."inc/images/chrome.png'> ";
} elseif (stristr($r[data], "ie")) {
echo "<img src='".$siteurl."inc/images/ie.png'> ";
} elseif (stristr($r[data], "opera")) {
echo "<img src='".$siteurl."inc/images/opera.png'> ";
} elseif (stristr($r[data], "safari")) {
echo "<img src='".$siteurl."inc/images/safari.png'> ";
} elseif (stristr($r[data], "maxthon")) {
echo "<img src='".$siteurl."inc/images/maxthon.png'> ";
} elseif (stristr($r[data], "traveler")) {
echo "<img src='".$siteurl."inc/images/traveler.png'> ";
} elseif (stristr($r[data], "theworld")) {
echo "<img src='".$siteurl."inc/images/theworld.png'> ";
} elseif (stristr($r[data], "netscape")) {
echo "<img src='".$siteurl."inc/images/netscape.png'> ";
}
echo $r[data]."</td><td>".number_format($num[0])."</td></tr>";
$names .= $r[data].",";
}
}
echo "</table></td><td valign='top'><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Operating Systems</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'operating_system'".$where." ORDER BY `views` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
echo "<tr><td>";
if (stristr($r[data], "vista")) {
echo "<img src='".$siteurl."inc/images/vista.png'> ";
} elseif (stristr($r[data], "ubuntu")) {
echo "<img src='".$siteurl."inc/images/ubuntu.png'> ";
} elseif (stristr($r[data], "windows")) {
echo "<img src='".$siteurl."inc/images/windows.png'> ";
} elseif (stristr($r[data], "linux")) {
echo "<img src='".$siteurl."inc/images/linux.png'> ";
} elseif (stristr($r[data], "mac")) {
echo "<img src='".$siteurl."inc/images/mac.png'> ";
} elseif (stristr($r[data], "fedora")) {
echo "<img src='".$siteurl."inc/images/fedora.png'> ";
}
echo $r[data]."</td><td>".number_format($num[0])."</td></tr>";
$names .= $r[data].",";
}
}
echo "</table></td><td valign='top'><table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td align='center'><b>Top Bots</b></td><td></td></tr>";
unset($num, $names);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'bot'".$where." ORDER BY `views` DESC LIMIT 5");
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));
$num2 = 1;
echo "<tr><td>".$r[data]."</td><td>".number_format($num[0])."</td></tr>";
$names .= $r[data].",";
}
}
if (!$num2) {
echo "<tr><td>No Bots</td><td></td></tr>";
}
echo "</table></td></tr>";

echo "</table>";
}



if ($_GET['do']) {
$showname = str_replace("_"," ",ucfirst($_GET['do']));
echo "<table cellspacing='0' cellpadding='0' border='0' align='center' width='100%'><tr><td><a href='admin.php?view=stats'><u>Stats</u></a></td><td><a href='admin.php?view=stats&do=page'>[Pages]</a></td><td><a href='admin.php?view=stats&do=referer'>[Referrals]</a></td><td><a href='admin.php?view=stats&do=keyword'>[Keywords]</a></td><td><a href='admin.php?view=stats&do=bot'>[Bots]</a></td><td><a href='admin.php?view=stats&do=operating_system'>[Operating Systems]</a></td><td><a href='admin.php?view=stats&do=browser'>[Browsers]</a></td></tr></table><br />";

echo "<center><h2>".$showname." Stats</h2></center>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

echo "<table cellspacing='0' cellpadding='5' border='0' align='center' width='100%' style='border: 2px solid #dddddd'><tr class='light'><td>Name</td><td>Views</td><td>Percent</td></tr>";
$tot = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE name = '".$_GET['do']."'"));
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = '".$_GET['do']."' ORDER BY `data` ASC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (stristr($names, $r[data]) === FALSE) {
$num = mysql_fetch_row(mysql_query("SELECT SUM(views) FROM ".$pre."stats_archive WHERE data = '".$r[data]."'"));

@$diff = $num[0] / $tot[0];
$diff = substr($diff, 2, 2);
if ($diff{0} == "0") {
$diff = str_replace("0", "", $diff);
}
if (strlen($diff) == "3") {
$diff = substr($diff, 0, 2);
}
if (!$diff) {
$diff = 0;
}
echo "<tr><td>";
if ($_GET['do'] == "operating_system") {
if (stristr($r[data], "vista")) {
echo "<img src='".$siteurl."inc/images/vista.png'> ";
} elseif (stristr($r[data], "ubuntu")) {
echo "<img src='".$siteurl."inc/images/ubuntu.png'> ";
} elseif (stristr($r[data], "windows")) {
echo "<img src='".$siteurl."inc/images/windows.png'> ";
} elseif (stristr($r[data], "linux")) {
echo "<img src='".$siteurl."inc/images/linux.png'> ";
} elseif (stristr($r[data], "mac")) {
echo "<img src='".$siteurl."inc/images/mac.png'> ";
} elseif (stristr($r[data], "fedora")) {
echo "<img src='".$siteurl."inc/images/fedora.png'> ";
}
} elseif ($_GET['do'] == "browser") {
if (stristr($r[data], "firefox")) {
echo "<img src='".$siteurl."inc/images/firefox.png'> ";
} elseif (stristr($r[data], "chrome")) {
echo "<img src='".$siteurl."inc/images/chrome.png'> ";
} elseif (stristr($r[data], "ie")) {
echo "<img src='".$siteurl."inc/images/ie.png'> ";
} elseif (stristr($r[data], "opera")) {
echo "<img src='".$siteurl."inc/images/opera.png'> ";
} elseif (stristr($r[data], "safari")) {
echo "<img src='".$siteurl."inc/images/safari.png'> ";
} elseif (stristr($r[data], "maxthon")) {
echo "<img src='".$siteurl."inc/images/maxthon.png'> ";
} elseif (stristr($r[data], "traveler")) {
echo "<img src='".$siteurl."inc/images/traveler.png'> ";
} elseif (stristr($r[data], "theworld")) {
echo "<img src='".$siteurl."inc/images/theworld.png'> ";
} elseif (stristr($r[data], "netscape")) {
echo "<img src='".$siteurl."inc/images/netscape.png'> ";
}
} elseif ($_GET['do'] == "referer") {
if (stristr($r[data], $siteurl) === FALSE) {
if (file_exists("http://www.".check_domain($r[data])."/favicon.ico")) {
echo "<img src='http://www.".check_domain($r[data])."/favicon.ico'> ";
}
}
}

if ($_GET['do'] == "page" or $_GET['do'] == "referer") {
echo "<a href='".$r[data]."'>";
$r[data] = str_replace($siteurl,"/",$r[data]);
if (strlen($r[data]) > 64) {
echo substr(urldecode($r[data]), 0, 61)."...";
} else {
echo substr(urldecode($r[data]), 0, 64);
}
echo "</a>";
} else {
echo $r[data];
}
echo "</td><td>".number_format($num[0])."</td><td>".$diff."%</td></tr>";
$names .= $r[data].",";
}
}
echo "</table><br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."stats_archive WHERE name = '".$_GET['do']."'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);
if ($total_pages > "1") {
echo "<center>";
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=stats&do=".$_GET['do']."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=stats&do=".$_GET['do']."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=stats&do=".$_GET['do']."&page=$next\">Next>></a>";
}
echo "</center>";
}
}
?>