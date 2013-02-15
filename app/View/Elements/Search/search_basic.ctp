<?= $this->Form->create('Search', array(
	'url' => array(
		'action' => 'search', 
		'controller' => 'search',
		'plugin' => false,
		'admin' => false
	),
	'class' => 'navbar-search pull-right', 
	'style' => 'margin-right: 15px'
)) ?>
	<?= $this->Form->input('q', array(
		'label' => false,
		'placeholder' => 'Enter Keyword...',
		'div' => false,
		'style' => 'margin-bottom: 0'
	)) ?>
	<?= $this->Form->button('Search', array(
		'class' => 'btn',
		'div' => false,
		'style' => 'margin-top: 0'
	)) ?>
	<?= $this->Form->hidden('module', array(
		'value' => 1
	)) ?>
<?= $this->Form->end() ?>