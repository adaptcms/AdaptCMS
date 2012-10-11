<?php
App::uses('Sanitize', 'Utility');

$this->set('channel', array(
	'title' => 'testing',
	'description' => 'desc',
	'language' => 'en-us',
	'atom:link' => array(
		'attrib' => array(
			'rel' => 'self',
			'type' => 'application/rss+xml',
			'href' => $this->html->url('/' .$this->here, true)
		)
	)
));
$this->set('test', 1);
?>

<?php foreach($this->request->data as $data): ?>
	<?php $link = array('controller' => 'articles', 'action' => 'view', $data['Article']['slug']) ?>
	<?php $description = "No Description" ?>
	
<?= $this->Rss->item(array(), array(
		'title' => $data['Article']['title'],
		'link' => $link,
		'guid' => array('url' => $link, 'isPermaLink' => 'true'),
		'description' => $description,
		'pubDate' => $data['Article']['created']
	)) ?>

<?php endforeach ?>