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
			// parse through the rest and place old value back if empty
			$('.item_value').not(':focus').each(function(){
				if ($(this).val() === '') {
					var old_value = $(this).attr('old');
					$(this).val(old_value);
				}
			});
			
		});





	}
};

requests = {

	get_item: function(search) {

	},

	get_totals: function(amount, company_id, direction) {

	}
	
};