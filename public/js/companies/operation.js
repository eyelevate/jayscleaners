$(document).ready(function(){
	operation.pageLoad();
	operation.events();
});

operation = {
	pageLoad: function(){

	},
	events: function(){
		$(".operation_status").change(function(){
			if($(this).find('option:selected').val() == '1') { // closed
				$(this).parents('tr:first').find('.operationFormTd select').attr('disabled','true');

			} else { // open 
				$(this).parents('tr:first').find('.operationFormTd select').removeAttr('disabled');
			}
		});
	}

};