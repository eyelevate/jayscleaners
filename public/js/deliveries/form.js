$(document).ready(function(){
	form.pageLoad();
	form.events();
});

form = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});

	},
	events: function() {
		$("#pickup_address").on('change', function() {
			var option_selected = this.value;
			request.address_check(option_selected);
		});

		$("#pickup_body input, #pickup_body radio, #pickup_body checkbox, #pickup_body select").click(function() {
			$(".panel-body").removeClass('active').addClass('notactive');
			$("#pickup_body").removeClass('notactive').addClass('active');
		});
		$("#dropoff_body input, #dropoff_body radio, #dropoff_body checkbox, #dropoff_body select").click(function() {
			$(".panel-body").removeClass('active').addClass('notactive');
			$("#dropoff_body").removeClass('notactive').addClass('active');
		});
	}
};

request = {
	address_check: function(id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/check_address',
			{
				"_token": token,
				"id": id
			},function(result){
				var status = result.status;
				if (status) {
					
					$(".pickup_date_div").find('input').removeAttr('disabled').css({'background-color':'#ffffff'});
					
				} else {
					$(".pickup_date_div").find('input').attr('disabled',true).css({'background-color':'#e5e5e5'});
					$("#pickuptime").attr('disabled',true);
				}
				
			}
		);
	},
	set_time: function(b, id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/set_time',
			{
				"_token": token,
				"date": b,
				"address_id":id
			},function(result){
				if (result.status) {

					var options = result.options;
					$("#pickuptime").find('option').remove();
					html_options = '';
					console.log(options);
					$.each( options, function( index, value ){
						html_options += value;
					});
					$('#pickuptime').html(html_options).removeAttr('disabled');

				}
				
			}
		);
	}
};