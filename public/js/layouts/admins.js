$(document).ready(function(){
	admins.pageLoad();
});

admins = {
	pageLoad: function(){
		// set focus on the first search bar
		setTimeout(function(){
			$("#search").focus().click();
		},500);

		// disable all a tags with href = #
		$('a[href="#"]').click(function(e){
			e.preventDefault();
		});
	}
};