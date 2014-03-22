$(document).ready(function(){
    $("button#poll-option-add").live('click', function() {
    	var number = Number($(".option").length);

    	$("#options").append('<div class="input-group col-lg-6 no-pad-l clearfix" style="margin-bottom: 0;" id="option' + number + '"><label for="PollValue' + number + 'Title">Option ' + number + ' <i>*</i></label><div class="clearfix"></div><input name="data[PollValue][' + number + '][title]" class="required option form-control form-control-inline pull-left" type="text" id="PollValue' + number + 'Title"> <button class="btn btn-danger poll-option-remove"><i class="fa fa-trash-o"></i> Delete</button></div><div class="clearfix"></div></div>');
    });

    $(".poll-option-remove").live('click', function(e) {
        e.preventDefault();

    	$(this).parent().remove();
    });

    $(".poll-remove").live('click', function(e) {
        e.preventDefault();

        var input = $(this).parent().find('input[type="text"]');
        var del = $(this).parent().find('.delete');

    	input.css("opacity", "0.3");
    	input.removeClass('required');
    	input.attr('disabled', true);
    	del.val('1');
    	$(this).replaceWith('<button class="btn btn-primary poll-option-undo"><i class="fa fa-refresh"></i> Undo</button>');
    });

    $(".poll-option-undo").live('click', function(e) {
    	e.preventDefault();

        var input = $(this).parent().find('input[type="text"]');
        var del = $(this).parent().find('.delete');

    	input.css("opacity", "1");
    	input.addClass('required');
    	input.attr('disabled', false);
    	del.val('0');
    	$(this).replaceWith('<button class="btn btn-danger poll-remove"><i class="fa fa-trash-o poll-delete"></i> Delete</button>');
    });

    $('.datepicker').datepicker().
        on('changeDate', function(e) {
            $('.datepicker').datepicker('hide');
        });
});