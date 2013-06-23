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

        var form = $("#ForumCategoryAdminForm");

        $(form).on('submit', function(e) {
            if ($("#pass_form").length == 0)
            {
                e.preventDefault();
            }

            if ($("#sort-list li").length > 0 && $(form).valid())
            {
                var order = [];

                $("#sort-list").find('li').each(function(){ order.push(this.id); });

                $.post($("#webroot").text() + "admin/adaptbb/forum_categories/ajax_order/", 
                    {
                        data:{
                            ForumCategory:{
                                category_ids: order
                            }
                        }
                    }, function(data) {

                    $(form).prepend('<i id="pass_form" style="display: none">1</i>');
                    $(form).submit();
                });
            }
        });
    }
});