<?php $this->Html->addCrumb('Sample Items', array('action' => 'index')) ?>
<?php $this->Html->addCrumb($this->request->data['Sample']['title'], null) ?>

<?php $this->set('title_for_layout', $this->request->data['Sample']['title']) ?>

<h1>
	<?= $this->request->data['Sample']['title'] ?>
</h1>

<?= $this->request->data['Sample']['text'] ?>

Test Helper - <?= $this->Sample->test() ?>