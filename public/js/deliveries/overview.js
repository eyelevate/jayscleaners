$(document).ready(function(){
	delivery.pageLoad();
});

delivery = {
	pageLoad: function() {
		$('#delivery_date').Zebra_DatePicker({
			container:$("#delivery_date_container"),
			format:'D m/d/Y',
			start_date :'',
			onSelect: function(a, b) {
				console.log(b);
			}
		});
	}
};