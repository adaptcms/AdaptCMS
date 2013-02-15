<?= $this->Form->create('Search', array(
	'url' => array(
		'action' => 'search', 
		'controller' => 'search',
		'plugin' => false,
		'admin' => false
	),
	'class' => 'form-search'
)) ?>
	<?= $this->Form->input('q', array(
		'label' => false,
		'placeholder' => 'Enter Keyword...',
		'div' => false,
		'style' => 'margin-top: 0'
	)) ?>
	<?= $this->Form->input('module', array(
		'empty' => 'All',
		'options' => ( !empty($options) ? $options : array() ),
		'label' => false,
		'div' => false,
		'class' => 'span2',
		'style' => 'margin-top: 0'
	)) ?>
	<?= $this->Form->button('Search', array(
		'class' => 'btn btn-primary',
		'div' => false
	)) ?>
<?= $this->Form->end() ?>