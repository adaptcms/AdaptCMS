<?php
$html = $content;

$temps['pagetitle'] = ($temps['pagetitle']) ? $temps['pagetitle'].' - powered by AdaptBB' : $default_title.' - powered by AdaptBB';

$temps['sitename'] = $setting['sitename'];
$temps['skin'] = $skin;
$temps['siteurl'] = $siteurl;

$temps['version'] = "AdaptBB ".$version;

foreach ($temps as $key => $value) {
	$search[] = '{'.$key.'}';
	$replace[] = $value;
}
$html = str_replace($search, $replace, $html);

echo $html;
?>