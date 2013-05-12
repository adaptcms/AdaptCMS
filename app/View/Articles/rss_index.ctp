<?php
$this->set('channel', array(
	'atom:link' => array(
		'attrib' => array(
			'rel' => 'self',
			'type' => 'application/rss+xml',
			'href' => $this->Html->url('', true)
		)
	),
	'title' => 'testing',
	'description' => 'desc',
	'language' => 'en-us'
));
?>

<?php foreach($this->request->data as $data): ?>
	<?php $link = array(
		'controller' => 'articles', 
		'action' => 'view', 
		'slug' => $data['Article']['slug'],
		'id' => $data['Article']['id']
	) ?>
	
<?= $this->Rss->item(array(), array(
		'title' => $data['Article']['title'],
		'link' => $link,
		'guid' => array('url' => $link, 'isPermaLink' => 'true'),
		'description' => $this->Field->getTextAreaData($data),
		'pubDate' => $data['Article']['created']
	)) ?>

<?php endforeach ?>