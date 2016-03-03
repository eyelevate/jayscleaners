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
			cancel: ".fixed",
			update: function( event, ui ) {
				// renumber all of the form data in the list
				$("#inventory-group-ul li").each(function(e){
					$(this).find('.inventory-order').val(e+1);
				});
				// submit the form to reorder the inventory group
				$("#group-form").submit();
			}
		});
	}


};