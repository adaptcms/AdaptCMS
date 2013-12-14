$(document).ready(function(){
    $("#TemplateThemeId").live('change', function() {
        var theme = $("#TemplateThemeId option:selected");
        var empty = $("#TemplateLocation option[value='']");

        if ($(theme).val()) {
	        $.post($("#webroot").text() + "admin/templates/ajax_template_locations/",
	        {
	            data:{
	                Theme:{
	                    id: $(theme).val(),
	                    title: $(theme).html()
	                }
	            }
	        }, function(response) {
	        	$("#TemplateLocation option").remove();
	        	$("#TemplateLocation").append(response.data).prepend(empty);
	        });
	    } else {
	    	$("#TemplateLocation option").remove();
	    }
    });
});