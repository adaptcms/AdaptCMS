<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Articles', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Article', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<?= $this->Html->css("datepicker.css") ?>
<?= $this->Html->script('bootstrap-datepicker.js') ?>
<?= $this->Html->script('bootstrap-typeahead.js') ?>

<?= $this->Form->create('Article', array('type' => 'file', 'class' => 'well admin-validate-article')) ?>
    <h2>Add Article</h2>

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
    <?php endforeach ?>

    <div id="text"></div>
    <div class="field_options">
        <?= $this->Form->input('field_options', array(
            'div' => false, 
            'label' => 
            "<i class='icon icon-question-sign field-desc' data-content='Tagging an article with a keyword, will let you see a list of those articles. So if you tag 3 articles with <strong>xbox</strong>, you can then go to site.com/tag/xbox and see all articles with the xbox tag.' data-title='Tags'></i>&nbsp;Tags"
        )) ?>
        <?= $this->Form->button('Add', array(
            'type' => 'button',
            'class' => 'btn btn-info', 
            'id' => 'add-data'
        )) ?>
    </div>
    <div id="field_data"></div>
    <div class="clearfix"></div>

    <label class="related-label">
        <i class="icon icon-question-sign field-desc" data-content="Linking another article to this one will allow you to show its data on this Articles page. Ex. Halo 5 Game linking to your Halo 5 preview, you can then show Halo 5 Game Details on the preview page." data-title="Related Articles"></i>
        &nbsp;Relate Articles
    </label>

    <div class="pull-left">
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

        <div id="related-articles"></div>
    </div>
    <div class="clearfix"></div>

    <?= $this->Form->input('Article.settings.comments_status', array(
            'options' => array(
                    'open' => 'Open',
                    'closed' => 'Closed'
            )
    )) ?>

    <div class="input text publish_time">
        <?= $this->Form->input('publishing_date', array(
            'label' => 'Publish Time',
            'div' => false,
            'class' => 'input-small datepicker',
            'value' => date('Y-m-d'),
            'data-date-format' => 'yyyy-mm-dd'
        )) ?>

        <?= $this->Form->input('publishing_time', array(
            'label' => false,
            'div' => false,
            'class' => 'input-mini',
            'value' => date('g:i A')
        )) ?>

        <div class="hidden date_ymd"><?= date('Y-m-d') ?></div>
        <div class="hidden time_gia"><?= date('g:i A') ?></div>

        <?= $this->Form->button('Submit', array(
                'type' => 'submit',
                'class' => 'btn btn-primary'
        )) ?>
    </div>

    <?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
    <?= $this->Form->hidden('status', array('value' => 0)) ?>
    <?= $this->Form->hidden('category_id', array('value' => $category_id)) ?>

    <div class="publish_options">
        <?= $this->Form->button('Publish Now', array(
                'type' => 'submit',
                'class' => 'btn btn-primary'
        )) ?>
        <?= $this->Form->button('Save Draft', array(
                'type' => 'submit',
                'class' => 'btn btn-danger draft'
        )) ?>
        <?= $this->Form->button('<i class="icon-calendar"></i> Publish Later', array(
                'type' => 'button',
                'class' => 'btn btn-success',
                'escape' => false
        )) ?>
    </div>
<?= $this->Form->end() ?>