$(document).ready(function() {
	add.events();
});

add = {
	events: function() {
		$('.datePicker').Zebra_DatePicker({
			format:'Y-m-d',
			direction: [true, false],
			show_select_today: false
		});
	}
};