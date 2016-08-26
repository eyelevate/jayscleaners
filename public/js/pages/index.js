$(document).ready(function(){
	pages.pageLoad();
	pages.events();

});

pages = {
	pageLoad: function() {

	},
	events: function() {
		$("#logout_button").click(function(e){
			e.preventDefault();
			$(this).parent().find('form:first').submit();
		});
	}
};