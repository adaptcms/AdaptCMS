<?php if (empty($modules)): ?>
	<?php if (!empty($q)): ?>
		<div class="search-results" id="module-<?= $module_id ?>">
			<h2 class="pull-left">
				Search for 
				<small>
					<?= $q ?>
				</small>
				in <?= $module_name ?>
			</h2>
			<span class="pull-right" style="margin-top: 20px">
				<strong>
					<?= $this->Paginator->counter('{:count}') ?>
				</strong>
				Total Result(s)
			</span>

			<div class="clearfix"></div>

			<div class="well">
				<?php if (!empty($data)): ?>
					<ul>
						<?php foreach($data as $row): ?>
							<?= $this->element($model, array('data' => $row)) ?>
						<?php endforeach ?>
					</ul>
				<?php else: ?>
					No Results
				<?php endif ?>
			</div>
			
			<?= $this->element('admin_pagination') ?>
		</div>
	<?php endif ?>
<?php else: ?>
	<span id="q"><?= $q ?></span>
	<span id="modules"><?= $modules ?></span>

	<div class="search-loading">
		<?= $this->element('flash_notice', array('message' => 'Loading Search Results...')) ?>
	</div>
	<div class="search-container"></div>
<?php endif ?>