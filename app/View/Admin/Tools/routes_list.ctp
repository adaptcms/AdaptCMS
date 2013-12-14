<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Routes List', null) ?>

<h1>Routes List</h1>

<div class="well">
	<p>
		Where &#36;array is defined, this references an array of that particular data. (such as user data for the 'user_profile' route) You can also specify a php array with
		proper key/values.
	</p>

	<?php foreach($routes as $key => $row): ?>
		<div class="">
			<h4><?= Inflector::humanize($key) ?></h4>
			<dl class="dl-horizontal">
				<dt>Example URL</dt>
				<dd>
					<?php if (empty($row['params'])): ?>
						<?= $this->View->url($key) ?>
					<?php else: ?>
						<?php if (!isset($row['params'][0]) || $row['params'][0] != 0): ?>
							<?php $params = $row['params'] ?>
						<?php elseif(isset($row['params'][0]) && $row['params'][0] == '0'): ?>
							<?php $params = array(0 => ':slug') ?>
						<?php else: ?>
							<?php $params = array() ?>
							<?php foreach($row['params'] as $param): ?>
								<?php $params[] = ':' . $param ?>
							<?php endforeach ?>
						<?php endif ?>

						<?= urldecode($this->View->url($key, $params)) ?>
					<?php endif ?>
				</dd>
				<dt>Example Code</dt>
				<dd>
					<?php if (empty($row['params'])): ?>
						<?php echo str_replace($find, $replace, "{ { url ('" . $key . "') } }") ?>
					<?php elseif(!empty($row['key'])): ?>
						<?php echo str_replace($find, $replace, "{ { url ('" . $key . "', &#36;array) } }") ?>
					<?php else: ?>
						<?php echo str_replace($find, $replace, "{ { url ('" . $key . "', array('" . $params[0] . "')) } }") ?>
					<?php endif ?>
				</dd>
			</dl>
		</div>
	<?php endforeach ?>
</div>