$(document).ready(function(){
    $("#theme-update").live('click', function() {
        var theme = $("#SettingTheme").val();
        var setting_id = $("#SettingThemeId").val();
        var theme_name = $("#SettingTheme option:selected").text();

        $.post($('#webroot').text() + "admin/templates/ajax_theme_update/",
        {
            data:{
                Setting:{
                    data: theme,
                    id: setting_id,
                    title: theme_name
                }
            }
        }, function(response) {
            if ($("#theme-update-div").length != 0) {
                $("#theme-update-div").replaceWith(response.data);
            } else {
                $(response.data).insertBefore("#SettingAdminIndexForm");
            }
        });
    });

    $(".refresh.btn-info").live('click', function(e) {
        e.preventDefault();

        var theme_id = $(this).attr('id');
        var theme_name = $(this).attr('href').replace("#","");

        $.post($('#webroot').text() + "admin/templates/ajax_theme_refresh/",
        {
            data:{
                Theme:{
                    id: theme_id,
                    name: theme_name
                }
            }
        }, function(response) {
            if ($("#theme-update-div").length != 0) {
                $("#theme-update-div").replaceWith(response.data);
            } else {
                $(response.data).insertBefore("#SettingAdminIndexForm");
            }
        });
    });

    $('#search').typeahead({
        source: function(typeahead, query) {
            $.ajax({
                url: $('#webroot').text() + "admin/templates/ajax_quick_search/",
                dataType: "json",
                type: "POST",
                cache: false,
                data: {search: query, theme: $("#theme").val()},
                success: function(response) {
                    var data = $.parseJSON(response.data);

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
                window.location.href = $('#webroot').text() + "admin/templates/edit/" + obj.id;
            }
        }
    });

    $(".theme-info").popover({
        trigger: 'hover',
        placement: 'left'
    });
});