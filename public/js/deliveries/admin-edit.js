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
			request.address_check_pickup(option_selected);
		});
		$("#dropoff_address").on('change', function() {
			var option_selected = this.value;
			request.address_check_dropoff(option_selected);
		});

	},
	update_dropoff: function() {
		
	}
};

request = {
	address_check_pickup: function(id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/check_address',
			{
				"_token": token,
				"id": id
			},function(result){
				var status = result.status;
				if (status) {
					
					location.reload();
					
				} else {
					$(".pickup_date_div, .dropoff_date_div").find('input').attr('disabled',true).css({'background-color':'#e5e5e5'});
					$("#pickuptime").attr('disabled',true);
					$("#dropoffdate, #dropofftime").attr('disabled',true);
				}
				
			}
		);
	},
	address_check_dropoff: function(id) {
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
	set_time_pickup: function(b, id, d_id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'http://www.jayscleaners.com/delivery/set_time_update',
			{
				"_token": token,
				"date": b,
				"address_id":id,
				"pickup_delivery_id":d_id
			},function(result){
				if (result.status) {

					var options = result.options_pickup;
					$("#pickuptime").find('option').remove();
					html_options = '';
					$.each( options, function( index, value ){
						html_options += value;
					});
					$('#pickuptime').html(html_options).removeAttr('disabled');

					var options_dropoff = result.options_dropoff;
					$("#dropofftime").find('option').remove();
					html_options = '';
					$.each( options_dropoff, function( index, value ){
						html_options += value;
					});
					$('#dropofftime').html(html_options).removeAttr('disabled');

					$("#dropoffdate").val(result.date);
					var datepicker = $('body').find('#dropoffdate').data('Zebra_DatePicker');
					datepicker.update({
						direction: [result.date, false],
					});
				}
			}
		);
	},
	set_time_dropoff: function(b, id) {
		console.log('here');
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