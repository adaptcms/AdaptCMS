$(function() {
  $('.error').hide();
  $(".button").click(function() {
		// validate and process form
		// first hide any error messages

    $('.error').hide();
	  var status = $("textarea#status").val();
		if (status == "") {
      $("label#status_error").show();
      $("textarea#status").focus();
      return false;
    }
		
		var dataString = 'status='+ status;
		//alert (dataString);return false;
		
		$.ajax({
      type: "POST",
      url: "../inc/status.php",
      data: dataString,
      success: function() {
        $('#status_update').html("<div id='message'></div>");
        $('#message').html("<span style='color:black'>Status Updated!</span>")
        .append("")
        .hide()
        .fadeIn(1500, function() {
          $('#message').append("");
        });
      }
     });
    return false;
	});
});
runOnLoad(function(){
  $("textarea#status").select().focus();
});
