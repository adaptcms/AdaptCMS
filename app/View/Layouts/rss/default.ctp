<!-- <?= '<?xml version="1.0" encoding="UTF-8"?>' ?> -->

<?php
if (!isset($channel)) {
	$channel = array();
}
if (!isset($channel['title'])) {
	$channel['title'] = $title_for_layout;
}

echo $this->Rss->document(
	$this->Rss->channel(
		array('xmlns:atom' => 'http://www.w3.org/2005/Atom'), 
		$channel, $this->fetch('content')
	)
);
?>