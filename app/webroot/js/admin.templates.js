$(document).ready(function(){
    $("#TemplateThemeId").live('change', function() {
        var theme = $("#TemplateThemeId option:selected");
        var empty = $("#TemplateLocation option[value='']");

        if ($(theme).val()) {
	        $.post($("#webroot").text() + "ajax/templates/template_locations/", 
	        {
	            data:{
	                Theme:{
	                    id: $(theme).val(),
	                    title: $(theme).html()
	                }
	            }
	        }, function(data) {
	        	$("#TemplateLocation option").remove();
	        	$("#TemplateLocation").append(data).prepend(empty);
	        });
	    } else {
	    	$("#TemplateLocation option").remove();
	    }
    });
});