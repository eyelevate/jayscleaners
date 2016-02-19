$(document).ready(function(){
	admins.pageLoad();
});

admins = {
	pageLoad: function(){
		// set focus on the first search bar
		setTimeout(function(){
			$("#search").focus().click();
		},500);
	}
};