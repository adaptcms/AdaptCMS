<?= $this->Html->script('bootstrap-typeahead.js') ?>
<script>
$(document).ready(function(){
    $("#ModuleStepThreeForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            $(this).find(":input.error:first:not(:checkbox):not(:radio)").focus();
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'radio' || $(element).attr('type') == 'checkbox') {
                error.insertAfter( $(element).parent().find('label').last() );
            } else {
                error.insertAfter( element );
            }
        }
    });

	$("#backButton").live('click', function(e) {
		e.preventDefault();
		
		window.location = "<?= $this->Html->url(array(
            'controller' => 'modules',
            'action' => 'add',
            'admin' => true
            )) ?>";
		return false;
	});

    $('#ModuleSearch').typeahead({
        source: function(typeahead, query) {
                $.ajax({
                    url: "<?= $this->webroot ?>ajax/templates/quick_search/",
                    dataType: "json",
                    type: "POST",
                    data: {search: query, element: 1, theme: $("#ModuleTheme").val()},
                    success: function(data) {
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
                    var value = obj.value.split(' - ');

                    $("#ModuleSearch").val("");
                    $("#ModuleTemplate").val(value[0]);
                    $("#ModuleTemplateId").val(obj.id);
                }
        }
    });

    $("#ModuleLimit").live('change', function() {
        if ($(this).val() == 1) {
            $("#data").show();
        } else {
            $("#data").hide();
            $("#ModuleData").val('');
        }
    });

    $("input[name='data[Module][location_type]']").live('change', function() {
        if ($(this).val() == "view") {
            $("#location").show();
        } else {
            $("#location").hide();
            $("#ModuleLocation").val("");
        }
    });
});
</script>

<h1>Add Module - Step Two</h1>
<?= $this->Form->create('Module', array('class' => 'well', 'action' => 'step_three')) ?>

<?= $this->Form->input('limit', array(
        'label' => "How many <strong>".$model_title."</strong> to display?",
        'class' => 'required',
        'empty' => '- Choose -',
        'options' => $limit
)) ?>

<div id="data" style="display: none">
    <?= $this->Form->input('data', array(
            'type' => 'select', 
            'class' => 'required',
            'empty' => '- Choose -',
            'options' => $list,
            'label' => $model_title
    )) ?>
</div>

<div class="input radio">
    <?= $this->Form->label('location_type', 'Location') ?>
    <?= $this->Form->radio('location_type', array(
            '*' => 'Global',
            'view' => 'Specific View'
        ), array('legend' => false, 'hiddenField' => false, 'class' => 'required'
    )) ?>
</div>

<div id="location" style="display:none">
    <?= $this->Form->input('location') ?>
</div>

<div class="pull-left" style="margin-top:10px">
<label>Select Template <i>*</i></label>

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
<br />

Current Template Selected
<?= $this->Html->link('Add a Template', array('controller' => 'templates', 'action' => 'add')) ?>
<?= $this->Form->input('template', array(
        'class' => 'required',
        'div' => false,
        'label' => false,
        'style' => 'margin-left:10px;width:100%'
)) ?>
</div>

<div class="clearfix"></div>

<?= $this->Form->hidden('template_id') ?>

<div class="btn-group" style="margin-top:10px">
    <?php
        echo $this->Form->button('Back', array('id' => 'backButton', 'class' => 'btn'));
        echo $this->Form->submit('Continue', array('div' => false, 'class' => 'btn'));
    ?>
</div>

<?= $this->Form->end() ?>