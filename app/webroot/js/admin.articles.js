$(document).ready(function(){
    if ($('#related-search').length)
    {
        $('#related-search').typeahead({
            source: function(typeahead, query) {
                $.ajax({
                    url: $("#webroot").text() + "admin/articles/ajax_related_search",
                    dataType: "json",
                    type: "POST",
                    data: {search: query, category: $("#category").val(), id: $("#ArticleId").val()},
                    success: function(data) {
                        if (data) {
                            var return_list = [], i = data.length;
                            while (i--) {
                                return_list[i] = {
                                    id: data[i].id, 
                                    value: data[i].title + data[i].category
                                };
                            }
                            typeahead.process(return_list);
                        }
                    }
                });
            },
            onselect: function(obj) {
                if (obj.id) {
                	if ($(".related[value='" + obj.id + "']").length == 0) {
                		$(".related-error").html("").hide();

	                	var html = '<div id="data-' + obj.id + '"><span class="label label-info">' + obj.value + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="RelatedData[]" class="related" name="RelatedData[]" value="' + obj.id + '"></div>';

	                	$("#related-articles").prepend(html);
	                } else {
	                	$(".related-error").html("<strong>Error</strong> Article already linked").show();
	                }

                	$("#related-search").val("").focus();
                }
            }
        });

        $(".field-desc").popover({
            trigger: 'hover',
            placement: 'left'
        });

        $.each($(".checkbox"), function(i, val) {
            if (!$(this).hasClass('input')) {
                $(this).replaceWith($(this).find('input,label'));
            }
        });

        $("button").live('click', function(){
            var btn = $(this).html();

            if (btn == "Publish Now") {
                $(".publish_time").hide();
                $("#ArticlePublishingDate").val($('.date_ymd').html());
                $("#ArticlePublishingTime").val($('.time_gia').html());
                $("#ArticleStatus").val(1);
            } else if(btn == "Save Draft") {
                $(".publish_time").hide();
                $("#ArticlePublishingDate").val($('.date_ymd').html());
                $("#ArticlePublishingTime").val($('.time_gia').html());
                $("#ArticleStatus").val(0);
            } else if(btn.match('Publish Later')) {
                $(".publish_time").toggle();
                $("#ArticleStatus").val(1);
            }
        });

        $('.datepicker').datepicker();

        $('.field_options').show();
        
        if ($('.show_publish_time').length)
        {
            $(".publish_time").show();
        }
        else
        {
            $('.publish_time').hide();
        }
        
        if ($('admin-validate-article').find(':radio').length)
        {
            $(".admin-validate-article").validate({
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
        }
        else
        {
            $(".admin-validate-article").validate();
        }
    }

    if ($("#related-submit").length)
    {
        $("#related-submit").live('click', function(e) {
            e.preventDefault();

            if ($(".related").length > 0) {
                var values = $(".related").map(function(){
                    return $(this).val();
                }).get();

                $.post($("#webroot").text() + "admin/articles/ajax_related_add/", 
                    {
                        data:{
                            Article:{
                                id: $("#ArticleId").val(),
                                ids: values
                            }
                        }
                    }, function(data) {
                    if (data) {
                        $("#flashMessageRelated").replaceWith(data);
                        $("#flashMessageRelated").fadeOut(3000);
                    }
                });
            }
        });

        $("input[type=file]").live('change', function() {
            if ($("#" + this.id).val()) {
                $("#" + this.id.replace('Data', 'Delete')).attr('disabled', true).attr('checked', false);
            }
        });
    }

    $(".admin-validate-article").on('submit', function(e) {
        if (!$("#pass_form").length)
        {   
            e.preventDefault();
        }

        if ($(this).valid() && !$("#pass_form").length)
        {
            var textareas = $(this).find('.textarea textarea');

            if (textareas.length)
            {
                var error = 0;

                $.each(textareas, function() {
                    var id = $(this).attr('id');
                    var content = tinyMCE.get(id).getContent();
                    var char_amount = content.replace(/<[^>]+>/g, '').length;
                    var min = $(this).attr('minlength');
                    var max = $(this).attr('maxlength');
                    var error_msg = '';

                    if (content.length == 0 && $(this).hasClass('required'))
                    {
                        error++;
                        error_msg = 'This field is required.';
                    }
                    else if(min && char_amount < min)
                    {
                        error++;
                        error_msg = 'Please enter at least ' + min + ' characters. (currently ' + char_amount + ')';
                    }
                    else if(max && char_amount > max)
                    {
                        error++;
                        error_msg = 'Please enter no more than ' + max + ' characters. (currently ' + char_amount + ')';
                    }

                    if (error_msg.length)
                    {
                        var msg = '<label for="' + id + '" class="error">' + error_msg + '</label>';

                        if ($(this).parent().find('label.error').length == 0)
                        {
                            $(this).parent().append(msg);
                        }
                        else
                        {
                            $(this).parent().find('label.error').show().html(msg);
                        }
                    }
                    else
                    {
                        if ($(this).parent().find('label.error').length > 0)
                        {
                            $(this).parent().find('label.error').hide();
                        }
                    }
                });
            
                if (error == 0)
                {
                    $(this).prepend('<i id="pass_form" class="hidden">1</i>');
                    $(this).submit();
                }
            }
            else
            {
                $(this).prepend('<i id="pass_form" class="hidden">1</i>');
                $(this).submit();
            }
        }
    });
});