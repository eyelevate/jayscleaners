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
			var item_qty = (parseInt($("#itemQty").val(),false) === 0) ? 1 : parseInt($("#itemQty").val(),false);

			$("#invoiceModal-item_name").html(item_name);
			$("#invoiceModal-item_id").val(item_id);
			$("#invoiceModal-total_qty").html(item_qty);
			$('#colorForm-total').val(item_qty);
			$('#colorForm-qty').val(0);
			$('#colorForm-id').val(item_id);
		});

		$(".number").click(function(){
			var current_quantity = $("#itemQty").val();
			var new_quantity = $(this).attr('id').replace('number-','');
			var quantity = invoices.quantity(current_quantity,new_quantity);
			$("#itemQty").val(quantity);
			$("#itemQtySpan").html(quantity);
		});
		$(".number-clear").click(function() {

			$("#itemQty").val(0);
			$("#itemQtySpan").html('--');
		});

		$(".colorBtn").click(function(e){
			var color = $(this).find('.colorsName').val();
			var color_id = $(this).find('.colorsId').val();
			var item_id = $("#colorForm-id").val();
			if(invoices.checkColorCount()) {
				alert('Total color count cannot exceed total item count.');
			} else {
				invoices.addColorRow(color, color_id);
			}
			
		});

		$(document).find("#selectedColorsUl .close").on('close.bs.alert', function () {
			alert('clicked');
			invoices.updateColorCount();
		});
	},
	checkColorCount: function(){
		var count = 0;
		var total = parseInt($("#colorForm-total").val(),false);
		$(document).find('#selectedColorsUl li').each(function(){
			count += parseInt($(this).attr('quantity'),false);
		});

		return (count >= total) ? true : false;
	},
	addColorRow: function(color, color_id) {
		var qty = $(document).find('#selectedColorsUl li[color_id='+color_id+']').length;
		var total = parseInt($("#colorForm-total").html(),false);

		if(qty > 0) { // update row
			$(document).find("#selectedColorsUl .alert").removeClass('alert-info').addClass('alert-default');
			invoices.editColorRow(color_id);
		} else { // create new row
			$(document).find("#selectedColorsUl .alert").removeClass('alert-info').addClass('alert-default');
			$(document).find('#selectedColorsUl li[color_id='+color_id+'] .alert').addClass('alert-info');
			$(document).find("#selectedColorsUl").prepend(generate.colorRow(color_id, color));
		}

		invoices.updateColorCount();
	},
	editColorRow: function(color_id) {
		var qty = parseInt($(document).find('#selectedColorsUl li[color_id='+color_id+']').attr('quantity'),false) + 1;
		$(document).find('#selectedColorsUl li[color_id='+color_id+']').attr('quantity',qty).find('.colorQty').html(qty);
		$(document).find('#selectedColorsUl li[color_id='+color_id+']').find('.alert').removeClass('alert-default').addClass('alert-info');
		invoices.updateColorCount();
	},

	updateColorCount: function() {
		var total = parseInt($("#invoiceModal-total_qty").html(),false);
		var count = 0;
		$(document).find('#selectedColorsUl li').each(function(){
			count += parseInt($(this).attr('quantity'), false);
		});

		$("#colorForm-qty").val(count);
		$("#invoiceModal-item_qty").html(count);
	},
	addNewInvoiceRow: function() {

	},
	quantity: function(current_quantity, new_quantity) {
		var quantity = 0;

		var new_number = (current_quantity+''+new_quantity);
		new_number = new_number.replace(/^0+/, '');
		if(parseInt(new_number,false) > 0 && parseInt(new_number,false) < 1000){
			quantity = new_number;
		} else if(parseInt(new_number, false) === 0) {
			quantity = 1;
		}


		return quantity;
	
	},
	reset: function() {
		// reset qty counters
		$("#item_qty, #colorQty").val(0);
		$("#itemQtySpan, #colorQtySpan").html('--');

		// reset colors

		// reset items
		$("#invoiceModal-item_name").html('');
		$("#invoiceModal-item_id").val(null);
		$("#invoiceModal-total_qty").html('');

		// reset memos

	}

};

generate = {
	colorRow:function(color_id, color_name) {
		var qty = 1;
		return '<li class="col-md-12 col-sm-12 col-xs-12 col-lg-12" color_id="'+color_id+'" quantity="'+qty+'">'+
					'<div class="alert alert-info alert-dismissible colorRow" role="alert">'+
						'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
						'<span class="badge colorQty" style="font-size:18px">'+qty+'</span> <strong class="colorName" style="font-size:15px;">'+color_name+'</strong>'+
					'</div>'+
				'</li>';
	}
};