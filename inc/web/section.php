<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

echo content('section', check($_GET['section']), $setting["section_limit"], 1);
?>