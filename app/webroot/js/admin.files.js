$(document).ready(function() {
    $(".add-media").live('click', function() {
        var val = $(this).parent().find(":selected");

        if (val.val()) {
            var random = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
            var prev = $(this).prev();

            if (prev.attr('id') == 'FileLibrary')
            {
                var id = 'File';
            } else if(prev.attr('id') == 'ArticleLibrary')
            {
                var id = 'Article';
            }
            else
            {
                var id = prev.attr('id').charAt(0);
            }

            if ($(this).parent().next().find("input[value='" + val.val() + "']").length == 0) {
                $(this).parent().parent().find('.error').remove();
                $(this).parent().next().prepend('<div id="data-' + random + '"><span class="label label-info">' + val.text() + ' <a href="#" class="fa fa-times fa-white"></a></span><input type="hidden" id="' + id + '.Media[]" name="data[' + id + '][Media][]" value="' + val.val() + '"></div>').hide().fadeIn('slow');
            } else {
                $(this).parent().next().prepend('<label class="error">Library already added!</label>').hide().fadeIn('slow');
            }

            val.parent().val('');
        }
    });
});