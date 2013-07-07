<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Articles', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Article', null) ?>

<?php $this->TinyMce->editor() ?>
<?php if ($this->request->data['Article']['publish_time'] > date('Y-m-d H:i:s')): ?>
    <?php $time = strtotime($this->request->data['Article']['publish_time']) ?>
<?php endif ?>

<?= $this->Html->script('data-tagging.js') ?>
<?= $this->Html->css("data-tagging.css") ?>

<?= $this->Html->css("datepicker.css") ?>
<?= $this->Html->script('bootstrap-datepicker.js') ?>
<?= $this->Html->script('bootstrap-typeahead.js') ?>

<?= $this->Html->script('jquery.blockui.min.js') ?>
<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li<?= (empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
		<a href="#main" data-toggle="tab">Edit Article</a>
	</li>
	<li>
		<a href="#relate" data-toggle="tab">Relate Articles</a>
	</li>
	<?php if (!empty($comments)): ?>
		<li<?= (!empty($this->params['pass'][1]) ? " class='active'" : "") ?>>
			<a href="#comments-container" data-toggle="tab">Comments</a>
		</li>
	<?php endif ?>
	<div class="pull-right hidden-phone">
	    <?= $this->Html->link(
	        '<i class="icon-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="icon-trash icon-white"></i> Delete',
	        array('action' => 'delete', $this->request->data['Article']['id'], $this->request->data['Article']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this article?')"));
	    ?>
	</div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane<?= (empty($this->params['pass'][1]) ? " fade active in" : "") ?>" id="main">
        <?= $this->Form->create('Article', array('type' => 'file', 'class' => 'well admin-validate-article')) ?>

            <h2>
                Edit Article -
                <?= $this->request->data['Category']['title'] ?>
            </h2>

            <?= $this->Form->input('title', array(
                'type' => 'text', 
                'class' => 'required',
                'label' => 
                "<i class='icon icon-question-sign field-desc' data-content='This is the Title of your article, the name is also what its called.' data-title='Title'></i>&nbsp;Title"
            )) ?>
            
            <?php foreach($fields as $key => $field): ?>
                <?= $this->Element('FieldTypes/' . $field['FieldType']['slug'], array(
                    'key' => $key,
                    'field' => $field,
                    'icon' => !empty($field['Field']['description']) ? 
                    "<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
                )) ?>
                <?= $this->Form->hidden('ArticleValue.' . $key . '.field_id', array('value' => $field['Field']['id'])) ?>
            
                <?php if (!empty($field['ArticleValue'][0]['id'])): ?>
                    <?= $this->Form->hidden('ArticleValue.' . $key . '.id', array('value' => $field['ArticleValue'][0]['id'])) ?>
                <?php endif ?>
            <?php endforeach ?>

            <div id="text"></div>
            <div class="field_options">
                <?= $this->Form->input('field_options', array(
                    'div' => false, 
                    'type' => 'text',
                    'label' => "<i class='icon icon-question-sign field-desc' data-content='Tagging an article with a keyword, will let you see a list of those articles. So if you tag 3 articles with <strong>xbox</strong>, you can then go to site.com/tag/xbox and see all articles with the xbox tag.' data-title='Tags'></i>&nbsp;Tags"
                )) ?>
                <?= $this->Form->button('Add', array(
                    'class' => 'btn btn-info', 
                    'type' => 'button',
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

            <div id="current-status">
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

            <div class="input text publish_time">
                <?= $this->Form->input('publishing_date', array(
                    'label' => 'Publish Time',
                    'div' => false,
                    'class' => 'input-small datepicker',
                    'value' => date('Y-m-d', !empty($time) ? $time : time()),
                    'data-date-format' => 'yyyy-mm-dd'
                )) ?>

                <?= $this->Form->input('publishing_time', array(
                    'label' => false,
                    'div' => false,
                    'class' => 'input-mini',
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

            <?= $this->Form->hidden('id') ?>
            <?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
            <?= $this->Form->hidden('status', array('value' => $this->request->data['Article']['status'])) ?>
            <?= $this->Form->hidden('category_id', array('value' => $category_id)) ?>

            <?= $this->Form->button('Publish Now', array(
                'type' => 'submit',
                'class' => 'btn btn-primary'
            )) ?>
            <?= $this->Form->button( ($this->request->data['Article']['status'] == 0 ? 'Keep As' : 'Save') . ' Draft', array(
                'type' => 'submit',
                'class' => 'btn btn-danger draft'
            )) ?>
            <?= $this->Form->button('<i class="icon-calendar"></i> Publish Later', array(
                'class' => 'btn btn-success',
                'type' => 'button',
                'escape' => false
            )) ?>

        <?= $this->Form->end(); ?>
    </div>

    <div class="tab-pane" id="relate">
        <?= $this->Form->create('RelatedArticle', array('action' => 'ajax_add', 'class' => 'well')) ?>
            <div id="flashMessageRelated" class="alert alert-success"></div>

            <h2 class="pull-left">
                Relate Articles
            </h2>
            <h4 class="pull-left">
                <i class="icon icon-question-sign field-desc" data-content="Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page." data-title="Related Articles" data-placement="right"></i>
            </h4>
            <div class="clearfix"></div>

            <div class="pull-left" style="margin-bottom: 20px">
                <?= $this->Form->input('category', array(
                    'id' => 'category',
                    'div' => false,
                    'label' => false,
                    'class' => 'input-medium',
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
                                    <?= $row['Article']['title'] ?> (<?= $row['Category']['title'] ?>) <a href="#" class="icon-white icon-remove-sign"></a>
                                </span>

                                <input type="hidden" id="RelatedData[]" class="related" name="RelatedData[]" value="<?= $row['Article']['id'] ?>">
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="clearfix"></div>

            <?= $this->Form->button('Update', array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'id' => 'related-submit'
            )) ?>
        <?= $this->Form->end() ?>
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