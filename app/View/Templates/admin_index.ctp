<?= $this->Html->script('bootstrap-typeahead.js') ?>

<script type="text/javascript">
 $(document).ready(function(){
    $("#theme-update").live('click', function() {
        var theme = $("#SettingTheme").val();
        var setting_id = $("#SettingThemeId").val();
        var theme_name = $("#SettingTheme option:selected").text();

        $.post("<?= $this->webroot ?>ajax/templates/theme_update/", 
            {
                data:{
                    Setting:{
                        data: theme,
                        id: setting_id,
                        title: theme_name
                    }
                }
            }, function(data) {
            if ($("#theme-update-div").length != 0) {
                $("#theme-update-div").replaceWith(data);
            } else {
                $(data).insertBefore("#SettingAdminIndexForm");
            }
        });
    });

    $(".refresh.btn-info").live('click', function(e) {
        var theme_id = $(this).attr('id');
        var theme_name = $(this).attr('href').replace("#","");
        e.preventDefault();

        $.post("<?= $this->webroot ?>ajax/templates/theme_refresh/",
            {
                data:{
                    Theme:{
                        id: theme_id,
                        name: theme_name
                    }
                }
            }, function(data) {
                if ($("#theme-update-div").length != 0) {
                    $("#theme-update-div").replaceWith(data);
                } else {
                    $(data).insertBefore("#SettingAdminIndexForm");
                }
        });
    });

    $('#search').typeahead({
        source: function(typeahead, query) {
                $.ajax({
                    url: "<?= $this->webroot ?>admin/templates/ajax_quick_search/",
                    dataType: "json",
                    type: "POST",
                    data: {search: query, theme: $("#theme").val()},
                    success: function(data) {
                        console.log(data);
                        if (data) {
                            var return_list = [], i = data.length;
                            while (i--) {
                                return_list[i] = {
                                    id: data[i].id, 
                                    value: data[i].title + data[i].location
                                };
                            }
                            typeahead.process(return_list);
                        }
                    }
                });
            },
            onselect: function(obj) {
                if (obj.id) {
                    window.location.href = "<?= $this->webroot ?>admin/templates/edit/" + obj.id;
                }
        }
    });

    $(".info").popover({
        trigger: 'hover',
        placement: 'left'
    });
});
</script>

<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', null) ?>

<h1>Appearance Settings</h1>

<div id="theme-update-div"></div>

<?= $this->Form->create('Setting', array('class' => 'well', 'onsubmit' => 'return false')) ?>
<?= $this->Form->input('theme', array(
    'options' => $themes,
    'label' => 'Default Theme',
    'value' => $current_theme['data']
)) ?>
<?= $this->Form->hidden('theme_id', array('value' => $current_theme['id'])) ?>
<?= $this->Form->button('Submit', array('id' => 'theme-update')) ?>

<?= $this->Form->end() ?>

<div class="left">
    <h1>Themes<?php if (!empty($this->params->named['trash_theme'])): ?> - Trash<?php endif ?></h1>
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
            'trash_theme' => 1
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

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 50%">Title</th>
            <th>Created</th>
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
                    <td></td>
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
                <tr>
                    <td>
                        <?= $data['Theme']['title'] ?> 
                        <?php if (!empty($data['Theme']['id'])): ?>
                            <a class="btn btn-small btn-info refresh" id="<?= $data['Theme']['id'] ?>" href="#<?= $data['Theme']['title'] ?>" style="float:right">
                                <i class="icon-refresh icon-white"></i>
                            </a>
                        <?php endif ?>

                        <?php if (!empty($this->request->data['ThemesData'][$data['Theme']['title']]['data'])): ?>
                            <br />
                            <p class="span10 no-marg-left">
                                <?= $this->request->data['ThemesData'][$data['Theme']['title']]['data']['short_description'] ?>
                            </p>

                            <div class="clearfix"></div>
                            <?php if (!empty($this->request->data['ThemesData'][$data['Theme']['title']]['data']['author_url'])): ?>
                                <?= $this->Html->link(
                                    $this->request->data['ThemesData'][$data['Theme']['title']]['data']['author_name'], 
                                    $this->request->data['ThemesData'][$data['Theme']['title']]['data']['author_url'], 
                                    array('target' => '_blank')) ?> | 
                            <?php endif ?>
                            
                            <?= $this->Html->link('Theme Page',
                                $this->Api->url() . 'theme/' . $this->request->data['ThemesData'][$data['Theme']['title']]['data']['slug'],
                                array('target' => '_blank')
                            ) ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if (!empty($data['Theme']['id'])): ?>
                            <?= $this->Admin->time($data['Theme']['created']) ?>
                        <?php endif ?>
                    </td>
                    <?php if (!empty($this->request->data['ThemesData'][$data['Theme']['title']]['data'])): ?>
                        <?php if ($this->request->data['ThemesData'][$data['Theme']['title']]['data']['current_version'] == 
                            $this->request->data['ThemesData'][$data['Theme']['title']]['current_version']): ?>
                            <td class="info" data-title="<?= $this->request->data['ThemesData'][$data['Theme']['title']]['data']['title'] ?>" data-content="Theme is up to date">
                                <?= $this->request->data['ThemesData'][$data['Theme']['title']]['current_version'] ?> 
                                <i class="icon icon-ok info"></i>
                            </td>
                        <?php else: ?>
                            <td class="info" data-title="<?= $this->request->data['ThemesData'][$data['Theme']['title']]['data']['title'] ?>" data-content="Theme is out of date, newest version is <?= $this->request->data['ThemesData'][$data['Theme']['title']]['data']['current_version'] ?>">
                                <?= $this->request->data['ThemesData'][$data['Theme']['title']]['current_version'] ?> 
                                <i class="icon icon-ban-circle"></i>
                                <div class="clearfix"></div>

                                <?= $this->Html->link('Download Latest Version <i class="icon icon-white icon-download-alt"></i>', 
                                    $this->Api->url() . 'theme/' . $this->request->data['ThemesData'][$data['Theme']['title']]['data']['slug'],
                                    array('target' => '_blank', 'class' => 'btn btn-primary pull-right', 'escape' => false)
                                ) ?>
                            </td>
                        <?php endif ?>
                    <?php else: ?>
                        N/A
                    <?php endif ?>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (!empty($this->request->data['ThemesData'][$data['Theme']['title']]['data'])): ?>
                                    <?php if ($this->request->data['ThemesData'][$data['Theme']['title']]['status'] == 0): ?>
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
                                    <?php if ($this->request->data['ThemesData'][$data['Theme']['title']]['upgrade_status'] == 1): ?>
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
                                    <?php endif ?>
                                <?php endif ?>
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if (!empty($data['Theme']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Theme']['id'],
                                                'themes'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if (empty($this->request->data['ThemesData'][$data['Theme']['title']]['data'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Theme']['id'],
                                                $data['Theme']['title'],
                                                'theme',
                                                'themes'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php elseif (empty($this->request->data['ThemesData'][$data['Theme']['title']]['data'])): ?>
                                    <li>
                                        <?= $this->Admin->restore(
                                            $data['Theme']['id'],
                                            $data['Theme']['title'],
                                            'themes'
                                        ) ?>
                                    </li>  
                                    <li>
                                        <?= $this->Admin->delete_perm(
                                            $data['Theme']['id'],
                                            $data['Theme']['title'],
                                            'theme',
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

<div class="pull-left">
    <h1>Templates<?php if (!empty($this->params->named['trash_temp'])): ?> - Trash<?php endif ?></h1>
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
            'trash_temp' => 1
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
        'options' => $themes,
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
            <?php foreach ($this->request->data['Themes'] as $theme): ?>
            <li><?= $this->Html->link($theme['Theme']['title'], array('controller' => 'templates', 'action' => 'add', $theme['Theme']['id'])) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
            Filter by Theme
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($themes as $theme_id => $theme): ?>
                <li>
                    <?= $this->Html->link($theme, array(
                        'theme_id' => $theme_id
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php if (empty($this->request->data)): ?>
    <div class="clearfix"></div>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('Theme.title', 'Theme') ?></th>
                <th><?= $this->Paginator->sort('location', 'Folder') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this->request->data['Template'] as $data): ?>
            <tr>
                <td>
                    <?= $this->Html->link($data['Template']['title'], array('admin' => false, 'controller' => 'templates', 'action' => 'view', $data['Template']['id'])); ?>
                </td>
                <td>
                    <?php if ($data['Template']['theme_id'] > 0): ?>
                        <?= $data['Theme']['title'] ?>
                    <?php endif; ?>
                </td>
                <td>
                    /<?= str_replace(basename($data['Template']['location']), "",$data['Template']['location']) ?>
                </td>
                <td>
                    <?= $this->Admin->time($data['Template']['created']) ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (empty($this->params->named['trash_temp'])): ?>
                                <li>
                                    <?= $this->Admin->edit(
                                        $data['Template']['id']
                                    ) ?>
                                </li>
                                <li>
                                    <?= $this->Admin->delete(
                                        $data['Template']['id'],
                                        $data['Template']['title'],
                                        'template'
                                    ) ?>
                                </li>
                            <?php else: ?>
                                <li>
                                    <?= $this->Admin->restore(
                                        $data['Template']['id'],
                                        $data['Template']['title']
                                    ) ?>
                                </li>  
                                <li>
                                    <?= $this->Admin->delete_perm(
                                        $data['Template']['id'],
                                        $data['Template']['title'],
                                        'template'
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