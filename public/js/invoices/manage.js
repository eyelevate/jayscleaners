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
		$(".item_value").on('click',function(){
			$(this).val('');
			invoices.focused();
			
		});





	},
	focused: function() {
		// parse through the rest and place old value back if empty
		$('.item_value').each(function(){
			if ($(this).is(':focus')) {
				console.log('focused');
			} else {
				var old_value = $(this).attr('old');
				if ($(this).val() === '') {
					$(this).val(old_value);
				}
				
			}
		});
	}
};

requests = {

	get_item: function(search) {

	},

	get_totals: function(amount, company_id, direction) {

	}
	
};