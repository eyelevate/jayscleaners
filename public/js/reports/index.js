$(document).ready(function() {
	reports.pageLoad();
	reports.events();
});

reports = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},

	events: function() {
		$('#start').Zebra_DatePicker({
			format:'D m/d/Y'
		});
		$('#end').Zebra_DatePicker({
			format:'D m/d/Y'
		});

		$(".select_dates").click(function(){
			start = $(this).attr('start');
			end = $(this).attr('end');

			$("#start").val(start);
			$("#end").val(end);

			$(this).parents('form:first').submit();
		});
	}
};