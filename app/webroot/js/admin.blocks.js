$(document).ready(function(){
    $("#BlockAdminForm").validate({
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

    $("#BlockLimit").on('change', function() {
        if ($(this).val() == 1) {
            $("#data").show();
            $("#next-step div").first().hide();
            $(".dynamic .order").hide();
        } else {
            $("#data").hide();
            $("#BlockData").val('');
            $("#next-step div").first().show();
            $(".dynamic .order").show();
        }
    });

    $("input[name='data[Block][location_type]']").on('change', function() {
        if ($(this).val() == "view") {
            if ($("#BlockLocationController option").length == 1) {
            	get_data('controllers');
        	} else {
        		$("#location").show();
        	}
        } else {
            $("#location").hide();
            $("#BlockLocation").val("");
        }
    });

    $("#BlockModel").on('change', function() {
    	if (!$(this).val()) {
    		$("#next-step").hide();
    		$("#BlockAdminAddForm").reset();
    		$("#BlockData option").remove();
    	} else {
    		get_model_data("model_field");
    	}
    });

    $("#BlockOrderBy").on('change', function() {
        if (!$(this).val() || $(this).val() == 'rand') {
            $("#next-step div").first().next().hide();
        } else {
            $("#next-step div").first().next().show();
        }
    });

    if ($("#BlockType").val() == 'dynamic') {
        get_model_data("model_field");
    }

    if ($("#BlockLimit").val() == 1) {
        $("#data").show();
    } else {
        $("#next-step div").first().show();

        if ($("#BlockOrderBy").val() && $("#BlockOrderBy").val() != "rand") {
            $("#next-step div").first().next().show();
            $(".order").show();
        }
    }

	if ($("#BlockModel").val()) {
		$("#next-step").show();
	}

	if ($("#BlockLocationAction").val()) {
		$("#location_action").show();
	}

	if ($("#BlockLocationId").val()) {
		$("#location_id").show();
	}

	if ($("#BlockLocationTypeView").is(':checked')) {
		$("#location").show();
        $("#BlockLocationController,#BlockLocationAction").removeClass('required').removeAttr('required');
        get_data('controllers');
    }

    /*
	* add block 
	if ($("#BlockModel").val()) {
		$("#next-step").show();
	}

	if ($("#BlockLocationAction").val()) {
		$("#location_action").show();
	}

	if ($("#BlockLocationId").val()) {
		$("#location_id").show();
	}

	if ($("#BlockLocationTypeView").is(':checked')) {
		$("#location").show();
	}
    */

	$("#BlockLocationController").on('change', function() {
		if ($("#BlockLocationTypeView").is(':checked')) {
			if ($(this).val()) {
				get_data("actions");
			}

			$("#location_id").hide();
		}
        $("#BlockLocationController,#BlockLocationAction").addClass('required').attr('required');
	});

	$("#BlockLocationAction").on('change', function() {
		if ($("#BlockLocationTypeView").is(':checked')) {
			if ($(this).val() == 'view' || $(this).val() == 'display') {
				get_model_data("action");
			}
		}
	});

    $("#BlockType").on('change', function() {
        var val = $(this).val();

        if (val == 'dynamic') {
            $("#next-step .code-block,#next-step .text-block").hide();
            $("#dynamic,#next-step .dynamic").show();

            $("#BlockCode,#BlockText").removeClass('required');
            $("#BlockModel").addClass('required');

            if (!$("#BlockModel").val()) {
                $(".btn-group").hide();
            } else {
                $(".btn-group").show();
            }
        } else if(val == 'text') {
            $("#dynamic,#next-step .dynamic,#next-step .code-block").hide();
            $("#next-step,#next-step .text-block,.btn-group").show();

            $("#BlockCode,#BlockModel").removeClass('required');
            $("#BlockText").addClass('required');
        } else if(val == 'code') {
            $("#dynamic,#next-step .dynamic,#next-step .text-block").hide();
            $("#next-step,#next-step .code-block,.btn-group").show();

            $("#BlockText,#BlockModel").removeClass('required');
            $("#BlockCode").addClass('required');
        } else {
            $("#dynamic,#next-step").hide();

            $("#BlockCode,#BlockText,#BlockModel").removeClass('required');
        }
    });

    if ($("#BlockType").val()) {
        $("#BlockType").trigger('change');
    }
});

function get_data(get)
{
	var controller = $("#BlockLocationController").val();
	var action = $("#BlockLocationAction").val();

    $.post($("#webroot").text() + "admin/permissions/ajax_location_list/", 
    	{
    		data:{
    			Block:{
    				controller: controller,
    				action: action,
    				get: get
    			}
    		}
    	}, function(new_data) {
    		var data_list = '';

    		if (get == 'controllers') {
    			var empty = $("#BlockLocationController option[value='']");
	    		for (var row in new_data) {
    				if (new_data[row].plugin) {
	    				data_list += '<option value="' + new_data[row].plugin_id + '">' + new_data[row].plugin + '</option>';
	    			} else {
	    				data_list += '<option value="' + new_data[row].controller_id + '">' + new_data[row].controller + '</option>';
	    			}
	    		}

	    		$("#BlockLocationController option").remove();
	    		$("#BlockLocationController").append(data_list).prepend(empty);
				$("#BlockLocationController option:first").attr('selected', 'selected');
	    	} else if(get == 'actions') {
    			var empty = $("#BlockLocationAction option[value='']");
	    		for (var row in new_data) {
	    			data_list += '<option value="' + new_data[row].action_id + '">' + new_data[row].action + '</option>';
	    		}

	    		$("#BlockLocationAction option").remove();
	    		$("#BlockLocationAction").append(data_list).prepend(empty);
	    		$("#BlockLocationAction option:first").attr('selected', 'selected');

	    		$("#location_action").show();
	    	}

	    	$("#location").show();
    }, 'json');
}

function get_model_data(type)
{
    $(".btn-group").show();

	if (type == 'model_field') {
		var empty = $("#BlockData option[value='']");
		var text = $("#BlockModel :selected").text().replace('Plugin - ', '');
		var component = $("#BlockModel").val();
        if ($("#custom-data").length > 0) {
            var custom = $.trim($("#custom-data").html());
        }
	} else if(type == 'action') {
		var empty = $("#BlockLocationId option[value='']");
		var component = $("#BlockLocationController").val();
	}

    $.post($("#webroot").text() + "admin/blocks/ajax_get_model/", 
    	{
    		data:{
    			Block:{
    				module_id: component,
    				type: type,
                    custom: custom
    			}
    		}
    	}, function(data) {
    		var new_data = $.parseJSON(data);

    		var data_list = '';

            if (new_data.custom) {
                $("#custom-data").html(new_data.custom).show();
            }

    		for (var row in new_data.data) {
    			data_list += '<option value="' + row + '">' + new_data.data[row] + '</option>';
    		}

    		if (type == 'model_field') {
	    		$("#BlockData option").remove();
	    		$("#BlockData").append(data_list).prepend(empty);

                var length = Number(text.length) - 1;

                if (text.substr(-3) == 'ies') {
                    var new_text = text.substr(0, text.length-3) + 'y';
                } else if (text[length] == 's') {
                    var new_text = text.substring(0, text.length-1);
                } else {
                    var new_text = text;
                }

	    		$("label[for='BlockData']").html(new_text + ' <i>*</i>');
	    		$("label[for='BlockLimit'] strong").html(text);

	    		$("#next-step").show();
	    	} else if(type == 'action') {
	    		$("#BlockLocationId option").remove();
	    		$("#BlockLocationId").append(data_list).prepend(empty);
	    		$("#BlockLocationId option:first").attr('selected', 'selected');

	    		$("#location_id").show();
	    	}

            if ($("#BlockDataHidden").length == 1) {
                var val = $("#BlockDataHidden").text();
                $("#BlockData option[value='" + val + "']").attr('selected', 'selected');

                $("#BlockDataHidden").remove();
            }
    });

    $("#location_add").live('click', function() {
		var controller = $("#BlockLocationController :selected").val();
		var action = $("#BlockLocationAction :selected").val();

    	if (controller && action) {
    		var random = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    		var id = $("#BlockLocationId :selected").val();

    		var value = controller + '/' + action

    		if (id) {
    			var text = value + '/' + $("#BlockLocationId :selected").text();
    			value += '/' + id;
    		} else {
    			var text = value;
    		}

    		if ($("#locations input[value='" + value + "']").length == 0) {
                $('#locations .error').remove();
				$("#locations").prepend('<div id="data-' + random + '"><span class="label label-info">' + text + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="LocationData[]" name="LocationData[]" value="' + value + '"></div>');
            } else {
                $("#locations").prepend('<label class="error">Location already added!</label>');
            }

            $("#BlockLocationController option:first").attr('selected', 'selected').trigger('change');
			$("#BlockLocationController,#BlockLocationAction").removeClass('required').removeAttr('required');
			$("#location_action").hide();
		}
    });
}