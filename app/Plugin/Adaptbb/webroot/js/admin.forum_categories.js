$(document).ready(function() {
    if ($("#ForumCategoryOrd").length > 0) {
        $( "#sort-list" ).sortable({
            cursor: 'move',
            update: function(event, ui)
            {
                var order = $(this).sortable("toArray");
                var ul = $(this).parent();
                var id = $("#ForumCategoryId").val();

                if (id) {
                    $("#ForumCategoryOrd").val($("#sort-list li#" + id).index());
                } else {
                    $("#ForumCategoryOrd").val($("#sort-list li#0").index());
                }

                $("#ForumCategoryOrder").val(order);
            },
            create: function(event, ui)
            {
                var order = $(this).sortable("toArray");
                $("#ForumCategoryOrder").val(order);
            }
        });

        $( "#sort-list" ).disableSelection();

        $(".icon-question-sign").popover({
            trigger: 'hover',
            placement: 'left'
        });

        $("#ForumCategoryTitle").on('change', function() {
            var value = $(this).val();

            if ($("#ForumCategoryId").val()) {
                var id = $("#ForumCategoryId").val();
            } else {
                var id = 0;
            }

            var text = $("#sort-list li#" + id + " span:first").text();

            if (text != value)
            {
                $("#sort-list li#" + id + " span:first").text(value);
            }
        });
    }
});