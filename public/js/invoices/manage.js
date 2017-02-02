$(document).ready(function(){
	invoices.pageLoad();
	invoices.events();
});

invoices = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});


	},
	events: function() {
		$(".item_value").on('focus',function(){
			$(this).val('');
		});



	}
};

requests = {

	get_item: function(search) {

	},

	get_totals: function(amount, company_id, direction) {

	}
	
};