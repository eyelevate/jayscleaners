$(document).ready(function() {
	member.pageLoad();
	member.events();
});

member = {
	pageLoad: function() {

	},
	events: function () {
		$("#pay_type").on('change',function(){
			value_selected = $("#pay_type option:selected").val();
			console.log(value_selected);
			$("#card_selection-form, #card_selection-cof").removeClass('hide');
			switch(value_selected) {
				case '1':
					$("#card_selection-cof").addClass('hide');
				break;

				case '2':
					$("#card_selection-form").addClass('hide');
				break;

				default:
					
					$("#card_selection-cof, #card_selection-form").addClass('hide');
				break;
			}
		});
	}
};

