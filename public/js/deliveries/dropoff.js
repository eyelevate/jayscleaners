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
		$("#dropoff_address").on('change', function() {
			var option_selected = this.value;
			request.address_check(option_selected);
		});

	},
	update_dropoff: function() {
		
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
					
					$(".dropoff_date_div").find('input').removeAttr('disabled').css({'background-color':'#ffffff'});
					
				} else {
					$(".dropoff_date_div").find('input').attr('disabled',true).css({'background-color':'#e5e5e5'});
					$("#dropofftime").attr('disabled',true);
				}
				
			}
		);
	},

	set_time_dropoff: function(b, id) {

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
					$("#dropofftime").find('option').remove();
					html_options = '';
					$.each( options, function( index, value ){
						html_options += value;
					});
					$('#dropofftime').html(html_options).removeAttr('disabled');

					if ($("#dropofftime option:selected").val() !== '') {
						$("#dropoff_submit").removeAttr('disabled');
						
					} else {
						$("#dropoff_submit").attr('disabled',true);
						
					}
				}
				
			}
		);
	},

};