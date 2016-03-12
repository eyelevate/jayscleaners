$(document).ready(function(){
	colors.pageLoad();
	colors.events();
});

colors = {
	pageLoad: function() {

	},
	events: function() {
		$("#colorsUl").sortable({
			update: function( event, ui ) {
				// renumber all of the form data in the list
				$("#colorsUl li").each(function(e){
					$(this).find('.colorsOrder').val(e+1);
				});
				// submit the form to reorder the inventory group
				$("#color-form").submit();
			}
		});

		$("#colorsUl li a").click(function(e){
			//highlight the current selected color
			$("#colorsUl li a").removeClass('active');
			$(this).addClass('active');

			//Send data to edit form
			var color_id = $(this).find('.colorsId').val();
			var color = $(this).find('.colorsColor').val();
			var name = $(this).find('.colorsName').val();

			$(".colorEditId").val(color_id);
			$("#colorEditInput").val(color);
			$("#colorEditName").val(name);
		});
	}

};