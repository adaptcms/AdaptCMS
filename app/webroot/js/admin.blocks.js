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
        } else {
            $("#data").hide();
            $("#BlockData").val('');
            $("#next-step div").first().show();
        }

        $(".dynamic .order").show();
    });

    $("#BlockModel").on('change', function() {
    	if (!$(this).val()) {
    		$("#next-step").hide();
    		$("#BlockAdminAddForm").reset();
    		$("#BlockData option").remove();
    	} else {
    		get_model_data();
    	}
    });

    $('#BlockData').on('change', function() {
        if ($(this).val()) {
            $(".order").hide();
        } else {
            $(".order").show();
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
        get_model_data();
    }

    if ($('#BlockDataHidden').length) {
        $(".order").hide();
    } else {
        $(".order").show();
    }

    if ($("#BlockLimit").val() == 1) {
        $("#data").show();
    } else {
        $("#next-step div").first().show();

        if ($("#BlockOrderBy").val() != "rand") {
            $("#next-step div").first().next().show();
        }
    }

	if ($("#BlockModel").val()) {
		$("#next-step").show();
	}

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

function get_model_data()
{
    $(".btn-group").show();

    var empty = $("#BlockData option[value='']");
    var text = $("#BlockModel :selected").text().replace('Plugin - ', '');
    var component = $("#BlockModel").val();
    if ($("#custom-data").length > 0) {
        var custom = $.trim($("#custom-data").html());
    }
    if ($('#BlockOrderByHide').val()) {
        var order_by = $('#BlockOrderByHide').val();
    }

    $.post($("#webroot").text() + "admin/blocks/ajax_get_model/",
    {
        data:{
            Block:{
                module_id: component,
                custom: custom
            }
        }
    }, function(response) {
        var new_data = response.data;

        if (new_data.order_by) {
            $('#BlockOrderBy option[value!=""]').remove();

            var data_list = '';
            for (var row in new_data.order_by) {
                data_list += '<option value="' + row + '">' + new_data.order_by[row] + '</option>';
            }

            $('#BlockOrderBy').append(data_list);

            if (order_by) {
                $('#BlockOrderBy option:selected').removeAttr('selected');
                $('#BlockOrderBy option[value="' + order_by + '"]').attr('selected', 'selected');
                $('#BlockOrderByHide').val('');
            }
        }

        if (new_data.custom) {
            $("#custom-data").html(new_data.custom).show();
        }

        var data_list = '';
        for (var row in new_data.data) {
            data_list += '<option value="' + row + '">' + new_data.data[row] + '</option>';
        }

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

        if ($("#BlockDataHidden").length == 1) {
            var val = $("#BlockDataHidden").text();
            $("#BlockData option[value='" + val + "']").attr('selected', 'selected');

            $("#BlockDataHidden").remove();
        }
    }, 'json');
}