$(document).ready(function(){
	if ($("#FieldFieldType").val() == "dropdown" || $("#FieldFieldType").val() == "multi-dropdown" ||
			$("#FieldFieldType").val() == "radio" || $("#FieldFieldType").val() == "check" || $('#ArticleFieldOptions').length) {
		$(".field_options").show();
	} else {
		$(".field_options").hide();
	}

	$("#FieldFieldType").live('change', function() {
		if ($(this).val() == "dropdown" || $(this).val() == "multi-dropdown" ||
			$(this).val() == "radio" || $(this).val() == "check") {
			$("#field_data").show();
			$(".field_options").show();
			$(".input.text.clear").css("padding-top", "9px").css("margin-top", "0");
		} else {
			$(".input.text.clear").css("padding-top", "0").css("margin-top", "-9px");
			$(".field_options").hide();
			// $("#field_data").html(null);
			$("#field_data").hide();
		}
	});

	$("#add-data").live('click', function() {
		// Gets the input value
		var input_val = $(this).prev().val().replace(/[^a-z0-9!-_&\s]/gi, '');
		var last_id = $("#field_data div:last").attr('id');
		// Source: http://forums.asp.net/t/1614106.aspx
		var random_id = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
		var is_exists = $("#field_data").find("input[value='" + input_val + "']").length;

		if (is_exists == 0) {
			if (last_id) {
				$('<div id="data-' + random_id + '"><span class="label label-info">' + input_val + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="FieldData[]" name="FieldData[]" value="' + input_val + '"></div>').insertAfter("#" + last_id).hide().fadeIn("slow");
			} else {
				$("#field_data").html('<div id="data-' + random_id + '"><span class="label label-info">' + input_val + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="FieldData[]" name="FieldData[]" value="' + input_val + '"></div>').hide().fadeIn("slow");
			}
			$(".tag").remove();
		} else {
			$("<label class='tag error'>Value already entered</label>").insertAfter("#add-data");
		}

		$(this).prev().val(null);
		$(this).prev().focus();

		$(".input.text.clear").css("padding-top", "9px");
	});

	$(".icon-remove-sign").live('click', function(e) {
		e.preventDefault();
		var id = $(this).parent().parent().attr('id');

		$("#" + id).fadeOut("slow", function() {
			$("#" + id).remove();
		});
	});

	var existing_data_count = Number($("#field_existing_data span").length);

	if (existing_data_count > 0) {
		$("#field_existing_data span").each(function(i) {
			var data = $(this).html();
			var random_id = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
			var last_id = $("#field_data div:last").attr('id');

			if (i == 0) {
				$("#field_data").html('<div id="data-' + random_id + '"><span class="label label-info">' + data + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="FieldData[]" name="FieldData[]" value="' + data + '"></div>');
			} else {
				$('<div id="data-' + random_id + '"><span class="label label-info">' + data + ' <a href="#" class="icon-white icon-remove-sign"></a></span><input type="hidden" id="FieldData[]" name="FieldData[]" value="' + data + '"></div>').insertAfter("#" + last_id);
			}
		});
	}
});