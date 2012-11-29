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

    $(".btn-info").live('click', function(e) {
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
 });
 </script>

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
    View
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" style="min-width: 0px">
    <li><?= $this->Html->link('Active', array('admin' => true, 'action' => 'index')) ?></li>
    <li><?= $this->Html->link('Trash', array('admin' => true, 'action' => 'index', 'trash_theme' => 1)) ?></li>
  </ul>
</div>
<div class="clear"></div>

<?= $this->Html->link('Add Theme', array('controller' => 'themes', 'action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th>Title</th>
        <th>Created</th>
        <th>Options</th>
    </tr>
    
    <?php if (!empty($this->request->data['Themes'])): ?>
        <?php foreach ($this->request->data['Themes'] as $data): ?>
            <?php if ($data['Theme']['title'] == 'Default'): ?>
                <tr>
                    <td>
                        Default 
                        <a class="btn btn-small btn-info" id="1" href="#" style="float:right">
                            <i class="icon-refresh icon-white"></i>
                        </a>
                    </td>
                    <td></td>
                    <td>
                        <?= $this->Html->link(
                            '<i class="icon-pencil icon-white"></i> Edit', 
                            array('controller' => 'themes', 'action' => 'edit', 1),
                            array('class' => 'btn btn-primary', 'escape' => false));
                        ?>
                    </td>
                </tr>
            <?php else: ?>  
                <tr>
                    <td>
                        <?= $this->Html->link($data['Theme']['title'], array('admin' => false, 'controller' => 'templates', 'action' => 'view', $data['Theme']['id'])); ?> <a class="btn btn-small btn-info" id="<?= $data['Theme']['id'] ?>" href="#<?= $data['Theme']['title'] ?>" style="float:right"><i class="icon-refresh icon-white"></i></a>
                    </td>
                    <td><?= $this->Time->format('F jS, Y h:i A', $data['Theme']['created']); ?></td>
                    <td>
                        <?php if (empty($this->params->named['trash_theme'])): ?>
                            <?= $this->Html->link(
                                '<i class="icon-pencil icon-white"></i> Edit', 
                                array('controller' => 'themes', 'action' => 'edit', $data['Theme']['id']),
                                array('class' => 'btn btn-primary', 'escape' => false));
                            ?>
                            <?= $this->Html->link(
                                '<i class="icon-trash icon-white"></i> Delete',
                                array('controller' => 'themes', 'action' => 'delete', $data['Theme']['id'], $data['Theme']['title']),
                                array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this theme?')"));
                            ?>
                        <?php else: ?>
                            <?= $this->Html->link(
                                '<i class="icon-share-alt icon-white"></i> Restore', 
                                array('controller' => 'themes', 'action' => 'restore', $data['Theme']['id'], $data['Theme']['title']),
                                array('class' => 'btn btn-success', 'escape' => false));
                            ?>    
                            <?= $this->Html->link(
                                '<i class="icon-trash icon-white"></i> Delete Forever',
                                array('controller' => 'themes', 'action' => 'delete', $data['Theme']['id'], $data['Theme']['title'], 1),
                                array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this theme? This is permanent.')"));
                            ?>      
                        <?php endif ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<div class="left">
    <h1>Templates<?php if (!empty($this->params->named['trash_temp'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" style="min-width: 0px">
    <li><?= $this->Html->link('Active', array('admin' => true, 'action' => 'index')) ?></li>
    <li><?= $this->Html->link('Trash', array('admin' => true, 'action' => 'index', 'trash_temp' => 1)) ?></li>
  </ul>
</div>
<div class="clear"></div>

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
<div class="btn-group" style="float:right;margin-bottom:10px">
    <a class="btn dropdown-toggle" data-toggle="dropdown">
        Add Template
    <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <?php foreach ($this->request->data['Themes'] as $theme): ?>
        <li><?= $this->Html->link($theme['Theme']['title'], array('controller' => 'templates', 'action' => 'add', $theme['Theme']['id'])) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
        <th><?= $this->Paginator->sort('Theme.title', 'Theme') ?></th>
        <th><?= $this->Paginator->sort('location', 'Folder') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php if (!empty($this->request->data['Template'])): ?>
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
            <td><?= $this->Time->format('F jS, Y h:i A', $data['Template']['created']); ?></td>
            <td>
                <?php if (empty($this->params->named['trash_temp'])): ?>
                    <?= $this->Html->link(
                        '<i class="icon-pencil icon-white"></i> Edit', 
                        array('action' => 'edit', $data['Template']['id']),
                        array('class' => 'btn btn-primary', 'escape' => false));
                    ?>
                    <?= $this->Html->link(
                        '<i class="icon-trash icon-white"></i> Delete',
                        array('action' => 'delete', $data['Template']['id'], $data['Template']['title']),
                        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this template?')"));
                    ?>
                <?php else: ?>
                    <?= $this->Html->link(
                        '<i class="icon-share-alt icon-white"></i> Restore', 
                        array('action' => 'restore', $data['Template']['id'], $data['Template']['title']),
                        array('class' => 'btn btn-success', 'escape' => false));
                    ?>    
                    <?= $this->Html->link(
                        '<i class="icon-trash icon-white"></i> Delete Forever',
                        array('action' => 'delete', $data['Template']['id'], $data['Template']['title'], 1),
                        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this template? This is permanent.')"));
                    ?>      
                <?php endif ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<?= $this->element('admin_pagination') ?>