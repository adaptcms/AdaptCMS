<?php $this->Html->addCrumb($category['title'], null) ?>

<?php $this->set('title_for_layout', $category['title']) ?>

<h1><?= $category['title'] ?></h1>

<?php if (empty($article)): ?>
	<p>No Articles Found</p>
<?php else: ?>
	<?php foreach($article as $item): ?>
		<div class="span8 no-marg-left">
			<?= $this->Html->link('<h2>' . $item['Article']['title'] . '</h2>', array(
				'controller' => 'articles',
				'action' => 'view',
				'slug' => $item['Article']['slug'],
				'id' => $item['Article']['id']
			), array('escape' => false)) ?>

			<p class="lead">
				@ <em><?= $this->Admin->time($item['Article']['created']) ?></em>
			</p>

			<blockquote><?= $this->Field->getTextAreaData($item) ?></blockquote>

			<div id="post-options">
		        <span class="pull-left">
		            <?= $this->Html->link('Read More', array(
			            'controller' => 'articles',
			            'action' => 'view',
			            'slug' => $item['Article']['slug'],
			            'id' => $item['Article']['id']
		            ), array('class' => 'btn btn-primary')) ?>
			        <span style="margin-left: 10px">
		                <i class="icon icon-comment"></i>&nbsp;
				        <?= $this->Html->link($item['Comments'] . ' Comments', array(
					        'controller' => 'articles',
					        'action' => 'view',
					        'slug' => $item['Article']['slug'],
					        'id' => $item['Article']['id']
				        )) ?>
		            </span>
		            <span style="margin-left: 10px">
		                <i class="icon-user"></i>&nbsp;
		                Posted by <?= $this->Html->link($item['User']['username'], array('controller' => 'users', 'action' => 'profile', $item['User']['username'])) ?>
		            </span>
		        </span>
		        <span class="pull-right">
		        	<?php if (!empty($item['Article']['tags'])): ?>
				        <?php foreach($item['Article']['tags'] as $tag): ?>
					        <?= $this->Html->link('<span class="btn btn-success">'.$tag.'</span>', array('controller' => 'articles', 'action' => 'tag', $tag), array('class' => 'tags', 'escape' => false)) ?>
				        <?php endforeach ?>
			        <?php endif ?>
		        </span>
			</div>
		</div>

		<div class="clearfix"></div>

		<hr>
	<?php endforeach ?>
<?php endif ?>

<?= $this->element('pagination') ?>