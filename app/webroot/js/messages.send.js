$(document).ready(function() {
    $('#MessageRecipient').typeahead({
        source: function(typeahead, query) {
            $.ajax({
                url: $("#webroot").text() + "ajax/users/quick_search/",
                dataType: "json",
                type: "POST",
                data: { User: { username: query } },
                success: function(response) {
                    var data = response.data;

                    if (data) {
                        var return_list = [], i = data.length;
                        while (i--) {
                            return_list[i] = {
                                id: data[i].id,
                                value: data[i].username
                            };
                        }
                        typeahead.process(return_list);
                    }
                }
            });
        },
        onselect: function(obj) {
            if (obj.id)
            {
                $('#MessageReceiverUserId').val(obj.id);
            }
        }
    });
});