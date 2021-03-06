$(document).ready(function() {
	reset.pageLoad();
	reset.events();
});

reset = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$("#send_selected").click(function(){
			request.send_selected();
		});
	}
};

request = {
	send_all: function() {

	},
	send_selected: function() {
		$(".sending").removeClass('hide');
		var count = $('.user_id:checked').length;
		var sent = 0;
		$("#step-1").removeClass('hide');
		$(".totalSelected").html(count);
		$("#step-2").removeClass('hide');
		$(".user_id:checked").each(function(e){
			var user_id = $(this).val();
			var next_count = e + 1;
			$.ajaxQueue({
				method: "POST",
				url: "/admins/reset-passwords",
				data: { 'user_id' : user_id },
				dataType: "json"
			}).done(function( data ) {

				$("#step-3").removeClass('hide');
				$("#currentSentCount").html(next_count);
				if (next_count == count) {
					$("#step-4").removeClass('hide');
					location.reload();
				}
				

			});

		});
	}
};