$(document).ready(function() {
	customers.pageLoad();
	customers.events();
});

customers = {
	pageLoad: function(){

	},
	events: function() {
		$("#invoiceTable tr").click(function(){
			$("#invoiceTable tr").removeClass('success');
			$(this).addClass('success');
		});
	}
};