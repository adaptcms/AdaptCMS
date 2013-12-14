$(document).ready(function(){
    $("button#poll-option-add").live('click', function() {
    	var number = Number($(".option").length);

    	$("#options").append('<div id="option' + number + '"><div class="input text"><label for="PollValue' + number + 'Title">Option ' + number + ' <i>*</i></label><input name="data[PollValue][' + number + '][title]" class="required option pull-left" type="text" id="PollValue' + number + 'Title"> <button class="btn btn-danger poll-option-remove pull-right"><i class="icon-trash icon-white"></i> Delete</button></div><div class="clearfix"></div></div>');
    });

    $(".poll-option-remove").live('click', function(e) {
        e.preventDefault();

    	$(this).parent().parent().remove();
    });

    $(".poll-remove").live('click', function(e) {
        e.preventDefault();

        var input = $(this).parent().parent().find('input[type="text"]');
        var del = $(this).parent().parent().find('.delete');

    	input.css("opacity", "0.3");
    	input.removeClass('required');
    	input.attr('disabled', true);
    	del.val('1');
    	$(this).replaceWith('<button class="btn btn-primary poll-option-undo pull-right"><i class="icon-refresh icon-white"></i> Undo</button>');
    });

    $(".poll-option-undo").live('click', function(e) {
    	e.preventDefault();

        var input = $(this).parent().parent().find('input[type="text"]');
        var del = $(this).parent().parent().find('.delete');

    	input.css("opacity", "1");
    	input.addClass('required');
    	input.attr('disabled', false);
    	del.val('0');
    	$(this).replaceWith('<button class="btn btn-danger poll-remove pull-right"><i class="icon-trash icon-white poll-delete"></i> Delete</button>');
    });

    $('.datepicker').datepicker();
});