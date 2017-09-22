$(document).ready(function() {
	reports.pageLoad();
	reports.events();
	reports.sales();
	reports.dropoff();
});

reports = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},

	events: function() {
		$('#start').Zebra_DatePicker({
			format:'D m/d/Y'
		});
		$('#end').Zebra_DatePicker({
			format:'D m/d/Y'
		});

		$(".select_dates").click(function(){
			start = $(this).attr('start');
			end = $(this).attr('end');

			$("#start").val(start);
			$("#end").val(end);

			$(this).parents('form:first').submit();
		});
	},


	sales: function() {
		$.getJSON("/admins/sales-data", function (json) {
			//-----------------------
			//- MONTHLY SALES CHART -
			//-----------------------

			// Get context with jQuery - using jQuery's .get() method.
			var ctx = $("#salesChart");

			var salesChartData = {
				labels: json.labels,
				datasets: json.datasets
			};

			var options = {
	    		'maintainAspectRatio': false,
	    		'legend' : {'display':false},
	    		'scales' : {
	    			'xAxes':[
	    				{
	    					'gridLines' : {
	    						'drawOnChartArea': false,
	    					}
	    					
	    				}
	    			],
	    			'yAxes': [
	    				{
	    					'ticks': {
	    						'beginAtZero': true
	    					}

	    				}
	    			]
	    		},
	    		'elements' : {
	    			'point' : {
	    				'radius': 0,
	    				'hitRadius': 10,
	    				'hoverRadius': 4,
	    				'hoverBorderWidth': 3
	    			}
	    		}
	    	};

	    	console.log(salesChartData);
	    	console.log(options);
			return new Chart(ctx, {
				type: 'line',
				data: salesChartData,
				options: options
			});
			// //Create the line chart
			// salesChart.Line(salesChartData, salesChartOptions);
		});
	},

	dropoff: function() {
		$.getJSON("/admins/dropoff-data", function (json) {
			//-----------------------
			//- MONTHLY SALES CHART -
			//-----------------------
			// Get context with jQuery - using jQuery's .get() method.
			var ctx = $("#dropoffChart");
			// This will get the first returned node in the jQuery collection.
			var salesChart = new Chart(salesChartCanvas);

			var salesChartData = {
				labels: json.labels,
				datasets: json.datasets
			};

			var options = {
	    		'maintainAspectRatio': false,
	    		'legend' : {'display':false},
	    		'scales' : {
	    			'xAxes':[
	    				{
	    					'gridLines' : {
	    						'drawOnChartArea': false,
	    					}
	    					
	    				}
	    			],
	    			'yAxes': [
	    				{
	    					'ticks': {
	    						'beginAtZero': true
	    					}

	    				}
	    			]
	    		},
	    		'elements' : {
	    			'point' : {
	    				'radius': 0,
	    				'hitRadius': 10,
	    				'hoverRadius': 4,
	    				'hoverBorderWidth': 3
	    			}
	    		}
	    	};
	    	console.log(salesChartData);
	    	console.log(options);
			return new Chart(ctx, {
				type: 'line',
				data: salesChartData,
				options: options
			});
		});
	}
};