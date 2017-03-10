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


		$( ".sortable-tbody" ).sortable({
			update: function(event, ui) {
				data = [];
				$(this).parents('table:first').find('tbody tr').each(function(e){
					var idx = e + 1;
					var item_id = $(this).attr('id').replace('item_list-','');
					$(this).attr('ordered',idx);
					$(this).find('.item_order').html(idx);
					data[e] = item_id;
				});

				requests.update_sort(data);
			}
        });
	}
};

requests = {
	update_sort: function(data) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/inventories/sort',
			{
				"_token": token,
				"sort": data
			},function(result){

				
			}
		);
	}
};