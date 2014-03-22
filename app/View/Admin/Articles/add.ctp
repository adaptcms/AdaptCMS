<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Articles', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Article - ' . $category['Category']['title'], null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Html->css("data-tagging") ?>
<?= $this->Html->css("datepicker") ?>

<?= $this->Html->script('data-tagging') ?>
<?= $this->Html->script('bootstrap-datepicker') ?>
<?= $this->Html->script('bootstrap-typeahead') ?>
<?= $this->Html->script('admin.files') ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('media_modal') ?>

<?= $this->Form->create('Article', array('type' => 'file', 'class' => 'well admin-validate-article', 'ng-app' => 'images')) ?>
    <h2>Add Article</h2>

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
	        'class' => 'form-control form-control-inline',
        )) ?>
        <?= $this->Form->button('Add', array(
            'type' => 'button',
            'class' => 'btn btn-info',
            'id' => 'add-data'
        )) ?>
    </div>
    <div id="field_data"></div>
	<div class="clearfix"></div>

    <label class="related-label pull-left">
        <i class="fa fa-question-circle field-desc" data-content="Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page." data-title="Related Articles"></i>
        &nbsp;Relate Articles
    </label>

    <div class="related-articles col-lg-8">
        <?= $this->Form->input('category', array(
                'id' => 'category',
                'div' => false,
                'label' => false,
                'class' => 'col-xs-4',
                'empty' => '- Category -',
                'options' => $categories
        )) ?>
        <?= $this->Form->input('related-search', array(
                'id' => 'related-search',
                'div' => false,
                'label' => false,
                'data-provide' => 'typeahead',
//	            'class' => 'col-xs-4',
                'data-source' => '[]', 
                'autocomplete'=>'off'
        )) ?>

        <span class="related-error alert alert-error"></span>

        <div id="related-articles"></div>
    </div>
    <div class="clearfix"></div>

    <?= $this->Form->input('Article.settings.comments_status', array(
        'options' => array(
            'open' => 'Open',
            'closed' => 'Closed'
        )
    )) ?>

	<?= $this->Element('Articles/admin_permissions') ?>
	<?= $this->Element('Articles/admin_media') ?>
	<div class="clearfix"></div>

    <div class="col-lg-8 no-pad-l input text publish_time">
	    <?= $this->Form->label('publishing_date', 'Publish Time') ?>

	    <div>
	        <?= $this->Form->input('publishing_date', array(
	            'label' => false,
	            'div' => false,
	            'class' => 'col-xs-2 datepicker',
	            'value' => date('Y-m-d'),
	            'data-date-format' => 'yyyy-mm-dd'
	        )) ?>

	        <?= $this->Form->input('publishing_time', array(
	            'label' => false,
	            'div' => false,
	            'class' => 'col-xs-2',
	            'value' => date('g:i A')
	        )) ?>

	        <div class="hidden date_ymd"><?= date('Y-m-d') ?></div>
	        <div class="hidden time_gia"><?= date('g:i A') ?></div>

	        <?= $this->Form->button('Submit', array(
	                'type' => 'submit',
	                'class' => 'btn btn-primary'
	        )) ?>
	    </div>
    </div>

    <?= $this->Form->hidden('status', array('value' => 0)) ?>
    <?= $this->Form->hidden('category_id', array('value' => $category_id)) ?>

    <div class="col-lg-12 no-pad-l publish_options">
        <?= $this->Form->button('Publish Now', array(
                'type' => 'submit',
                'class' => 'btn btn-primary'
        )) ?>
        <?= $this->Form->button('Save Draft', array(
                'type' => 'submit',
                'class' => 'btn btn-danger draft'
        )) ?>
        <?= $this->Form->button('<i class="fa fa-calendar"></i> Publish Later', array(
                'type' => 'button',
                'class' => 'btn btn-success',
                'escape' => false
        )) ?>
    </div>
	<div class="clearfix"></div>
<?= $this->Form->end() ?>