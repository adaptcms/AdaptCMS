<?php
echo header('Content-type: application/rss+xml');
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>

<?php
if (!isset($channel)) {
	$channel = array();
}
if (!isset($channel['title'])) {
	$channel['title'] = $title_for_layout;
}

echo $this->Rss->document(
	$this->Rss->channel(
		array(), 
		$channel, $this->fetch('content')
	)
);
?>