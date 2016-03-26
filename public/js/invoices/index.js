$(document).ready(function(){
	invoices.pageLoad();
	invoices.events();
	calendars.events();
	calculator.events();
});

invoices = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});

		$("#priceCalculatorInput").priceFormat({
			'prefix':'',
			limit:10
		});

		//repopulate invoice table based on form data
		invoices.repopulateInvoiceTable();
		invoices.recalculateTotals();
		
	},
	events: function() {
		$(".items").click(function(){
			var item_name = $(this).find('.item-name').val();
			var item_id = $(this).find('.item-id').val();
			var qty = (parseInt($("#itemQty").val(),false) === 0) ? 1 : parseInt($("#itemQty").val(),false);
			var price = parseFloat($(this).find('.item-price').val(),false);
			// create or edit an existing row
			invoices.addInvoiceTableRow(item_id, item_name, qty, price);
			invoices.recalculateTotals();
			// //reset
			// invoices.resetItemQty();
		});

		$(".invoiceTr").click(function(){
			$(".invoiceTr").removeClass('success');
			$(this).addClass('success');
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
				color_string += (parseInt(color_count,false) > 0) ? color_count+'-'+color_name+' / ' : '';
			}

			
			$("#invoiceSummaryTable tbody").find('tr.success .itemTr-color').html(color_string.substring(0,color_string.length-2));

		});

		$("#editQty").click(function(){
			invoices.resetAll();
			var item_id = $('#invoiceSummaryTable tbody').find('tr.success').attr('item-id');
			var item_name = $('#invoiceSummaryTable tbody').find('tr.success').find('.itemTr-name').html();
			invoices.editQtyFromInvoice(item_id, item_name);
		});

		$("#deleteQty-selected").click(function(){
			$("#qtyTable tbody").find('tr').each(function(e){
				if($(this).hasClass('danger')){
					var idx = $(this).attr('item-idx');
					$(this).remove();
					$("#invoice-form").find('div.formItemsDiv[item-idx="'+idx+'"]').remove();
				}

			});

			//reindex idx & update invoice table
			invoices.reindexIdx();
			invoices.recalculateTotals();
		});

		$("#deleteQty-all").click(function(){
			$("#qtyTable tbody").find('tr').each(function(e){
				var idx = $(this).attr('item-idx');
				$(this).remove();
				$("#invoice-form").find('div.formItemsDiv[item-idx="'+idx+'"]').remove();
			});
			//reindex idx & update invoice table
			invoices.reindexIdx();
		});

		//Memo
		$("#editMemo").click(function(){
			invoices.resetAll();
			var item_id = $('#invoiceSummaryTable tbody').find('tr.success').attr('item-id');
			var item_name = $('#invoiceSummaryTable tbody').find('tr.success').find('.itemTr-name').html();
			invoices.editMemoFromInvoice(item_id, item_name);
		});
		$(".memoLi").click(function(){
			var item_id=$(this).parents('ul:first').attr('item-id');
			var item_idx = $(this).parents('ul:first').attr('item-idx');
			var memo_id = $(this).find(".memosId").val();
			//make active
			if($(this).hasClass('active')) {
				$(this).removeClass('active').removeClass('alert-info').addClass('alert-default');
				$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] ul li[memo-id="'+memo_id+'"]').remove();
			} else {
				var memoli = generate.memoLi(memo_id);
				$(this).addClass('active').removeClass('alert-default').addClass('alert-info');
				$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] ul').append(memoli);
			}

			var memo = generate.createMemo();
			// updte memo input
			$("#memoInput").val(memo);
		});

		$("#memoAdd").click(function(){
			var item_id = $("#memosUl").attr('item-id');
			var item_idx = $("#memosUl").attr('item-idx');
			var memo_string = $("#memoInput").val();

			$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] .invoiceItem-memo').val(memo_string);
			$("#memoTable tbody").find('.success .memoTd').html(memo_string);
			invoices.resetMemoList();
		});

		//clear memo
		$("#clearMemo").click(function() {
			$(".memoLi").each(function(){
				var item_id = $(this).parents('ul:first').attr('item-id');
				var item_idx = $(this).parents('ul:first').attr('item-idx');
				var memo_id = $(this).find(".memosId").val();

				//remove active and ul in form 
				if($(this).hasClass('active')) {
					$(this).removeClass('active').removeClass('alert-info').addClass('alert-default');
					$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] ul li[memo-id="'+memo_id+'"]').remove();
				}
				// update memo input
				
			});
			$("#memoInput").val('');
			// remove the value from the form
			var item_id = $("#memoUl").attr('item-id');
			var item_idx = $("#memoUl").attr('item-idx');
			$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] .invoiceItem-color').val('');
		});


		// final memo accept 
		$("#memo-accept").click(function(){
			var memo_string = '';
			$("#memoTable").find('tr').each(function(){
				var item_id = $(this).attr('item-id');
				var item_idx = $(this).attr('item-idx');
				$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+item_idx+'"] .invoiceItem-memo').each(function(){
					var memo = $(this).val();
					if(memo !== '') {
						memo_string += $(this).val()+' / ';
					}
				});
			});
			var item_id = $("#invoiceSummaryTable tbody").find('.success').attr('item-id');

			$("#invoiceSummaryTable tbody").find('.success .itemTr-memo').html(memo_string.substring(0,memo_string.length-2));
		});

		// Due date
		$("#calendar-selected").click(function(){
			var temp_date = $("#temp_date").val();
			var hour_selected = $('#due_temp_hours option:selected').val();
			var minutes_selected = $('#due_temp_minutes option:selected').val();
			var ampm_selected = $('#due_temp_ampm option:selected').val();
			var time_selected = hour_selected+':'+minutes_selected+''+ampm_selected;
			var date_time_selected = moment(temp_date).format('ddd, MM/DD, ')+''+time_selected;
			$("#openCalendar").find('h4').html(date_time_selected);
			
			if(temp_date !== '') {
				$("#due_date").val(temp_date+' '+time_selected);
			}
			
		});

		// Price
		$("#editPrice").click(function(){
			invoices.resetAll();
			var item_id = $('#invoiceSummaryTable tbody').find('tr.success').attr('item-id');
			var item_name = $('#invoiceSummaryTable tbody').find('tr.success').find('.itemTr-name').html();
			invoices.editPriceFromInvoice(item_id, item_name);
		});

		//print
		$(".printSelection").click(function(){
			var print = ($(this).attr('id').replace('printSelection-','') === 'store') ? true : false;
			$("#store_copy").val(print);
			$("#invoice-form").submit();
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
			var old_qty = $("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"]').length;
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

		//add scripts
		$("#invoiceTr-"+item_id).click(function(){
			$(this).parents('tbody:first').find('.invoiceTr').removeClass('success');
			$(this).addClass('success');

		});
		//reset 
		invoices.resetAll();
	},
	editMemoFromInvoice: function(item_id, item_name) {
		invoices.resetAll();
		// send item_id and item name to the colors modal
		element = $("#invoice-form").find('input.invoiceItem-id[item-id="'+item_id+'"]');
		element.each(function(e){
			var idx = $(this).attr('item-idx');
			var items = generate.memoRow(item_id,item_name,idx);
			$("#memoTable tbody").append(items);

			$("#memoTr-"+idx).click(function(){
				invoices.resetMemoList();
				$(this).parents('tbody:first').find('tr').removeClass('success');
				$(this).addClass('success');
				// send the proper variables to the memo modal
				$("#memosUl").attr('item-idx',idx).attr('item-id',item_id);

				// check to see what memo items were previously clicked
				$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+idx+'"] ul li').each(function(){
					var memo_id = $(this).attr('memo-id');
					$('#memo-'+memo_id).addClass('active').removeClass('alert-default').addClass('alert-info');
				});
				// repopulate the memo input
				var memo_string = $("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"][item-idx="'+idx+'"] .invoiceItem-memo').val();
				$("#memoInput").val(memo_string);

			});
		});

	},
	editPriceFromInvoice: function(item_id, item_name) {
		invoices.resetAll();
		// send item_id and item name to the colors modal
		element = $("#invoice-form").find('input.invoiceItem-id[item-id="'+item_id+'"]');
		element.each(function(e){
			var idx = $(this).attr('item-idx');
			var items = generate.priceRow(item_id,item_name,idx);
			$("#priceTable tbody").append(items);

			$("#priceTr-"+idx).click(function(){
				$(this).parents('tbody:first').find('tr').removeClass('success');
				$(this).addClass('success');
				var price = $("#priceInput-"+idx).val();
				$("#priceCalculatorInput").val(price).attr('status',1);
			});
		});

	},
	editQtyFromInvoice: function(item_id, item_name) {
		invoices.resetAll();
		var itemQty = $("#invoice-form").find('input.invoiceItem-id[item-id="'+item_id+'"]').length;
		// send item_id and item name to the colors modal
		$("#qtyModalTotal").html(itemQty);
		element = $("#invoice-form").find('input.invoiceItem-id[item-id="'+item_id+'"]');
		element.each(function(e){
			var idx = $(this).attr('item-idx');
			var items = generate.qtyRow(item_id,item_name,idx);
			$("#qtyTable tbody").append(items);

			$("#qtyTr-"+idx).click(function(){
				if($(this).hasClass('danger')) {
					$(this).removeClass('danger');
					$(this).find('input[type="checkbox"]').removeAttr('checked');
				} else {
					$(this).addClass('danger');
					$(this).find('input[type="checkbox"]').prop('checked', true);

				}
			});
		});

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
	reindexIdx: function() {
		$("#invoice-form").find('div.formItemsDiv').each(function(e){
			var idx = e+1;
			var item_id = $(this).attr('item-id');
			var inventory_id = $("#item-"+item_id).find('.item-inventory_id').val();
			$(this).attr('item-idx',idx);
			$(this).find('.invoiceItem-id').attr('item-idx',idx).attr('name','item['+inventory_id+']['+idx+']['+item_id+'][item_id]');
			$(this).find('.invoiceItem-price').attr('item-idx',idx).attr('name','item['+inventory_id+']['+idx+']['+item_id+'][price]');
			$(this).find('.invoiceItem-memo').attr('item-idx',idx).attr('name','item['+inventory_id+']['+idx+']['+item_id+'][memo]');
			$(this).find('.invoiceItem-color').attr('item-idx',idx).attr('name','item['+inventory_id+']['+idx+']['+item_id+'][color]');
		});

		invoices.repopulateInvoiceTable();
	},
	repopulateInvoiceTable: function(){
		// variables
		color_string = '';
		colors = [];

		// qty update
		$("#invoice-form").find('div.formItemsDiv').each(function(e){
			var item_id = $(this).attr('item-id');
			var item_idx = $(this).attr('item-idx');
			var color_id = $(this).find('.invoiceItem-color[item-id="'+item_id+'"]').val();
			colors[color_id] = item_id;
			var qty = $("#invoice-form").find('.invoiceItem-id[item-id="'+item_id+'"]').length;
			$("#invoiceSummaryTable tbody").find("#invoiceTr-"+item_id).attr('qty',qty).find('.itemTr-qty').html(qty);
		});

		// iterate through color array and update invoice table with new colors
		for (var k in colors){
			var item_id = colors[k];
			var color_count = $("#invoice-form").find('.invoiceItem-color[value="'+k+'"][item-id="'+item_id+'"]').length;
			var color_name = $("#invoice-form").find('.invoiceItem-color[value="'+k+'"]:first').attr('color-name');
			if(color_count > 0 && color_name !== ''){
				color_string += color_count+'-'+color_name+' / ';
			}
			$("#invoiceSummaryTable tbody").find("#invoiceTr-"+item_id+" .itemTr-color").html(color_string.substring(0,color_string.length-2));
		}

		// update price

		$('#invoiceSummaryTable tbody').find('tr').each(function(e){
			var item_id = $(this).attr('item-id');
			var price = 0;
			if($("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"]').length > 0){
				$("#invoice-form").find('.invoiceItem-price[item-id="'+item_id+'"]').each(function(f){
					price += parseFloat($(this).val().replace(/,/g, ''),false);
				});
				$("#invoiceSummaryTable tbody").find("#invoiceTr-"+item_id+" .itemTr-price").html($.number(price,2));
			} else {
				$(this).remove();
			}

		});

		//update memo


	},
	resetItemQty: function() {
		$("#itemQty").val(0);
		$("#itemQtySpan").html('--');
	},
	resetMemoList: function() {
		$("#memosUl").attr('item-id','').attr('item-idx','');
		$("#memosUl li").removeClass('active').removeClass('alert-info').addClass('alert-default');
		$("#memoInput").val('');
	},
	recalculateTotals: function() {
		// get item row totals
		$("#invoiceSummaryTable tbody").find('tr').each(function(){
			var item_id = $(this).attr('item-id');
			var subtotal = 0;
			$("#invoice-form").find('div.formItemsDiv[item-id="'+item_id+'"]').each(function(){
				subtotal += parseFloat($(this).find('.invoiceItem-price').val().replace(/,/g, ''),false);
			});

			$(this).find('.itemTr-price').html($.number(subtotal,2));

		});

		var subtotal = 0;
		var qty = 0;
		var tax_rate = parseFloat($("#tax_rate").val(),false);
		//grab subtotals
		$("#invoice-form").find('div.formItemsDiv').each(function(){
			qty++;
			subtotal += parseFloat($(this).find('.invoiceItem-price').val().replace(/,/g, ''),false);
		});
		var tax = $.number(parseFloat(subtotal,false) * tax_rate,2);
		var total = $.number(subtotal * (1+tax_rate),2);
		$("#invoiceItem-subtotal").html($.number(subtotal,2));
		$("#subtotal").val(subtotal);
		$("#invoiceItem-tax").html(tax);
		$("#tax").val(tax);
		$("#invoiceItem-total").html(total);
		$("#total").val(total);

	},
	resetAll: function() {
		// reset qty counters
		$("#itemQty").val(0);
		$("#itemQtySpan").html('--');

		// reset qty edit
		$("#qtyModalTotal").html('0');
		$("#qtyTable tbody").find('tr').remove();

		// reset colors
		$("#selectedColorsTable tbody").find('tr').remove();
		invoices.updateColorCount();
		// reset items
		$("#invoiceModal-item_name").html('');
		$("#invoiceModal-item_id").val(null);
		$("#invoiceModal-total_qty").html('');

		// reset memos
		$("#memoTable tbody").find('tr').remove();
		$("#memosUl li").removeClass('active').removeClass('alert-info').addClass('alert-default');

		// reset price
		$("#priceTable tbody").find('tr').remove();

	}

};

generate = {
	colorRow:function(item_id,item_name,idx,e, status) {
		var success = (e === 0) ? 'success' : '';
		var rows = '';
		if(status === true){
			// TODO after editing the color for 2 rows stays on the last color edited. 
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
		return (parseInt(id, false) === 0 || id === '') ? '' : '<input name="colorSelected-'+idx+'" type="color" value="'+hex+'" color_id="'+id+'" color="'+color+'" disabled="true"/>';
	},
	createMemo: function() {
		var memo_string = '';
		// first check to see which items have been selected
		$("#memosUl li").each(function(e){

			if($(this).hasClass('active')) {
				var string_item = $(this).find('.memosMemo').val()+' / ';
				memo_string += string_item;
			}
		});
		return memo_string.substring(0,memo_string.length-2);
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
	memoLi: function(memo_id){
		return '<li id="memoli-form-'+memo_id+'" class="memoli-form hide" memo-id="'+memo_id+'"></li>';
	},
	memoRow:function(item_id,item_name,idx) {
		var color = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').attr('color-name');
		var color_id = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var hex = $(".colorsId[value='"+color_id+"']").parents('button:first').find('.colorsColor').val();
		var colorItem = (color_id === '') ? '' : generate.colorItem(idx, color, hex, color_id);
		var memo = $("#invoice-form").find('.invoiceItem-memo[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var price = $("#invoice-form").find('.invoiceItem-price[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var rows = '<tr id="memoTr-'+idx+'" class="memoTr" item-id="'+item_id+'" item-idx="'+idx+'" check="false" style="cursor:pointer;" data-toggle="modal" data-target="#memo">'+
				'<td>'+idx+'</td>'+
				'<td>'+item_name+'</td>'+
				'<td>'+colorItem+'</td>'+
				'<td id="memoTd-'+idx+'" class="memoTd">'+memo+'</td>'+
				'<td>'+$.number(price,2)+'</td>'+
			'</tr>';

		return rows;
	},
	priceRow:function(item_id,item_name,idx) {
		var color = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').attr('color-name');
		var color_id = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var hex = $(".colorsId[value='"+color_id+"']").parents('button:first').find('.colorsColor').val();
		var colorItem = (color_id === '') ? '' : generate.colorItem(idx, color, hex, color_id);
		var memo = $("#invoice-form").find('.invoiceItem-memo[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var price = $("#invoice-form").find('.invoiceItem-price[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var rows = '<tr id="priceTr-'+idx+'" class="priceTr" item-id="'+item_id+'" item-idx="'+idx+'" check="false" style="cursor:pointer;" data-toggle="modal" data-target="#priceCalculator">'+
				'<td>'+idx+'</td>'+
				'<td>'+item_name+'</td>'+
				'<td>'+colorItem+'</td>'+
				'<td>'+memo+'</td>'+
				'<td class="form-group"><input id="priceInput-'+idx+'" class="priceInput" value="'+$.number(price,2)+'" type="text" disabled="disabled" style="background-color:#ffffff"></td>'+
			'</tr>';

		return rows;
	},
	qtyRow:function(item_id,item_name,idx) {
		var color = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').attr('color-name');
		var color_id = $("#invoice-form").find('.invoiceItem-color[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var hex = $(".colorsId[value='"+color_id+"']").parents('button:first').find('.colorsColor').val();
		var colorItem = (color_id === '') ? '' : generate.colorItem(idx, color, hex, color_id);
		var memo = $("#invoice-form").find('.invoiceItem-memo[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var price = $("#invoice-form").find('.invoiceItem-price[item-id="'+item_id+'"][item-idx="'+idx+'"]').val();
		var rows = '<tr id="qtyTr-'+idx+'" class="qtyTr" item-id="'+item_id+'" item-idx="'+idx+'" check="false" style="cursor:pointer;">'+
				'<td>'+idx+'</td>'+
				'<td>'+item_name+'</td>'+
				'<td>'+colorItem+'</td>'+
				'<td class="memo">'+memo+'</td>'+
				'<td>'+$.number(price,2)+'</td>'+
				'<td>'+
					'<input id="qtyCheck-'+idx+'" class="qtyCheck" type="checkbox" disabled="true"/>'+
				'</td>'+
			'</tr>';

		return rows;
	},
	formItem: function(item_id,qty,price){
		var idx = $(document).find('#invoice-form .invoiceItem-id').length +1;
		var inventory_id = $("#item-"+item_id).find('.item-inventory_id').val();
		console.log(inventory_id);
		var item = '';
		var total_qty = idx+parseInt(qty,false);
		for (var i = idx; i < total_qty; i++) {
			item += '<div class="hide formItemsDiv" item-idx="'+i+'" item-id="'+item_id+'">';
			item += '<input class="invoiceItem-id" type="hidden" value="'+item_id+'" name="item['+inventory_id+']['+i+']['+item_id+'][item_id]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item +=	'<input class="invoiceItem-price" type="hidden" value="'+price+'" name="item['+inventory_id+']['+i+']['+item_id+'][price]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item += '<input class="invoiceItem-color" type="hidden" value="" name="item['+inventory_id+']['+i+']['+item_id+'][color]" item-idx="'+i+'" item-id="'+item_id+'" color-name=""/>';
			item += '<input class="invoiceItem-memo" type="hidden" value="" name="item['+inventory_id+']['+i+']['+item_id+'][memo]" item-idx="'+i+'" item-id="'+item_id+'"/>';
			item +='<ul class="memoFormUl"></ul></div>';
		}
		

		return item;
	}
};

calendars = {
	events: function() {
		$('#openCalendar').on('click', function () {
			setTimeout(function(){
				$('#calendarDiv').fullCalendar('prev');
				$('#calendarDiv').fullCalendar('next');
				$('#calendarDiv').fullCalendar('render');

			}, 500);

		});

		$('#calendarDiv').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month'
			},
			eventLimit: true,
			defaultDate: moment(new Date()).format(),
			businessHours: $.parseJSON($("#store_hours").val()), // display business hours
			editable: true,
			dayRender: function(date, cell){

				var check=moment(date).format('L');
				var today = moment(new Date()).format('L');
				var turnaround = moment().add($("#turnaround").val(), 'days').format('L');
				var temp_turnaround = moment($("#temp_date").val()).format('L');
				
				// make sure all previous days are disabled
				if (check < today){
					$(cell).addClass('fc-state-disabled');
				}


				// check for turnaround selected in form
				if($("#temp_date").val() === '') {
					if(check === turnaround) {
						$(cell).addClass("fc-turnaround-selected").addClass('turnaround');
					}
				} else if(check == temp_turnaround){
					$(cell).addClass("fc-turnaround-selected").addClass('turnaround');
				}


			},
			selectable: true,
			select: function(start, end, allDay) {
				//start with turnaround date preselected
				$("#calendarDiv").find(".fc-turnaround-selected").removeClass('fc-turnaround-selected');

				var check=moment(start).format();
				var today = moment().subtract(1, 'days').format();
				
				if(check < today || moment(start).format('dddd') === 'Sunday') { // Previous Day. show message if you want otherwise do nothing.
					alert(moment(start).format('ll')+' is not selectable');
					$("#calendarDiv").find(".fc-highlight").removeClass('fc-highlight');
					$("#calendarDiv").find(".turnaround").addClass('fc-turnaround-selected');
					$("#temp_date").val('');
				} else { // Its a right date
					// Do something
					$("#calendarDiv").find(".turnaround").removeClass('turnaround');
					$("#calendarDiv").find(".fc-highlight").addClass('fc-turnaround-selected').addClass('turnaround').removeClass("fc-highlight");
					//send date to tempform
					$("#temp_date").val(moment(start).format('L'));

				}
			}
		});
	}
};

calculator = {
	events: function() {

		$(".priceNum").click(function() {
			var num = $(this).attr('num');
			var raw = $("#priceCalculatorInput").val().replace(/,/g, '');
			var first = parseFloat($("#priceCalculatorInput").attr('first'),false);
			var total = parseFloat($("#priceCalculatorInput").val(), false);
			var new_total = 0;
			if(num === 'C') {
				$('.priceNum').removeClass('active');
				$('#priceCalculatorInput').attr('first','0.00').val('0.00');
			} else if(num === 'B') {
				var backed = $("#priceCalculatorInput").val().replace(/[^0-9]/g, '').replace(/^0+/, '');
				backed = backed.substring(0,backed.length-1);
				backed = (parseFloat(backed) / 100 > 0) ? parseFloat(backed) / 100 : '0.00';
				$("#priceCalculatorInput").val($.number(backed,2));

			} else if(num === '='){
				$('.priceNum').removeClass('active');
				$("#priceCalculatorInput").attr('first','0.00').attr('status','1');
			} else if(num === '+'|| num === '-' || num==='*' || num==='/') {
				var operand = $(".priceNum.active").html();
				$("#priceCalculatorInput").attr('operand',$(this).html());

				if(($(".priceNum").hasClass('active'))) {
					if($("#priceCalculatorInput").attr('operand') === operand){
						switch(operand) {
							case '+':
							new_total = $.number((parseFloat(raw,false) + first),2);
							break;

							case '-':
							new_total = $.number(first - parseFloat(raw,false),2);
							break;

							case '*':
							new_total = $.number(parseFloat(raw,false) * first,2);
							break;

							case '/':
							new_total = Math.round(((parseFloat(raw,false) / first)*100)/100).toString(2);
							break;

						}
						$("#priceCalculatorInput").attr('first',new_total).val(new_total).attr('status','1');
						$('.priceNum').removeClass('active');
						$(this).addClass('active');
					} else {
						$('.priceNum').removeClass('active');
						$(this).addClass('active');
						$("#priceCalculatorInput").attr('first',total).attr('status','1');
					}

				} else {
					$('.priceNum').removeClass('active');
					$(this).addClass('active');
					$("#priceCalculatorInput").attr('first',total).attr('status','1').attr('operand',$(this).val()).val(total);
				}

			} else {
				var status = $("#priceCalculatorInput").attr('status');

				if(status === '1') {
					$("#priceCalculatorInput").val('0.00').attr('status','2');
					total = calculator.calculate(num);
				} else {
	
					total = calculator.calculate((Math.round(parseFloat(raw,true)*100))+''+num);
				}

				$("#priceCalculatorInput").val($.number(total,2));
			}

			
		});

		$("#updateCalcPrice").click(function(){
			var price = $("#priceCalculatorInput").val();
			$('#priceTable').find('tr.success .priceInput').val(price);
		});

		$("#updatePrice").click(function(){
			$('#priceTable').find('tr .priceInput').each(function(){
				var price = $(this).val();
				var item_id = $(this).parents('tr:first').attr('item-id');
				var item_idx = $(this).parents('tr:first').attr('item-idx');
				$("#invoice-form").find('div.formItemsDiv[item-idx="'+item_idx+'"][item-id="'+item_id+'"] .invoiceItem-price').val(price);
				invoices.recalculateTotals();
			});
		});


	},

	calculate: function(data) {

		var num = (parseInt(data, false) * 1) / 100;
		return num;
	}

};