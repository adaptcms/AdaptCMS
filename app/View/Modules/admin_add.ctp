<?= $this->Html->script('bootstrap-typeahead.js') ?>
<script>
$(document).ready(function(){
    $("#ModuleAdminAddForm").validate({
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

    $('#ModuleSearch').typeahead({
        source: function(typeahead, query) {
                $.ajax({
                    url: "<?= $this->webroot ?>admin/templates/ajax_quick_search/",
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

    $("#ModuleLimit").on('change', function() {
        if ($(this).val() == 1) {
            $("#data").show();
            $("#next-step div").first().hide();
        } else {
            $("#data").hide();
            $("#ModuleData").val('');
            $("#next-step div").first().show();
        }
    });

    $("input[name='data[Module][location_type]']").on('change', function() {
        if ($(this).val() == "view") {
            if ($("#ModuleLocationController option").length == 1) {
            	get_data('controllers');
        	} else {
        		$("#location").show();
        	}
        } else {
            $("#location").hide();
            $("#ModuleLocation").val("");
        }
    });

    $("#ModuleModel").on('change', function() {
    	if (!$(this).val()) {
    		$("#next-step").hide();
    		$("#ModuleAdminAddForm").reset();
    		$("#ModuleData option").remove();
    	} else {
    		get_model_data("model_field");
    	}
    });

    $("#ModuleOrderBy").on('change', function() {
        if (!$(this).val() || $(this).val() == 'rand') {
            $("#next-step div").first().next().hide();
        } else {
            $("#next-step div").first().next().show();
        }
    });

	if ($("#ModuleModel").val()) {
		$("#next-step").show();
	}

	if ($("#ModuleLocationAction").val()) {
		$("#location_action").show();
	}

	if ($("#ModuleLocationId").val()) {
		$("#location_id").show();
	}

	if ($("#ModuleLocationTypeView").is(':checked')) {
		$("#location").show();
	}

	$("#ModuleLocationController").on('change', function() {
		if ($("#ModuleLocationTypeView").is(':checked')) {
			if ($(this).val()) {
				get_data("actions");
			}

			$("#location_id").hide();
		}
	});

	$("#ModuleLocationAction").on('change', function() {
		if ($("#ModuleLocationTypeView").is(':checked')) {
			if ($(this).val() == 'view' || $(this).val() == 'display') {
				get_model_data("action");
			}
		}
	});
});

function get_data(get)
{
	var controller = $("#ModuleLocationController").val();
	var action = $("#ModuleLocationAction").val();

    $.post("<?= $this->webroot ?>admin/permission_values/ajax_location_list/", 
    	{
    		data:{
    			Module:{
    				controller: controller,
    				action: action,
    				get: get
    			}
    		}
    	}, function(data) {
    		var new_data = $.parseJSON(data);
    		var data_list = '';

    		if (get == 'controllers') {
    			var empty = $("#ModuleLocationController option[value='']");
	    		for (var row in new_data) {
    				if (new_data[row].plugin) {
	    				data_list += '<option value="' + new_data[row].plugin_id + '">' + new_data[row].plugin + '</option>';
	    			} else {
	    				data_list += '<option value="' + new_data[row].controller_id + '">' + new_data[row].controller + '</option>';
	    			}
	    		}

	    		$("#ModuleLocationController option").remove();
	    		$("#ModuleLocationController").append(data_list).prepend(empty);
	    		$("#ModuleLocationController").val("option:first");
	    	} else if(get == 'actions') {
    			var empty = $("#ModuleLocationAction option[value='']");
	    		for (var row in new_data) {
	    			data_list += '<option value="' + new_data[row].action_id + '">' + new_data[row].action + '</option>';
	    		}

	    		$("#ModuleLocationAction option").remove();
	    		$("#ModuleLocationAction").append(data_list).prepend(empty);
	    		$("#ModuleLocationAction").val("option:first");

	    		$("#location_action").show();
	    	}

	    	$("#location").show();
    });
}

function get_model_data(type)
{
	if (type == 'model_field') {
		var empty = $("#ModuleData option[value='']");
		var text = $("#ModuleModel :selected").text().replace('Plugin - ', '');
		var component = $("#ModuleModel").val();
	} else if(type == 'action') {
		var empty = $("#ModuleLocationId option[value='']");
		var component = $("#ModuleLocationController").val();
	}

    $.post("<?= $this->webroot ?>admin/modules/ajax_get_model/", 
    	{
    		data:{
    			Module:{
    				component_id: component,
    				type: type
    			}
    		}
    	}, function(data) {
    		var new_data = $.parseJSON(data);

    		var data_list = '';

    		for (var row in new_data) {
    			data_list += '<option value="' + row + '">' + new_data[row] + '</option>';
    		}

    		if (type == 'model_field') {
	    		$("#ModuleData option").remove();
	    		$("#ModuleData").append(data_list).prepend(empty);

                var length = Number(text.length) - 1;

                if (text.substr(-3) == 'ies') {
                    var new_text = text.substr(0, text.length-3) + 'y';
                } else if (text[length] == 's') {
                    var new_text = text.substring(0, text.length-1);
                } else {
                    var new_text = text;
                }

	    		$("label[for='ModuleData']").html(new_text + ' <i>*</i>');
	    		$("label[for='ModuleLimit'] strong").html(text);

	    		$("#next-step").show();
	    	} else if(type == 'action') {
	    		$("#ModuleLocationId option").remove();
	    		$("#ModuleLocationId").append(data_list).prepend(empty);
	    		$("#ModuleLocationId").val("option:first");

	    		$("#location_id").show();
	    	}
    });

    $("#location_add").on('click', function() {
		var controller = $("#ModuleLocationController :selected").val();
		var action = $("#ModuleLocationAction :selected").val();

    	if (controller && action) {
    		var random = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    		var id = $("#ModuleLocationId :selected").val();

    		var value = controller + '/' + action

    		if (id) {
    			var text = value + '/' + $("#ModuleLocationId :selected").text();
    			value += '/' + id;
    		} else {
    			var text = value;
    		}

    		if ($("#locations input[value='" + value + "']").length == 0) {
				$("#locations").prepend('<div id="data-' + random + '"><span class="label label-info">' + text + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="LocationData[]" name="LocationData[]" value="' + value + '"></div>');
			}

			$("#ModuleLocationController,#ModuleLocationAction").removeClass('required').removeAttr('required');
			$("#ModuleLocationController").val("option:first").trigger('change');
			$("#location_action").hide();
		}
    });
}
</script>


<h1>Add Module</h1>
<?php
    echo $this->Form->create('Module', array('class' => 'well'));
    
    echo $this->Form->input('title', array(
    	'label' => 'Name of Module',
    	'class' => 'required'
    ));

    echo $this->Form->input('model', array(
    	'type' => 'select', 
    	'class' => 'required',
    	'empty' => '- Choose -',
    	'options' => $models
    ));
?>

<div id="next-step" style="display:none">
    <?= $this->Form->input('order_by', array(
            'empty' => '- Choose -',
            'options' => array(
                'id' => 'ID',
                'title' => 'Title',
                'created' => 'Created',
                'modified' => 'Modified',
                'rand' => 'Random'
            ),
            'div' => array(
                'style' => 'display: none'
            )
    )) ?>

    <?= $this->Form->input('order_dir', array(
            'options' => array(
                'asc' => 'Ascending',
                'desc' => 'Descending'
            ),
            'div' => array(
                'style' => 'display: none'
            )
    )) ?>

	<?= $this->Form->input('limit', array(
	        'label' => "How many <strong></strong> to display?",
	        'empty' => '- Choose -',
	        'options' => $limit
	)) ?>

	<div id="data" style="display: none">
	    <?= $this->Form->input('data', array(
	            'type' => 'select', 
	            'empty' => '- Choose -'
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
		<div id="location_controller_component" style="display:none"></div>
		<?= $this->Form->input('location_plugin', array(
				'type' => 'select',
				'div' => array('id' => 'location_plugin', 'style' => 'display:none')
		)) ?>
		<div style="margin-left:35%" id="locations"></div>
    	<?= $this->Form->input('location_controller', array(
    			'required' => true,
    			'type' => 'select',
    			'empty' => '- choose controller -',
    			'div' => array('id' => 'location_controller'),
    			'label' => false
    	)) ?>
    	<?= $this->Form->input('location_action', array(
    			'required' => true,
    			'type' => 'select',
    			'empty' => '- choose action -',
    			'div' => array('id' => 'location_action', 'style' => 'display:none'),
    			'label' => false
    	)) ?>
    	<?= $this->Form->input('location_id', array(
    			'empty' => '- choose entry (optional) -',
    			'div' => array('id' => 'location_id', 'style' => 'display:none'),
    			'label' => false
    	)) ?>
    	<?= $this->Form->button('Add', array(
    			'class' => 'btn btn-info', 
    			'id' => 'location_add',
    			'type' => 'button'
    	)) ?>

    	<div class="clearfix"></div>
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
	<?= $this->Html->link('Add a Template', array(
			'controller' => 'templates', 
			'action' => 'add'
			), array('target' => '_blank')
	) ?>
	<?= $this->Form->input('template', array(
	        'div' => false,
	        'label' => false,
	        'style' => 'margin-left:10px;width:100%'
	)) ?>
	</div>

	<div class="clearfix"></div>

	<?= $this->Form->hidden('template_id') ?>
	<?= $this->Form->hidden('component_id') ?>

	<div class="btn-group" style="margin-top:10px">
	    <?php
	        echo $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
            echo $this->Form->submit('Continue', array('div' => false, 'class' => 'btn'));
        ?>
	</div>
</div>

<?= $this->Form->end() ?>