<?= $this->Form->create('Search', array(
	'url' => array(
		'action' => 'search',
		'controller' => 'search',
		'plugin' => false,
		'admin' => false
	),
	'class' => 'navbar-form col-lg-4 pull-left'
)) ?>
	<div class="col-lg-8 input-group">
		<?= $this->Form->input('q', array(
			'label' => false,
			'placeholder' => 'Enter Keyword!...',
			'div' => false,
			'class' => 'form-control'
		)) ?>
		<span class="input-group-btn">
			<?= $this->Form->button('Search', array(
				'class' => 'btn',
				'div' => false
			)) ?>
		</span>
	</div>
	<?= $this->Form->hidden('module', array(
		'value' => 1
	)) ?>
<?= $this->Form->end() ?>