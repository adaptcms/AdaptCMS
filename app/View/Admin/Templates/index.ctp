<?= $this->Html->script('bootstrap-typeahead.js') ?>

<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', null) ?>

<h1>Appearance Settings</h1>

<div id="theme-update-div"></div>

<?= $this->Form->create('Setting', array('class' => 'well', 'onsubmit' => 'return false')) ?>
    <?= $this->Form->input('theme', array(
        'options' => $default_theme_options,
        'label' => 'Default Theme',
        'value' => $current_theme['data']
    )) ?>
    <?= $this->Form->hidden('theme_id', array('value' => $current_theme['id'])) ?>
    <?= $this->Form->button('Submit', array('id' => 'theme-update')) ?>
<?= $this->Form->end() ?>

<div class="left">
    <h1>Themes<?php if (!empty($this->request->named['trash_theme'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View <i class="icon-picture"></i>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu view">
    <li>
        <?= $this->Html->link('<i class="icon-ok"></i> Active', array(
            'admin' => true, 
            'action' => 'index'
        ), array('escape' => false)) ?>
    </li>
    <li>
        <?= $this->Html->link('<i class="icon-trash"></i> Trash', array(
            'admin' => true, 
            'action' => 'index', 
            'trash_theme' => 1,
	        'trash_temp' => (!empty($this->request->named['trash_temp']) ? 1 : 0)
        ), array('escape' => false)) ?>
    </li>
  </ul>
</div>
<div class="clear"></div>

<?= $this->Html->link('Add Theme <i class="icon icon-plus icon-white"></i>', array(
        'controller' => 'themes', 
        'action' => 'add'
    ), array(
        'class' => 'btn btn-info pull-right', 
        'style' => 'margin-bottom:10px',
        'escape' => false
    )
) ?>

<?php if (empty($this->request->data['Themes'])): ?>
	<div class="clearfix"></div>
	<div class="well">
		No Themes Found.
	</div>
<?php else: ?>
	<table id="templates-list" class="table table-striped">
	    <thead>
	        <tr>
	            <th style="width: 50%">Title</th>
	            <th class="hidden-phone">Created</th>
	            <th>Theme Version</th>
	            <th></th>
	        </tr>
	    </thead>

	    <tbody>
	        <?php foreach ($this->request->data['Themes'] as $data): ?>
	            <?php if ($data['Theme']['title'] == 'Default'): ?>
	                <tr>
	                    <td>
	                        Default
	                        <a class="btn btn-small btn-info refresh" id="1" href="#" style="float:right">
	                            <i class="icon-refresh icon-white"></i>
	                        </a>
	                    </td>
	                    <td></td>
	                    <td class="hidden-phone"></td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <li>
	                                    <?= $this->Admin->edit(1, 'themes') ?>
	                                </li>
	                            </ul>
	                        </div>
	                    </td>
	                </tr>
	            <?php else: ?>
	                <?php if (!empty($data['Data']['data'])): ?>
	                    <?php if ($data['Data']['data']['current_version'] == $data['Data']['current_version']): ?>
	                        <tr class="theme-info" data-title="<?= $data['Data']['data']['title'] ?>" data-content="Theme is up to date">
	                    <?php else: ?>
	                        <tr class="theme-info" data-title="<?= $data['Data']['data']['title'] ?>" data-content="Theme is out of date, newest version is <?= $data['Data']['data']['current_version'] ?>">
	                    <?php endif ?>
	                <?php else: ?>
	                    <tr>
	                <?php endif ?>
	                    <td>
	                        <?= $data['Theme']['title'] ?>
	                        <?php if (!empty($data['Theme']['id'])): ?>
	                            <a class="btn btn-small btn-info refresh" id="<?= $data['Theme']['id'] ?>" href="#<?= $data['Theme']['title'] ?>" style="float:right">
	                                <i class="icon-refresh icon-white"></i>
	                            </a>
	                        <?php endif ?>

	                        <?php if (!empty($data['Data']['data'])): ?>
	                            <br />
	                            <p class="span10 no-marg-left">
	                                <?= $data['Data']['data']['short_description'] ?>
	                            </p>

	                            <div class="clearfix"></div>
	                            <?php if (!empty($data['Data']['data']['author_url'])): ?>
	                                <?= $this->Html->link(
	                                    $data['Data']['data']['author_name'],
	                                    $data['Data']['data']['author_url'],
	                                    array('target' => '_blank')) ?> |
	                            <?php endif ?>

	                            <?= $this->Html->link('Theme Page',
	                                $this->Api->url() . 'theme/' . $data['Data']['data']['slug'],
	                                array('target' => '_blank')
	                            ) ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?php if (!empty($data['Theme']['id'])): ?>
	                            <?= $this->Admin->time($data['Theme']['created']) ?>
	                        <?php elseif(isset($data['Data']['status']) && $data['Data']['status'] == 0): ?>
	                            Not yet Installed
	                        <?php endif ?>
	                    </td>
	                    <?php if (!empty($data['Data']['data'])): ?>
	                        <?php if ($data['Data']['data']['current_version'] == $data['Data']['current_version']): ?>
	                            <td class="hidden-phone">
	                                <?=$data['Data']['current_version'] ?>
	                                <i class="icon icon-ok"></i>
	                            </td>
	                        <?php else: ?>
	                            <td class="hidden-phone">
	                                <?= $data['Data']['current_version'] ?>
	                                <i class="icon icon-ban-circle"></i>
	                                <div class="clearfix"></div>

	                                <?= $this->Html->link('Download Latest Version <i class="icon icon-white icon-download-alt"></i>',
	                                    $this->Api->url() . 'theme/' . $data['Data']['data']['slug'],
	                                    array('target' => '_blank', 'class' => 'btn btn-primary pull-right', 'escape' => false)
	                                ) ?>
	                            </td>
	                        <?php endif ?>
	                    <?php elseif (!empty($data['Data']['current_version'])): ?>
	                        <td class="hidden-phone">
	                            <?= $data['Data']['current_version'] ?>
	                        </td>
	                    <?php else: ?>
	                        <td class="hidden-phone">N/A</td>
	                    <?php endif ?>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (!empty($data['Data'])): ?>
	                                    <?php if ($data['Data']['status'] == 0): ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="icon-plus"></i> Install',
	                                                array(
	                                                    'admin' => false,
	                                                    'controller' => 'install',
	                                                    'action' => 'install_theme',
	                                                    $data['Theme']['title']
	                                                ),
	                                                array(

	                                                    'escape' => false
	                                                ))
	                                            ?>
	                                        </li>
	                                    <?php else: ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="icon-trash"></i> Uninstall',
	                                                array(
	                                                    'admin' => false,
	                                                    'controller' => 'install',
	                                                    'action' => 'uninstall_theme',
	                                                    $data['Theme']['title']
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ))
	                                            ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if (!empty($data['Data']['upgrade'])): ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="icon-upload"></i> Upgrade',
	                                                array(
	                                                    'admin' => false,
	                                                    'controller' => 'install',
	                                                    'action' => 'upgrade_theme',
	                                                    $data['Theme']['title']
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ));
	                                            ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php endif ?>
	                                <?php if (empty($this->request->named['trash_theme'])): ?>
	                                    <?php if (!empty($data['Theme']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Theme']['id'],
	                                                'themes'
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if (empty($data['Data'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Theme']['id'],
	                                                $data['Theme']['title'],
	                                                false,
	                                                'themes'
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php elseif (empty($data['Data'])): ?>
	                                    <li>
	                                        <?= $this->Admin->restore(
	                                            $data['Theme']['id'],
	                                            $data['Theme']['title'],
	                                            'themes'
	                                        ) ?>
	                                    </li>
	                                    <li>
	                                        <?= $this->Admin->remove(
	                                            $data['Theme']['id'],
	                                            $data['Theme']['title'],
	                                            true,
	                                            'themes'
	                                        ) ?>
	                                    </li>
	                                <?php endif ?>
	                            </ul>
	                        </div>
	                    </td>
	                </tr>
	            <?php endif ?>
	        <?php endforeach ?>
	    </tbody>
	</table>
	<div class="clearfix"></div>
<?php endif ?>

<div class="pull-left">
    <h1>Templates<?php if (!empty($this->request->named['trash_temp'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group pull-right" style="margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View <i class="icon-picture"></i>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu view">
    <li>
        <?= $this->Html->link('<i class="icon-ok"></i> Active', array(
            'admin' => true, 
            'action' => 'index'
        ), array('escape' => false)) ?>
    </li>
    <li>
        <?= $this->Html->link('<i class="icon-trash"></i> Trash', array(
            'admin' => true, 
            'action' => 'index', 
            'trash_temp' => 1,
	        'trash_theme' => (!empty($this->request->named['trash_theme']) ? 1 : 0)
        ), array('escape' => false)) ?>
    </li>
  </ul>
</div>
<div class="clearfix"></div>

<div class="pull-left">
Search
<?= $this->Form->input('theme', array(
        'div' => false,
        'label' => false,
        'empty' => '- Theme -',
        'options' => $themes_dropdown,
        'style' => 'width: 150px;margin-right: 10px'
)) ?>
<?= $this->Form->input('search', array(
        'div' => false,
        'label' => false,
        'data-provide' => 'typeahead', 
        'data-source' => '[]', 
        'autocomplete'=>'off'
)) ?>
</div>
<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            Add Template
        <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($themes_dropdown as $theme_id => $theme): ?>
                <li>
                    <?= $this->Html->link($theme, array(
                        'controller' => 'templates',
                        'action' => 'add',
                        $theme_id
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
            Filter by Theme
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($themes_dropdown as $theme_id => $theme): ?>
                <li>
                    <?= $this->Html->link($theme, array(
                        'theme_id' => $theme_id
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php if (empty($this->request->data['Template'])): ?>
    <div class="clearfix"></div>
    <div class="well">
        No Templates Found. If you just installed AdaptCMS, refresh the default theme -
        <a class="btn btn-small btn-info refresh" id="1" href="#">
            <i class="icon-refresh icon-white"></i>
        </a>
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('label') ?></th>
                <th><?= $this->Paginator->sort('Theme.title', 'Theme') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('location', 'Folder') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this->request->data['Template'] as $data): ?>
	            <tr>
	                <td>
	                    <?= $this->Html->link($data['Template']['label'], array('controller' => 'templates', 'action' => 'edit', $data['Template']['id'])); ?>
	                </td>
	                <td>
	                    <?php if ($data['Template']['theme_id'] > 0): ?>
	                        <?= $data['Theme']['title'] ?>
	                    <?php endif; ?>
	                </td>
	                <td class="hidden-phone">
	                    /<?= str_replace(basename($data['Template']['location']), "",$data['Template']['location']) ?>
	                </td>
	                <td class="hidden-phone">
	                    <?= $this->Admin->time($data['Template']['created']) ?>
	                </td>
	                <td>
	                    <div class="btn-group">
	                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                            Actions
	                            <span class="caret"></span>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <?php if (empty($this->request->named['trash_temp'])): ?>
	                                <li>
	                                    <?= $this->Admin->edit(
	                                        $data['Template']['id']
	                                    ) ?>
	                                </li>
	                                <li>
	                                    <?= $this->Admin->remove(
	                                        $data['Template']['id'],
	                                        $data['Template']['label']
	                                    ) ?>
	                                </li>
	                            <?php else: ?>
	                                <li>
	                                    <?= $this->Admin->restore(
	                                        $data['Template']['id'],
	                                        $data['Template']['label']
	                                    ) ?>
	                                </li>
	                                <li>
	                                    <?= $this->Admin->remove(
	                                        $data['Template']['id'],
	                                        $data['Template']['label'],
	                                        true
	                                    ) ?>
	                                </li>
	                            <?php endif ?>
	                        </ul>
	                    </div>
	                </td>
	            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<?= $this->element('admin_pagination') ?>