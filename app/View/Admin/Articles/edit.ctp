<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Articles', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Article - ' . $this->request->data['Category']['title'], null) ?>

<?php $this->TinyMce->editor() ?>
<?php if ($this->request->data['Article']['publish_time'] > date('Y-m-d H:i:s')): ?>
    <?php $time = strtotime($this->request->data['Article']['publish_time']) ?>
<?php endif ?>

<?= $this->Html->css("data-tagging") ?>
<?= $this->Html->css("datepicker") ?>

<?= $this->Html->script('data-tagging') ?>
<?= $this->Html->script('bootstrap-datepicker') ?>
<?= $this->Html->script('bootstrap-typeahead') ?>

<?= $this->Html->script('jquery.blockui.min') ?>
<?= $this->Html->script('jquery.smooth-scroll.min') ?>
<?= $this->Html->script('admin.files') ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('media_modal') ?>

<?php $this->start('admin_header_top_right') ?>
	<div class="pull-left">
		<div class="pull-left quick-save-date">
			Last Saved: <span class="last-saved"><?php echo $this->request->data['Article']['last_saved'] ?></span>
		</div>
		<?= $this->Form->button('Quick Save <i class="fa fa-refresh"></i>', array(
			'type' => 'button',
			'escape' => false,
			'class' => 'btn btn-info navbar-btn pull-left quick-save'
		)) ?>
		<?= $this->Form->input('auto_save', array(
			'div' => false,
			'class' => 'col-xs-3 navbar-btn',
			'label' => false,
			'empty' => 'auto',
			'options' => array(
				'60000' => '1 Min',
				'120000' => '2 Mins',
				'300000' => '5 Mins',
				'600000' => '10 Mins'
			)
		)) ?>
	</div>
<?php $this->end() ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li<?= (empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
		<a href="#main" data-toggle="tab">Edit Article</a>
	</li>
	<li>
		<a href="#relate" data-toggle="tab">Relate Articles</a>
	</li>
	<li>
		<a href="#revisions" data-toggle="tab">Revisions</a>
	</li>
	<?php if (!empty($comments)): ?>
		<li<?= (!empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
			<a href="#comments-container" data-toggle="tab">Comments</a>
		</li>
	<?php endif ?>
	<div class="pull-right hidden-xs">
	    <?= $this->Html->link(
	        '<i class="fa fa-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn btn-default', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="fa fa-trash-o"></i> Delete',
	        array('action' => 'delete', $this->request->data['Article']['id'], $this->request->data['Article']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this article?')"));
	    ?>
	</div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane<?= (empty($this->params['pass'][1]) ? " fade active in" : "") ?>" id="main">
        <?= $this->Form->create('Article', array('type' => 'file', 'class' => 'well admin-validate-article', 'ng-app' => 'images')) ?>
            <h2>
                Edit Article -
                <?= $this->request->data['Category']['title'] ?>
            </h2>

            <?= $this->Form->input('title', array(
                'type' => 'text', 
                'class' => 'required',
                'label' => 
                "<i class='fa fa-question-circle field-desc' data-content='This is the Title of your article, the name is also what its called.' data-title='Title'></i>&nbsp;Title"
            )) ?>

            <div ng-controller="ImageModalCtrl">
	            <?php foreach($fields as $key => $field): ?>
	                <?= $this->Element('FieldTypes/' . $field['FieldType']['slug'], array(
	                    'key' => $key,
	                    'field' => $field,
	                    'icon' => !empty($field['Field']['description']) ?
	                    "<i class='fa fa-question-circle field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
	                )) ?>
	                <?= $this->Form->hidden('ArticleValue.' . $key . '.field_id', array('value' => $field['Field']['id'])) ?>
	                <?= $this->Form->hidden('ArticleValue.' . $key . '.required', array('value' => $field['Field']['required'])) ?>

	                <?php if (!empty($field['ArticleValue'][0]['id'])): ?>
	                    <?= $this->Form->hidden('ArticleValue.' . $key . '.id', array('value' => $field['ArticleValue'][0]['id'])) ?>
	                <?php endif ?>
	            <?php endforeach ?>
	            <?= $this->element('media_modal', array('disable_parsing' => true)) ?>
            </div>

            <div id="text"></div>
		    <div class="field_options input-group col-lg-5">
			    <?= $this->Form->label('field_options', "<i class='fa fa-question-circle field-desc' data-content='Tagging an article with a keyword, will let you see a list of those articles. So if you tag 3 articles with <strong>xbox</strong>, you can then go to site.com/tag/xbox and see all articles with the xbox tag.' data-title='Tags'></i>&nbsp;Tags") ?>
			    <div class="clearfix"></div>

			    <?= $this->Form->input('field_options', array(
				    'label' => false,
				    'div' => false,
				    'class' => 'form-control form-control-inline'
			    )) ?>
			    <?= $this->Form->button('Add', array(
				    'type' => 'button',
				    'class' => 'btn btn-info',
				    'id' => 'add-data'
			    )) ?>
		    </div>
            <div id="field_data"></div>
            <div id="field_existing_data">
                <?php if (!empty($this->request->data['Article']['tags'])): ?>
                    <?php foreach($this->request->data['Article']['tags'] as $row): ?>
                        <span><?= $row ?></span>
                    <?php endforeach ?>
                <?php endif ?>
            </div>

            <div class="clearfix"></div>

            <?= $this->Form->input('Article.settings.comments_status', array(
                'options' => array(
                    'open' => 'Open',
                    'closed' => 'Closed'
                ),
                'value' => (!empty($this->request->data['Article']['settings']['comments_status']) ? $this->request->data['Article']['settings']['comments_status'] : '')
            )) ?>

            <div id="current-status" class="col-lg-12 no-pad-l clearfix">
                <label>Current Status</label>

                <?php if ($this->request->data['Article']['status'] == 0): ?>
                    <div class="alert alert-block current-status">
                            Article is Saved as a Draft, NOT live
                    </div>
                <?php elseif (!empty($time)): ?>
                    <div class="alert alert-info current-status">
                        Article will go Live - 
                        <?= $this->Admin->Time($this->request->data['Article']['publish_time'], "F d, Y h:i a") ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success current-status">
                        Article is Live
                    </div>
                <?php endif ?>
            </div>

	        <?= $this->Element('Articles/admin_permissions') ?>
	        <?= $this->Element('Articles/admin_media') ?>

            <div class="input text publish_time col-lg-8 no-pad-l">
	            <?= $this->Form->label('publishing_date', 'Publish Time') ?>

	            <div>
	                <?= $this->Form->input('publishing_date', array(
	                    'label' => false,
	                    'div' => false,
	                    'class' => 'col-xs-2 datepicker',
	                    'value' => date('Y-m-d', !empty($time) ? $time : time()),
	                    'data-date-format' => 'yyyy-mm-dd'
	                )) ?>

	                <?= $this->Form->input('publishing_time', array(
	                    'label' => false,
	                    'div' => false,
	                    'class' => 'col-xs-2',
	                    'value' => date('g:i A', !empty($time) ? $time : time())
	                )) ?>

	                <span class="hidden date_ymd"><?= date('Y-m-d') ?></span>
	                <span class="hidden time_gia"><?= date('g:i A') ?></span>

	                <?php if (!empty($time)): ?>
	                    <span class="hidden show_publish_time">1</span>
	                <?php endif ?>

	                <?= $this->Form->button('Submit', array(
	                    'type' => 'submit',
	                    'class' => 'btn btn-primary'
	                )) ?>
	            </div>
            </div>

            <?= $this->Form->hidden('id') ?>
            <?= $this->Form->hidden('old_data') ?>
            <?= $this->Form->hidden('status', array('value' => $this->request->data['Article']['status'])) ?>
            <?= $this->Form->hidden('category_id', array('value' => $category_id)) ?>

            <div class="publish_options col-lg-11 no-pad-l">
                <?= $this->Form->button('Publish Now', array(
                    'type' => 'submit',
                    'class' => 'btn btn-primary'
                )) ?>
                <?= $this->Form->button( ($this->request->data['Article']['status'] == 0 ? 'Keep As' : 'Save') . ' Draft', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger draft'
                )) ?>
                <?= $this->Form->button('<i class="fa fa-calendar"></i> Publish Later', array(
                    'class' => 'btn btn-success',
                    'type' => 'button',
                    'escape' => false
                )) ?>
                <?= $this->Form->button('Preview <i class="fa fa-picture-o"></i>', array(
	                'type' => 'button',
	                'escape' => false,
	                'class' => 'btn btn-primary pull-right preview-modal'
                )) ?>
            </div>
	        <div class="clearfix"></div>
        <?= $this->Form->end() ?>
    </div>

    <div class="tab-pane" id="relate">
        <?= $this->Form->create('RelatedArticle', array('action' => 'ajax_add', 'class' => 'well')) ?>
            <div id="flashMessageRelated" class="alert alert-success"></div>

            <h2 class="pull-left">
                Relate Articles
            </h2>
            <h4 class="pull-left">
                <i class="fa fa-question-circle field-desc" data-content="Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page." data-title="Related Articles" data-placement="right"></i>
            </h4>
            <div class="clearfix"></div>

            <div class="pull-left col-lg-8 no-pad-l" style="margin-bottom: 20px">
                <?= $this->Form->input('category', array(
                    'id' => 'category',
                    'div' => false,
                    'label' => false,
                    'class' => 'col-xs-3',
                    'empty' => '- Category -',
                    'options' => $categories
                )) ?>
                <?= $this->Form->input('related-search', array(
                    'id' => 'related-search',
                    'div' => false,
                    'label' => false,
                    'data-provide' => 'typeahead', 
                    'data-source' => '[]', 
                    'autocomplete'=>'off'
                )) ?>

                <span class="related-error alert alert-error"></span>

                <div id="related-articles">
                    <?php if (!empty($related_articles['all'])): ?>
                        <?php foreach($related_articles['all'] as $row): ?>
                            <div id="data-<?= $row['Article']['id'] ?>">
                                <span class="label label-info">
                                    <?= $row['Article']['title'] ?> (<?= $row['Category']['title'] ?>) <a href="#" class="fa fa-times fa-white"></a>
                                </span>

                                <input type="hidden" id="RelatedData[]" class="related" name="RelatedData[]" value="<?= $row['Article']['id'] ?>">
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="clearfix"></div>
        <?= $this->Form->end() ?>
    </div>
    <div class="tab-pane well" id="revisions">
		<h2>Revisions</h2>

		<?php if (empty($this->request->data['ArticleRevision'])): ?>
	        <p>There are no saved revisions for this article.</p>
	    <?php else: ?>
	        <table class="table">
		        <thead>
		            <th>Date</th>
		            <th>Type</th>
		            <th>By</th>
		            <th>Most Recent?</th>
		            <th></th>
		        </thead>
		        <tbody>
		            <?php foreach($this->request->data['ArticleRevision'] as $revision): ?>
						<tr>
							<td><?php echo $this->Admin->time($revision['created']) ?></td>
							<td><?php echo $revision_types[$revision['type']] ?></td>
							<td>
								<?php echo $this->Html->link($revision['User']['username'], array(
										'controller' => 'users',
										'action' => 'edit',
										$revision['User']['id']
									), array('target' => '_blank')
								) ?>
							</td>
							<td>
								<?php if ($revision['active'] == 1): ?>
				                    <strong>Yes</strong>
			                    <?php else: ?>
				                    No
				                <?php endif ?>
							</td>
							<td>
								<?= $this->Html->link('Restore <i class="fa fa-undo"></i>', array(
									$this->request->data['Article']['id'],
									'#' => 'revisions',
									'?' => array('restore_revision' => $revision['id'])
								), array('escape' => false, 'class' => 'btn btn-info btn-confirm', 'data-new-title' => 'Are you sure you wish to restore this revision?')) ?>
								<?= $this->Form->button('Preview <i class="fa fa-picture-o"></i>', array(
									'type' => 'button',
									'escape' => false,
									'class' => 'btn btn-primary pull-right preview-modal',
									'data-query' => 'revision-' . $revision['id']
								)) ?>
								<span class="hidden" id="revision-<?php echo $revision['id'] ?>"><?php echo htmlentities($revision['data']) ?></span>
							</td>
						</tr>
					<?php endforeach ?>
		        </tbody>
	        </table>
	    <?php endif ?>
    </div>

    <?php if (!empty($comments)): ?>
        <div class="tab-pane<?= (!empty($this->params['pass'][1]) ? " fade active in" : "") ?>" id="comments-container">
            <?= $this->Form->create('Comment', array(
                'url' => array(
                    'controller' => 'comments',
                    'action' => 'edit',
                    $this->request->data['Article']['id']
                ),
                'class' => 'well comments-form'
            )) ?>

                <h2>Comments</h2>

                <?= $this->Form->input('all', array(
                    'div' => array(
                            'class' => 'pull-right'
                    ),
                    'class' => 'check-all',
                    'label' => 'Check All',
                    'type' => 'checkbox',
                    'checked' => false
                )) ?>
                <div class="clearfix"></div>

                <div id="comments">
                    <?php foreach($comments as $i => $comment): ?>
                        <?php $id = $comment['Comment']['id'] ?>

                        <div class="pull-left comment clearfix">
                            <h4>
                                @ <?= $this->Time->format('F jS, Y h:i A', $comment['Comment']['created']) ?>
                                by
                                <?php if (!empty($comment['User']['id'])): ?>
                                    <?= $this->Html->link($comment['User']['username'], array(
                                        'controller' => 'users',
                                        'action' => 'edit',
                                        $comment['User']['id']
                                        ), array('target' => '_blank')
	                                ) ?>
                                <?php else: ?>
                                    <span class="field-desc" data-content="
                                    IP: <?= $comment['Comment']['author_ip'] ?><br />
                                    <?php if (!empty($comment['Comment']['author_name'])): ?>
                                            <?= $comment['Comment']['author_name'] ?><br />
                                    <?php endif ?>
                                    <?php if (!empty($comment['Comment']['author_email'])): ?>
                                            <?= $comment['Comment']['author_email'] ?><br />
                                    <?php endif ?>
                                    <?php if (!empty($comment['Comment']['author_website'])): ?>
                                            <?= $this->Html->link($comment['Comment']['author_website'], $comment['Comment']['author_website'], array('target' => '_blank')) ?><br />
                                    <?php endif ?>
                                    " data-title="Comment Info">
                                            Guest
                                    </span>
                                <?php endif ?>
                            </h4>

                            <?= $this->Form->input($i . '.Comment.comment_text', array(
                                'class' => 'wysiwyg required',
                                'style' => 'width:45%;height:120px',
                                'value' => $comment['Comment']['comment_text'],
                                'label' => false
                            )) ?>

                            <?= $this->Form->input($i . '.Comment.active', array(
                                'type' => 'checkbox',
                                'value' => 1,
                                'checked' => ($comment['Comment']['active'] == 1 ? 'checked' : '')
                            )) ?>

                            <?= $this->Form->hidden($i . '.Comment.id', array('value' => $id)) ?>
                        </div>
                    <?php endforeach ?>
                    <div class="clearfix"></div>

                    <div class="btn-group pull-right" style="font-size: 1em;">
                        <span style="margin-right: 10px;">
                            Showing <?= $cur_limit ?> of <?= $comments_count ?> total
                        </span>

                        <?php if (!empty($new_comments_limit)): ?>
                            <?= $this->Html->link('Load ' . $new_comments_amount . ' More...', array(
                                $this->request->data['Article']['id'],
                                'limit' => $new_comments_limit
                            ), array('class' => 'load-more')) ?>
                        <?php endif ?>
                    </div>
                </div>

                <?= $this->Form->submit('Update Comments', array('class' => 'btn')) ?>
            <?= $this->Form->end() ?>
        </div>
    <?php endif ?>
</div>

<?= $this->Element('Articles/admin_preview') ?>