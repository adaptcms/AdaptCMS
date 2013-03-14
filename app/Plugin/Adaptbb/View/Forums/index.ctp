<?php $this->Html->addCrumb('Forums', null) ?>

<h1>Forum Index</h1>

<div class="span11">
	<?php foreach($categories as $category): ?>
		<h2>
			<?= $this->Html->link($category['ForumCategory']['title'], array(
				'action' => 'view',
				'controller' => 'forum_categories',
				$category['ForumCategory']['slug']
			)) ?>
		</h2>

		<?php if (!empty($category['Forum'])): ?>
			<ul>		
				<?php foreach($category['Forum'] as $forum): ?>
					<li>
						<?= $this->Html->link($forum['title'], array(
							'action' => 'view',
							$forum['slug']
						)) ?>
					</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
		<div class="clearfix"></div>
	<?php endforeach ?>
</div>