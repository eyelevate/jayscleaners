$(document).ready(function(){
	pickup.pageLoad();
	pickup.events();
});

pickup = {
	pageLoad: function(){
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function(){
		$("#invoice_tbody tr").click(function(){
			if ($(this).hasClass('success')) {
				$(this).removeClass('success');
			} else {
				$(this).addClass('success');
			}

			requests.select_invoice();
		});
		$("#amount_tendered").priceFormat({
			'prefix':'',
			limit:10
		});
		$(".calc_button").click(function() {
			var number = $(this).attr('value');
			var display = ($("#amount_tendered").val() > 0) ? $("#amount_tendered").val() : '';

			switch(number) {
				case 'C':
					display = '0';
				break;

				case '00':
					display += '00';
				break;

				default:
					display += ''+number;
				break;
			}
			display = parseInt(display,false);
			var tendered = display;
			var tendered_exact = (tendered / 100);
			var tendered_display = $.number(tendered_exact,2);
			$("#amount_tendered_display").html('$'+tendered_display);
			$("#amount_tendered").val(tendered);
			$("#amount_tendered_exact").val((tendered_exact).toFixed(2));

			var amount_due = parseFloat($("#amount_due_cash").attr('total'),false);
			var change = $.number(tendered_exact - amount_due,2);
			$("#change").val('$'+change);
		});

		$(".finish_button").click(function(){
			$("#invoice_form").html('');
			var type = $(this).attr('id').replace('finish-','');
			console.log(type);
			var type_input = '<input type="hidden" name="type" value="'+type+'" />';
			$("#invoice_form").append(type_input);

			switch(type) {
				case 'credit':
				var last_four = $("#last_four_credit").val();
				$('#invoice_form').append('<input type="hidden" name="last_four" value="'+last_four+'" />');
				break;

				case 'cash':
				var tendered = $("#amount_tendered_exact").val();
				$('#invoice_form').append('<input type="hidden name="tendered" value="'+tendered+'" />');
				break;

				case 'cof':
				var payment_id = $("#payment_id").val();
				$('#invoice_form').append('<input type="hidden name="payment_id" value="'+payment_id+'" />');
				break;

				default:
				var check_number = $("#last_four_check").val();
				$('#invoice_form').append('<input type="hidden" name="last_four" value="'+check_number+'" />');
				break;
			}

			$("#selected_tbody").find('tr').each(function(e){
				var invoice_id = $(this).attr('id').replace('selected_tr-','');
				var selected_input = '<input name="invoice_id['+e+']" value="'+invoice_id+'"/>';
				$("#invoice_form").append(selected_input);
			});

			$("#invoiceForm").submit();
		});
	}
};

requests = {
	select_invoice:function(){
		console.log('test');
		customer_id = $("#customer_id").val();
		console.log(customer_id);
		invoice_ids = [];
		$("#invoice_tbody").find('tr.success').each(function(e){
			var id = $(this).attr('id').replace('invoice_tr-','');
			invoice_ids[e] = id;
		});

		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/invoices/select',
			{
				"_token": token,
				"invoice_ids": invoice_ids,
				"customer_id": customer_id
			},function(result){
				if (result.status) {
					var totals = result.invoice_data.totals;
					var credits = parseFloat(result.credits);
					var total_due = parseFloat(result.total_due);
					var total_due_html = result.total_due_html;

					$("#selected_tbody").html(result.invoice_data.invoices);
					$("#subtotal_td").html(totals.subtotal_html);
					$("#tax_td").html(totals.tax_html);
					$("#quantity_td").html(totals.quantity);
					$("#total_td").html(totals.total_html);
					$("#due_td").html(total_due_html);

					$(".amount_due").attr('total',total_due).val(total_due_html);
				} else{
					$("#selected_tbody").html('');
					$("#subtotal_td").html('$0.00');
					$("#tax_td").html('$0.00');
					$("#quantity_td").html('0');
					$("#discount_td").html('$0.00');
					$("#total_td").html('$0.00');
					$("#due_td").html('$0.00');
					$(".amount_due").attr('total','0').val('$0.00');
				}
			}
		);
	}
};