$(document).ready(function() {
    if ($("#FieldFieldOrder").length) {
        $( "#sort-list" ).sortable({
            cursor: 'move',
            create: function(event, ui)
            {
                var order = $(this).sortable("toArray");
                $("#FieldOrder").val(order);
            }
        });

        $( "#sort-list").on('sortupdate', function(event, ui) {
            var order = $(this).sortable("toArray");
            var ul = $(this).parent();
            var id = $("#FieldId").val();

            if (id) {
                $("#FieldFieldOrder").val($("#sort-list li#" + id).index());
            } else {
                $("#FieldFieldOrder").val($("#sort-list li#0").index());
            }

            $("#FieldOrder").val(order);
        });

        $( "#sort-list" ).disableSelection();

        $(".icon-question-sign").popover({
            trigger: 'hover',
            placement: 'left'
        });

        $("#FieldTitle").on('change', function() {
            var value = $(this).val();

            if (!value) {
                noTitle();
            } else if($("#FieldCategoryId").val() && $("#sort-list li").length == 0) {
                $("#FieldCategoryId").trigger('change');
            }

            if ($("#FieldId").val()) {
                var id = $("#FieldId").val();
            } else {
                var id = 0;
            }

            var text = $("#sort-list li#" + id + " span:first").text();

            if (text != value) {
                $("#sort-list li#" + id + " span:first").text(value);
            }
        });

        $("#FieldCategoryId").on('change', function() {
            var id = $("#FieldId").val();
            var category_id = $(this).val();
            var title = $("#FieldTitle").val();
            var description = tinyMCE.activeEditor.getContent();

            if (!category_id) {
                noCategory();
            } else if (!title) {
                noTitle();
            } else {
                $.post($("#webroot").text() + "admin/fields/ajax_fields/", 
                {
                    data:{
                        Field:{
                            id: id,
                            category_id: category_id,
                            title: title,
                            description: description
                        }
                    }
                }, function(response) {
                    $("#sort-list").html(response.data);
                    $("#sort-list").trigger('sortupdate');

                    enablePopovers();
                }, 'json');
            }
        });

        fieldTypeToggle($("#FieldFieldTypeId").val(), false);

        $("#FieldFieldTypeId").on('change', function() {
            fieldTypeToggle($(this).val(), true);
        });

        $("#FieldImport").live('change', function() {
            var id = $(this).val();

            if (id) {
                $.post($("#webroot").text() + "admin/fields/ajax_import/", 
                {
                    data:{
                        Field:{
                            id: id
                        }
                    }
                }, function(data_parse) {
                    var data = data_parse.data;
                    var field_options = data.Field.field_options;

                    if (data.Field.id) {
                        $("#field_data").html('');
                        
                        $("#FieldCategoryId").val(data.Field.category_id).trigger('change');
                        $("#FieldFieldTypeId").val(data.Field.field_type_id).trigger('change');
                        $("#FieldFieldLimitMin").val(data.Field.field_limit_min);
                        $("#FieldFieldLimitMax").val(data.Field.field_limit_max);

                        if (data.Field.description == '') {
                            tinyMCE.activeEditor.setContent('');
                        } else {
                            tinyMCE.activeEditor.setContent(data.Field.description);
                        }
                        
                        if (data.Field.required == 1) {
                            $("#FieldRequired").attr('checked', 'checked');
                        } else {
                            $("#FieldRequired").attr('checked', false);
                        }

                        if (field_options) {
                            $.each(field_options, function(i, val) {
                                $("#FieldFieldOptions").val(val);
                                $("#add-data").trigger('click');
                            });
                        }

                        $("#sort-list").trigger('sortupdate');
                    }
                }, 'json');
            } else {
                $("#FieldCategoryId").val('').trigger('change');
                $("#FieldFieldTypeId").val('').trigger('change');
                $("#FieldFieldLimitMin").val(0);
                $("#FieldFieldLimitMax").val(0);
                $("#FieldRequired").attr('checked', false);
            }
        });
    }
});

function noTitle()
{
    $("#sort-list").html('<p>Please enter in name of field');
}

function noCategory()
{
    $("#sort-list").html('<p>No Category Currently Selected</p>');
}

function fieldTypeToggle(val,trigger_show) 
{
    /*
    if (val == "date") {
        fieldLimitToggle('hide');
    } else if(val == "file") {
        fieldLimitToggle('hide');
    } else if(val == "dropdown") {
        fieldLimitToggle('hide');
    } else if(val == "multi-dropdown") {
        fieldLimitToggle('hide');
    } else if(val == "radio") {
        fieldLimitToggle('hide');
    } else if(val == "check") {
        fieldLimitToggle('hide');
    } else if(trigger_show === true) {
        fieldLimitToggle('show');
    }

    if ($('#field-rules .field').length)
    {
        $.each($('#field-rules .field'), function() {
            console.log($(this).attr('data-slug'));
            console.log($(this).html());
        });
    }
    */

    var field = $('.field[data-id="' + val + '"]');

    if (val)
    {
        if (val == 2 || val == 4 || val == 8 || val == 10)
        {
            $('.field_options').show();
        }
        else
        {
            $('.field_options').hide();
        }

        if (field.length)
        {
            var toggle = field.html();

            fieldLimitToggle(toggle);
        }
    } else if(trigger_show === true)
    {
        fieldLimitToggle('show');
    }
}

function fieldLimitToggle(type) 
{
    if (type == "show") {
        $("#FieldFieldLimitMin").val('0').show();
        $("#FieldFieldLimitMin").prev().show();

        $("#FieldFieldLimitMax").val('0').show();
        $("#FieldFieldLimitMax").prev().show();
    } else {
        $("#FieldFieldLimitMin").val('0').hide();
        $("#FieldFieldLimitMin").prev().hide();

        $("#FieldFieldLimitMax").val('0').hide();
        $("#FieldFieldLimitMax").prev().hide();
    }
}