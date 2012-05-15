<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

if ($_GET['q']) {
$_GET['q'] = str_replace("-"," ",check($_GET['q']));
content('search', check($_GET['section']), $setting["section_limit"], 1, $_GET['q']);
} else {
echo "<form action='".$siteurl."index.php' method='get'><input type='hidden' name='view' value='search'><input type='text' name='q' class='title'> <select name='section' class='select'><option value='' selected>- Section -</option>";
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}
echo "</select> <input type='submit' value='Search' class='addContent-button'></form>";
}
?>