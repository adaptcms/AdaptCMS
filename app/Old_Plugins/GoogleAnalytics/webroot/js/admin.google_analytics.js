$(document).ready(function() {
	callDatePicker();

	$('.update-stats').live('submit', function(e) {
		e.preventDefault();

		var url = $(this).attr('action') + '/?' + $(this).serialize();

		$.blockUI({ 
			message: 'Loading Stats...',
			css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff' 
	        }
        });

		$('.google-analytics-container').load(url + ' .google-analytics-container .inner', function() {
			callDatePicker();

			$('#chart').html('');
			drawChart();
			
			$.unblockUI();
		});
	});
});

function callDatePicker()
{
	$('input.datepicker').datepicker();
}

function drawChart2()
{
	var data_sets = [];
	$.each($('#chart-data'), function() {

	});

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Views');
    data.addColumn('number', 'Uniques');
    data.addRows(data_sets);

    var chart = new google.visualization.AreaChart(document.getElementById('chart'));
    chart.draw(data, {
        width: 940,
        height: 240,
        colors:['#22AADD', '#0099CC'],
        areaOpacity: 0.1,
        hAxis: {textPosition: 'in', showTextEvery: 2, slantedText: true, textStyle: { color: '#058dc7', fontSize: 10 } },
        pointSize: 5,
        chartArea:{left:40,top:5,width:"790",height:"230"}
    });
}

function drawChart()
{
	var data_sets = [];
	$.each($('#chart-data .set'), function() {
		var set = $.trim($(this).html()).split(',');
		set[1] = Number(set[1]);
		set[2] = Number(set[2]);

		data_sets.push( set );
	});

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Views');
    data.addColumn('number', 'Uniques');
    data.addRows(data_sets);

    var chart = new google.visualization.AreaChart(document.getElementById('chart'));
    chart.draw(data, {
        width: 940,
        height: 240,
        colors:['#22AADD', '#0099CC'],
        areaOpacity: 0.1,
        hAxis: {textPosition: 'in', showTextEvery: 2, slantedText: true, textStyle: { color: '#058dc7', fontSize: 10 } },
        pointSize: 5,
        chartArea:{left:40,top:5,width:"790",height:"230"}
    });
}