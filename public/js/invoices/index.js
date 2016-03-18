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
			var qty = (parseInt($("#itemQty").val(),false) === 0) ? 1 : parseInt($("#itemQty").val(),false);
			var price = parseFloat($(this).find('.item-price').val(),false);
			// create or edit an existing row
			invoices.addInvoiceTableRow(item_id, item_name, qty, price);

			// //reset
			// invoices.resetItemQty();
		});

		$(document).find("#invoiceSummaryTable .invoiceTr").on('click',function(){
			alert('clicked');
			var item_id = $(this).attr('item-id');
			var item_name = $(this).find('.itemTr-name').val();
			var item_qty = (parseInt($(this).attr('qty'),false) === 0) ? 1 : parseInt($(this).attr('qty'),false);

			console.log('item_id='+item_id);
			$("#invoiceModal-item_name").html(item_name);
			$("#invoiceModal-item_id").val(item_id);
			$("#invoiceModal-total_qty").html(item_qty);
			$('#colorForm-total').val(item_qty);
			$('#colorForm-qty').val(0);
			$('#colorForm-id').val(item_id);

			//reset
			invoices.resetItemQty();
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
		$(".numberColors").click(function(){
			var current_quantity = $("#colorQty").val();
			var new_quantity = $(this).attr('id').replace('numberColors-','');
			var quantity = invoices.quantity(current_quantity,new_quantity);
			$("#colorQty").val(quantity);
			$("#colorQtySpan").html(quantity);
		});
		$(".numberColors-clear").click(function() {

			$("#colorQty").val(0);
			$("#colorQtySpan").html('--');
		});

		$(".colorBtn").click(function(e){
			var qty = $("#colorQty").val();
			var color = $(this).find('.colorsName').val();
			var color_id = $(this).find('.colorsId').val();
			var item_id = $("#colorForm-id").val();
			if(invoices.checkColorCount(qty)) {
				alert('Total color count cannot exceed total item count.');
				invoices.resetColorQty();
			} else {
				invoices.addColorRow(color, color_id, qty);
			}
			
		});

		$("#colorCancel").click(function(){
			invoices.resetAll();
		});

		$("#colorUpdate").click(function(){

		});

	},
	checkColorCount: function(qty){
		qty = (parseInt(qty,false) === 0) ? 1 : parseInt(qty,false);
		var count = 0;
		var total = parseInt($("#colorForm-total").val(),false);
		$(document).find('#selectedColorsUl li').each(function(){
			count += parseInt($(this).attr('quantity'),false);
		});

		var check = count + qty;
		return (check > total) ? true : false;
	},
	addColorRow: function(color, color_id, quantity) {
		var qty = $(document).find('#selectedColorsUl li[color_id='+color_id+']').length;
		var total = parseInt($("#colorForm-total").html(),false);

		if(qty > 0) { // update row
			$(document).find("#selectedColorsUl .alert").removeClass('alert-info').addClass('alert-default');
			invoices.editColorRow(color_id, quantity);
		} else { // create new row
			$(document).find("#selectedColorsUl .alert").removeClass('alert-info').addClass('alert-default');
			$(document).find('#selectedColorsUl li[color_id='+color_id+'] .alert').addClass('alert-info');
			$(document).find("#selectedColorsUl").prepend(generate.colorRow(color_id, color, quantity));
			$(document).find('#selectedColorsUl #close-'+color_id).bind('close.bs.alert', function () {
				invoices.updateBeforeColorCount(color_id);
				$(document).find('#selectedColorsUl #close-'+color_id).remove();
			});
		}

		invoices.updateColorCount();
		invoices.resetColorQty();
	},
	editColorRow: function(color_id, quantity) {
		quantity = (parseInt(quantity,false) === 0) ? 1 : parseInt(quantity,false);
		var qty = parseInt($(document).find('#selectedColorsUl li[color_id='+color_id+']').attr('quantity'),false) + quantity;
		$(document).find('#selectedColorsUl li[color_id='+color_id+']').attr('quantity',qty).find('.colorQty').html(qty);
		$(document).find('#selectedColorsUl li[color_id='+color_id+']').find('.alert').removeClass('alert-default').addClass('alert-info');
		invoices.updateColorCount();
	},
	updateBeforeColorCount: function(color_id){
		var total = parseInt($("#invoiceModal-total_qty").html(),false);
		var count = 0;
		var subtract = parseInt($(document).find('#selectedColorsUl li[color_id='+color_id+']').attr('quantity'),false);
		$(document).find('#selectedColorsUl li').each(function(){
			count += parseInt($(this).attr('quantity'), false);
		});
		var new_count = count - subtract;
		count = (new_count < 0 ) ? 0 : new_count;


		$("#colorForm-qty").val(count);
		$("#invoiceModal-item_qty").html(count);
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
	addInvoiceTableRow: function(item_id, item_name, qty, price) {
		// check for existing tr
		if($(document).find('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').length > 0) { // exists so only update
			var old_qty = parseInt($('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').attr('qty'), false);
			var new_qty = old_qty + parseInt(qty,false);
			var new_price = $.number(new_qty*price,2);
			$('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').attr('qty',new_qty).find('.itemTr-qty').html(new_qty);
			$('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').find('.itemTr-price').html(new_price);

		} else { // Does not exist create a new row
			//insert new row into table
			var newTableRow = generate.invoiceRow(item_id, item_name, qty, price);
			$("#invoiceSummaryTable tbody").prepend(newTableRow);
		}


		//insert new rows into the main invoice form
		var invoiceItem = generate.formItem(item_id,qty,price);

		$("#invoice-form").append(invoiceItem);

		// update total fields
		var pre_tax = '';
		var tax_rate = '';
		var total = '';

		//reset 
		invoices.resetAll();
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
	resetColorQty: function() {
		$("#colorQty").val(0);
		$("#colorQtySpan").html('--');
	},
	resetItemQty: function() {
		$("#itemQty").val(0);
		$("#itemQtySpan").html('--');
	},
	resetAll: function() {
		// reset qty counters
		$("#itemQty, #colorQty").val(0);
		$("#itemQtySpan, #colorQtySpan").html('--');

		// reset colors
		$(document).find("#selectedColorsUl li").remove();
		invoices.updateColorCount();
		// reset items
		$("#invoiceModal-item_name").html('');
		$("#invoiceModal-item_id").val(null);
		$("#invoiceModal-total_qty").html('');

		// reset memos

	}

};

generate = {
	colorRow:function(color_id, color_name, qty) {
		qty = (parseInt(qty,false) === 0) ? 1 : qty;
		return '<li id="close-'+color_id+'" class="closeColorAlert col-md-12 col-sm-12 col-xs-12 col-lg-12" color_id="'+color_id+'" quantity="'+qty+'">'+
					'<div class="alert alert-info alert-dismissible colorRow" role="alert">'+
						'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
						'<span class="badge colorQty" style="font-size:18px">'+qty+'</span> <strong class="colorName" style="font-size:15px;">'+color_name+'</strong>'+
					'</div>'+
				'</li>';
	},
	invoiceRow: function(item_id, item_name, qty, price) {
		return '<tr class="invoiceTr" data-toggle="modal" data-target="#item-update" item-id="'+item_id+'" qty="'+qty+'" style="cursor:pointer;">'+
					'<td class="itemTr-qty">'+qty+'</td>'+
					'<td class="itemTr-name">'+item_name+'</td>'+
					'<td class="itemTr-color"></td>'+
					'<td class="itemTr-price">'+$.number(price*qty,2)+'</td>'+
				'</tr>';
	},
	formItem: function(item_id,qty,price){
		var idx = $(document).find('#invoice-form .invoiceItem-id[item-id="'+item_id+'"]').length +1;
		var item = '';
		var total_qty = idx+parseInt(qty,false);
		for (var i = idx; i < total_qty; i++) {
			item += '<input class="invoiceItem-id" type="hidden" value="'+item_id+'" name="item['+item_id+'][item_id]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item +=	'<input class="invoiceItem-price" type="hidden" value="'+price+'" name="item['+item_id+'][price]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item += '<input class="invoiceItem-color" type="hidden" value="" name="item['+item_id+'][color]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item += '<input class="invoiceItem-memo" type="hidden" value="" name="item['+item_id+'][memo]" item-idx="'+i+'" item-id="'+item_id+'"/>';
		}

		return item;
	}
};