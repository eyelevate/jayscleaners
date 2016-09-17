$(document).ready(function(){
	checklist.pageLoad();
});

checklist = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
};

requests = {

};