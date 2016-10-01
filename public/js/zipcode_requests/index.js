$(document).ready(function(){
	zipcodes.pageLoad();
	zipcodes.events();
});

zipcodes = {
	pageLoad: function(){

	},
	events: function(){
		$.getJSON("/zipcode-requests/request-data", function (json) {
			//-----------------------
			//- MONTHLY SALES CHART -
			//-----------------------
			// // Get context with jQuery - using jQuery's .get() method.
			var reportsChartCanvas = $("#reportsChart").get(0).getContext("2d");
			var data = {
				datasets: [{
					data: json.datasets.data,
					backgroundColor: json.datasets.backgroundColor,
					label: 'Zipcodes' // for legend
				}],
				labels: json.labels
			};
			new Chart(reportsChartCanvas, {
				data: data,
				type: "polarArea",
				options: {
					elements: {
					arc: {
						borderColor: "#000000"
					}
				}
				}
			});

		});
	}
};