$(document).ready(function() {
    if ($("#ForumOrd").length > 0) {
        $( "#sort-list" ).sortable({
            cursor: 'move',
            update: function(event, ui)
            {
                var order = $(this).sortable("toArray");
                var ul = $(this).parent();
                var id = $("#ForumId").val();

                if (id) {
                    $("#ForumOrd").val($("#sort-list li#" + id).index());
                } else {
                    $("#ForumOrd").val($("#sort-list li#0").index());
                }

                $("#ForumOrder").val(order);
            },
            create: function(event, ui)
            {
                var order = $(this).sortable("toArray");
                $("#ForumOrder").val(order);
            }
        });

        $( "#sort-list" ).disableSelection();

        $("#ForumTitle").on('change', function() {
            var value = $(this).val();

            if (!value) {
                noTitle();
            } else if($("#ForumCategoryId").val() && $("#sort-list li").length == 0) {
                $("#ForumCategoryId").trigger('change');
            }

            if ($("#ForumId").val()) {
                var id = $("#ForumId").val();
            } else {
                var id = 0;
            }

            var text = $("#sort-list li#" + id + " span:first").text();

            if (text != value)
            {
                $("#sort-list li#" + id + " span:first").text(value);
            }
        });

        $("#ForumCategoryId").on('change', function() {
            var id = $("#ForumId").val();
            var category_id = $(this).val();
            var title = $("#ForumTitle").val();
            var description = tinyMCE.activeEditor.getContent();

            if (!category_id) {
                noCategory();
            } else if (!title) {
                noTitle();
            } else {
                $.post($("#webroot").text() + "admin/adaptbb/forums/ajax_forums/", 
                {
                    data:{
                        Forum:{
                            id: id,
                            category_id: category_id,
                            title: title,
                            description: description
                        }
                    }
                }, function(response) {
                    $("#sort-list").html(response.data);

                    if (!id) {
                        $("#ForumOrd").val($("#sort-list li#0").index());
                    } else {
                        $("#ForumOrd").val($("#sort-list li#" + id).index());
                    }

                    enablePopovers();
                }, 'json');
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