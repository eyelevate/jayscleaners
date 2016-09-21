$(document).ready(function(){
	checklist.pageLoad();
	checklist.events();
});

checklist = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$( ".schedule_table tbody" ).on( "click", ".invoices_tr", function() {
			var schedule_id = $(this).find('.schedule_ids').val();
			var invoice_id = $(this).find('.invoice_ids').val();
			
			// toggle the selected classes and update the session data
			if ($(this).hasClass('success')) {
				// remove class and remove item from session
				$(this).removeClass('success');
				$(this).find('.invoice_ids').prop('checked', false);
				requests.remove_invoice_row(schedule_id, invoice_id);

				
			} else {
				// add class and add to session
				$(this).addClass('success');
				$(this).find('.invoice_ids').prop('checked', true);

				requests.select_invoice_row(schedule_id, invoice_id);
			}
		});


	}
};

requests = {
	select_invoice_row: function(schedule_id, invoice_id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/schedules/select-invoice-row',
			{
				"_token": token,
				"schedule_id": schedule_id,
				"invoice_id": invoice_id
			},function(result){
				status = result.status;
				totals = result.totals;
				if (status) {
					$("#total_qty-"+schedule_id).html(totals.quantity);
					$("#total_subtotal-"+schedule_id).html(totals.subtotal_html);
					$("#total_tax-"+schedule_id).html(totals.tax_html);
					$("#total_total-"+schedule_id).html(totals.total_html);
				}
				
			}
		);
	},
	remove_invoice_row: function(schedule_id, invoice_id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/schedules/remove-invoice-row',
			{
				"_token": token,
				"schedule_id": schedule_id,
				"invoice_id": invoice_id
			},function(result){
				status = result.status;
				totals = result.totals;
				if (status) {
					$("#total_qty-"+schedule_id).html(totals.quantity);
					$("#total_subtotal-"+schedule_id).html(totals.subtotal_html);
					$("#total_tax-"+schedule_id).html(totals.tax_html);
					$("#total_total-"+schedule_id).html(totals.total_html);
				}
				
			}
		);
	},
};