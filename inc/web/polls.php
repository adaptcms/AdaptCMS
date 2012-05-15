<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

echo poll($_GET['id']);
?>