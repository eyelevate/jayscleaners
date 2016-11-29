$(document).ready(function() {
	pay.pageLoad();
	pay.events();
});

pay = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},

	events: function() {
		$(".transaction_tr").click(function(){
			if ($(this).hasClass('success')) {
				$(this).removeClass('success');
				$(this).find('.transaction_id').prop('checked', false);
			} else {
				$(this).addClass('success');
				$(this).find('.transaction_id').prop('checked', true);
			}
			customer_id = $(this).attr('customer');
			request.update_total(customer_id);
		});
		$("#tendered").on("change paste keyup", function() {
			var tendered = parseFloat($("#tendered").val());
			var due = parseFloat($("#total").val());
			var change = (due - tendered).toFixed(2);
			$("#change").val(change);
		});

	}
};

request = {
	update_total: function(id) {
		var token = $('meta[name=csrf-token]').attr('content');
		transaction_ids = [];
		$(".transaction_tr.success").each(function(){
			transaction_id = $(this).attr('id').replace('transaction_tr-','');
			transaction_ids.push(transaction_id);
		});
		$.post(
			'/accounts/update-total',
			{
				"_token": token,
				"transaction_ids": transaction_ids,
				"customer_id":id
			},function(result){
				if (result.status) {
					pretax_format = result.pretax_format;
					tax_format = result.tax_format;
					aftertax_format = result.aftertax_format;
					credit_format = result.credit_format;
					discount_format = result.discount_format;
					total_format = result.total_format;
					$("#subtotal").html(pretax_format);
					$("#tax").html(tax_format);
					$("#aftertax").html(aftertax_format);
					$("#credits").html(credit_format);
					$("#discount").html(discount_format);
					$("#due").html(total_format);
					$("#tendered").val(result.total);
					$("#change").val('0.00');
					$("#total").val(result.total);

				}
			}
		);
	},
};