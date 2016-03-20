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


		$("#editColor").click(function(){
			invoices.resetAll();
			var item_id = $('#invoiceSummaryTable tbody').find('tr.success').attr('item-id');
			var item_name = $('#invoiceSummaryTable tbody').find('tr.success').find('.itemTr-name').html();
			invoices.addColorsFromInvoice(item_id, item_name);

		});
		$(".colorBtn").click(function(e){
			var qty = 1;
			var color = $(this).find('.colorsName').val();
			var color_id = $(this).find('.colorsId').val();
			var color_hex = $(this).find('.colorsColor').val();
			var color_idx = $("#selectedColorsTable tbody").find('tr.success').attr('item-idx');
			var colorItem = generate.colorItem(color_idx,color,color_hex, color_id);
			$("#selectedColorsTable tbody").find('tr.success .colorTd').html(colorItem);
			//check next tr row
			if($("#selectedColorsTable tbody").find('tr.success').next('tr').length == 1) {
				element = $("#selectedColorsTable tbody").find('tr.success').next('tr');
				$("#selectedColorsTable tbody").find('tr').removeClass('success');
				element.addClass('success');
			} else {
				element = $("#selectedColorsTable tbody").find('tr:first');
				$("#selectedColorsTable tbody").find('tr').removeClass('success');
				element.addClass('success');
			}

			invoices.updateColorCount();
			
		});


		$("#colorCancel").click(function(){
			invoices.resetAll();
		});

		$("#colorUpdate").click(function(){
			color_string = '';
			colors = [];
			$("#selectedColorsTable tbody").find('tr').each(function(e){
				var item_idx = $(this).attr('item-idx');
				var item_id = $(this).attr('item-id');
				var item_color = $(this).find('input').attr('color');
				var color_id = $(this).find('input').attr('color_id');
				colors[color_id] = item_id;
				// update main form inputs
				$("#invoice-form").find('.invoiceItem-color[item-idx="'+item_idx+'"]').attr('color-name',item_color).val(color_id);
			});

			for (var k in colors){
				var item_id = colors[k];
				var color_count = $("#invoice-form").find('.invoiceItem-color[value="'+k+'"][item-id="'+item_id+'"]').length;
				var color_name =  $("#invoice-form").find('.invoiceItem-color[value="'+k+'"]:first').attr('color-name');
				color_string += color_count+' - '+color_name+' /';
			}

			
			$("#invoiceSummaryTable tbody").find('tr.success .itemTr-color').html(color_string);
		});

	},
	addColorsFromInvoice: function(item_id, item_name) {
		var colorQty = $("#invoice-form").find('input.invoiceItem-color[item-id="'+item_id+'"]').length;
		// send item_id and item name to the colors modal
		$("#invoiceModal-item_name").html(item_name);
		$("#invoiceModal-total_qty").html(colorQty);
		element = $("#invoice-form").find('input.invoiceItem-color[item-id="'+item_id+'"]');
		element.each(function(e){
			var idx = $(this).attr('item-idx');

			var status = ($(this).val()=== '') ? false : true;
			var color_items = generate.colorRow(item_id,item_name,idx, e, status);
			$("#selectedColorsTable tbody").append(color_items);

		});
		$(document).find("#selectedColorsTable tr").click(function(){
			$("#selectedColorsTable tbody").find('tr').removeClass('success');
			$(this).addClass('success');
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

	updateColorCount: function() {
		var count = 0;
		$("#selectedColorsTable tbody").find('tr').each(function(e){
			if($(this).find('input').length > 0) {
				count++;
			}
		});
		$("#invoiceModal-item_qty").html(count);
	},
	addInvoiceTableRow: function(item_id, item_name, qty, price) {
		// check for existing tr
		$("#invoiceSummaryTable tbody tr").removeClass('success');

		if($(document).find('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').length > 0) { // exists so only update
			var old_qty = parseInt($('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').attr('qty'), false);
			var new_qty = old_qty + parseInt(qty,false);
			var new_price = $.number(new_qty*price,2);
			$('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').attr('qty',new_qty).addClass('success').find('.itemTr-qty').html(new_qty);
			$('#invoiceSummaryTable tbody tr[item-id="'+item_id+'"]').find('.itemTr-price').html(new_price);

		} else { // Does not exist create a new row
			//insert new row into table
			var newTableRow = generate.invoiceRow(item_id, item_name, qty, price);
			$("#invoiceSummaryTable tbody").prepend(newTableRow);
		}


		//insert new rows into the main invoice form
		var invoiceItem = generate.formItem(item_id,qty,price);

		$("#invoice-form").append(invoiceItem);

		// update total fields TODO
		var pre_tax = '';
		var tax_rate = '';
		var total = '';

		//add scripts
		$("#invoiceTr-"+item_id).click(function(){
			$(this).parents('tbody:first').find('.invoiceTr').removeClass('success');
			$(this).addClass('success');

		});
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

	resetItemQty: function() {
		$("#itemQty").val(0);
		$("#itemQtySpan").html('--');
	},
	resetAll: function() {
		// reset qty counters
		$("#itemQty, #colorQty").val(0);
		$("#itemQtySpan, #colorQtySpan").html('--');

		// reset colors
		$(document).find("#selectedColorsTable tbody tr").remove();
		invoices.updateColorCount();
		// reset items
		$("#invoiceModal-item_name").html('');
		$("#invoiceModal-item_id").val(null);
		$("#invoiceModal-total_qty").html('');

		// reset memos

	}

};

generate = {
	colorRow:function(item_id,item_name,idx,e, status) {
		var success = (e === 0) ? 'success' : '';
		var rows = '';
		if(status === true){
			var color = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').attr('color-name');
			var color_id = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
			var hex = $(".colorsId[value='"+color_id+"']").parents('button:first').find('.colorsColor').val();
			var item = generate.colorItem(idx, color, hex, color_id);
			rows = '<tr id="colorTr-'+item_id+'" class="colorTr '+success+'" item-id="'+item_id+'" item-idx="'+idx+'" style="cursor:pointer;">'+
					'<td>'+idx+'</td>'+
					'<td>'+item_name+'</td>'+
					'<td id="colorTd-'+idx+'" class="colorTd">'+item+'</td>'+
				'</tr>';
		} else {
			rows = '<tr id="colorTr-'+item_id+'" class="colorTr '+success+'" item-id="'+item_id+'" item-idx="'+idx+'" style="cursor:pointer;">'+
					'<td>'+idx+'</td>'+
					'<td>'+item_name+'</td>'+
					'<td id="colorTd-'+idx+'" class="colorTd"></td>'+
				'</tr>';
		}
		return rows;
	},
	colorItem: function(idx, color, hex, id){
		return '<input name="colorSelected-'+idx+'" type="color" value="'+hex+'" color_id="'+id+'" color="'+color+'" disabled="true"/>';
	},
	invoiceRow: function(item_id, item_name, qty, price) {
		return '<tr id="invoiceTr-'+item_id+'" class="invoiceTr success" item-id="'+item_id+'" qty="'+qty+'" style="cursor:pointer;">'+
					'<td class="itemTr-qty">'+qty+'</td>'+
					'<td class="itemTr-name">'+item_name+'</td>'+
					'<td class="itemTr-color"></td>'+
					'<td class="itemTr-memo"></td>'+
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
			item += '<input class="invoiceItem-color" type="hidden" value="" name="item['+item_id+'][color]" item-idx="'+i+'" item-id="'+item_id+'" color-name=""/>';
			item += '<input class="invoiceItem-memo" type="hidden" value="" name="item['+item_id+'][memo]" item-idx="'+i+'" item-id="'+item_id+'"/>';
		}

		return item;
	}
};