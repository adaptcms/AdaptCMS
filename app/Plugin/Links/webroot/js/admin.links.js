 $(document).ready(function(){
    $("#LinkUrl").rules("add", {
    	required: true,
    	url: true
    });

    $("#LinkImageUrl").rules("add", {
    	url: true
    });

    $(".image_url,.file_id").hide();

    $("#LinkType").on('change', function() {
    	if ($(this).val()) {
    		if ($(this).val() == 'file') {
    			$(".file_id").show();
    			$(".image_url").hide();

    			$("#LinkImageUrl").val('');
    		} else {
    			$(".file_id").hide();
    			$(".selected-images").html('');

    			$(".image_url").show();
    		}
    	} else {
    		$(".image_url,.file_id").hide();
    		$(".selected-images").html('');
    		$("#LinkImageUrl").val('');
    	}
    });

    if ($("#LinkId").length > 0)
    {
    	if ($("#File0").length > 0)
    	{
    		$("#LinkType").val('file').trigger('change');
    	} else if ($("#LinkImageUrl").val()) 
    	{
    		$("#LinkType").val('external').trigger('change');
    	}
    }
 });