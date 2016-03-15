$(document).ready(function(){
	invoices.pageLoad();
	invoices.events();
});

invoices = {
	pageLoad: function() {

	},
	events: function() {
		$(".items").click(function(){
			var item_name = $(this).find('.item-name').val();
			var item_id = $(this).find('.item-id').val();
			var item_qty = parseInt($("#item_qty").val(),false);
			$("#invoiceModal-item_name").html(item_name);
			$("#invoiceModal-item_id").val(item_id);
			$("#invoiceModal-total_qty").html(item_qty);
		});

		$(".number").click(function(){
			var current_quantity = $("#item_qty").val();
			var new_quantity = $(this).attr('id').replace('number-','');
			var quantity = invoices.quantity(current_quantity,new_quantity);
			$("#item_qty").val(quantity);
			$("#itemQtySpan").html(quantity);
		});
		$(".number-clear").click(function() {
			$("#item_qty").val(0);
			$("#itemQtySpan").html(0);
		});

		$(".colorBtn").click(function(e){
			invoices.addNewColor();
		});
	},
	addNewColorRow: function() {

	},
	deleteColorRow: function() {

	},
	addNewRow: function() {

	},
	quantity: function(current_quantity, new_quantity) {
		var quantity = 0;
		var new_number = (current_quantity+''+new_quantity);
		new_number = new_number.replace(/^0+/, '');
		if(parseInt(new_number,false) > 0 && parseInt(new_number,false) < 1000){
			quantity = new_number;
		}


		return quantity;
	
	},
	reset: function() {

	}

};