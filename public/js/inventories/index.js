$(document).ready(function(){
	inventories.pageLoad();
	inventories.events();
});

inventories = {
	pageLoad: function(){
		$('#image_select').ddslick({
			onSelected: function(selectedData){
				//callback function: do something with selectedData;
				$("#image_selected").val(selectedData.selectedData.value);
			}
		});
		$('#image_select-edit').ddslick({
			onSelected: function(selectedData){
				//callback function: do something with selectedData;
				$("#itemEdit-image").val(selectedData.selectedData.value);
			}
		});
	},
	events: function(){
		$("#inventory-edit").click(function(){
			// get active class hidden fields and place them into form
			var inventory_id = $("#inventory-group-ul .active .inventory-id").val();
			var inventory_name = $("#inventory-group-ul .active .inventory-name").val();
			var inventory_desc = $("#inventory-group-ul .active .inventory-description").val();
			$(".groupEdit-id").val(inventory_id);
			$("#groupEdit-name").val(inventory_name);
			$("#groupEdit-description").val(inventory_desc);
		});
		$("#inventory-items-edit").click(function(){
			// get active class hidden fields and place them into form
		});
		$("#inventory-group-ul").sortable({
			update: function( event, ui ) {
				// renumber all of the form data in the list
				$("#inventory-group-ul li").each(function(e){
					$(this).find('.inventory-order').val(e+1);
				});
				// submit the form to reorder the inventory group
				$("#group-form").submit();
			}
		});

		$(".items").click(function(){
			// remove all other selected classes
			$(".items").removeClass('active');
			// select this class
			$(this).addClass('active');
			// send hidden data to edit form 
			var item_id = $(this).find('.item-id').val();
			var item_name = $(this).find('.item-name').val();
			var item_desc = $(this).find('.item-description').val();
			var item_price = $(this).find('.item-price').val();
			var item_tags = $(this).find('.item-tags').val();
			var item_quantity = $(this).find('.item-quantity').val();
			var item_inventory_id = $(this).find('.item-inventory_id').val();
			var item_image = $(this).find('.item-image').val();

			$(".itemEdit-id").val(item_id);
			$("#itemEdit-name").val(item_name);
			$("#itemEdit-description").val(item_desc);
			$("#itemEdit-inventory_id option[value='"+item_inventory_id+"']").attr('selected','selected');
			$("#itemEdit-tags option[value='"+item_tags+"']").attr('selected','selected');
			$("#itemEdit-quantity option[value='"+item_quantity+"']").attr('selected','selected');
			$("#itemEdit-price").val(item_price);
			$("#itemEdit-image").val(item_image);
			$("#image_select-edit .dd-option-value").each(function(e){
				if($(this).val() == item_image){
					$('#image_select-edit').ddslick('select', {index: e });

				}
			});
			
		});

		$(".tab-content li").sortable({
			update: function( event, ui ) {
				// reorder items
				$('.chart').each(function(e){
					$(this).find('.items').each(function(i){
						$(this).find('.item-order').val(i+1);
					});
				});
				// save ordered items
				$("#item-form").submit();
			}
		});
	}


};