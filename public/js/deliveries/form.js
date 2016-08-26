$(document).ready(function(){
	form.pageLoad();
});

form = {
	pageLoad: function() {
		$('input.datepicker').Zebra_DatePicker({
			container:$("#pickup_container")}
		);
	}
};