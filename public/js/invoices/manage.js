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
		$("#search_item").click(function() {
			var search = $("#search_query").val();
			$("#invoice_item_id").val(search);
			requests.get_item(search);
		});
		$("#search_query").keypress(function(e) {
			if(e.which == 13) {
				$("#invoice_item_id").val(search);
				$("#search_item").trigger('click');
			}
		});

		$("#pretax").keypress(function(e) {
			if(e.which == 13) {
				pretax = $(this).val();
				company_id = $("#company_id option:selected").val();
				requests.get_totals(pretax,company_id,1);
			}
		});

		$("#total").keypress(function(e) {
			if(e.which == 13) {
				total = $(this).val();
				company_id = $("#company_id option:selected").val();
				requests.get_totals(total,company_id,2);
			}
		});

		$("#pretax").blur(function(){
			pretax = $(this).val();
			company_id = $("#company_id option:selected").val();
			requests.get_totals(pretax,company_id,1);
		});
		$("#total").blur(function() {
			total = $(this).val();
			company_id = $("#company_id option:selected").val();
			requests.get_totals(total,company_id,2);
		});



	}
};

requests = {

	get_item: function(search) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/invoices/manage-update',
			{
				"_token": token,
				"search": search,
			},function(result){
				if (result.status) {
					location_id = result.location_id;
					pretax = result.pretax;
					tax = result.tax;
					total = result.total;
					company_id = result.company_id;
					item = result.name;
					memo = result.memo;
					color = result.color;
					$("#company_id").val(company_id);
					$("#location").val(location_id);
					$("#pretax").val(pretax);
					$("#tax").val(tax);
					$("#total").val(total);
					$("#name").val(item);
					$("#color").val(color);
					$("#memo").val(memo);
				} else {
					$("#company_id").val(1);
					$("#location").val(1);
					$("#pretax").val('0.00');
					$("#tax").val('0.00');
					$("#total").val('0.00');
					$("#name").val('');
					$("#color").val('');
					$("#memo").val('');
				}
			}
		);
	},

	get_totals: function(amount, company_id, direction) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/invoices/manage-totals',
			{
				"_token": token,
				"amount": amount,
				"direction": direction,
				"company_id": company_id
			},function(result){
				if (result.status) {
					pretax = result.pretax;
					tax = result.tax;
					total = result.total;
					$("#pretax").val(pretax);
					$("#tax").val(tax);
					$("#total").val(total);
				} else {
					$("#pretax").val('0.00');
					$("#tax").val('0.00');
					$("#total").val('0.00');
				}
			}
		);
	}
	
};