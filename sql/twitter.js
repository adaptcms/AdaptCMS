// Twitter Feed
	$.getJSON("http://search.twitter.com/search.json?callback=?&rpp=5&q=from:charliepage", function(data) {
		$.each(data.results, function(i, tweet) {
			var date_tweet = new Date(tweet.created_at);
			var date_now   = new Date();
			var date_diff  = date_now - date_tweet;
			var hours      = Math.round(date_diff/(1000*60*60));
			var minutes    = Math.round(date_diff/(1000*60));
			if (hours > 1) {
				$("#twitter-feed").append("<li>" + tweet.text + " <a href='http://www.twitter.com/charliepage/status/" + tweet.id_str + "' target='_blank'>" + hours + "&nbsp;hours&nbsp;ago</a></li>");
			} else if (hours == 0) {
				$("#twitter-feed").append("<li>" + tweet.text + " <a href='http://www.twitter.com/charliepage/status/" + tweet.id_str + "' target='_blank'>" + minutes + "&nbsp;minutes&nbsp;ago</a></li>");
			} else {
				$("#twitter-feed").append("<li>" + tweet.text + " <a href='http://www.twitter.com/charliepage/status/" + tweet.id_str + "' target='_blank'>" + hours + "&nbsp;hour&nbsp;ago</a></li>");
			}
		});

		var count = 1;

		$('#twitter-feed li:nth-child(1)').fadeIn();

		setInterval(function() {
			count++;
			if (count > $('#twitter-feed li').length) count = 1;
			$('#twitter-feed li').fadeOut();
			$('#twitter-feed li:nth-child(' + count + ')').fadeIn();
		}, 7000); 
	});
// End