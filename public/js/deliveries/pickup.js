$(document).ready(function(){
	form.pageLoad();
	form.events();
});

form = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
		$('[data-toggle="tooltip"]').tooltip();

	},
	events: function() {
		$("#pickup_address").on('change', function() {

			var option_selected = this.value;
			request.address_check(option_selected);
		});

		$("#dropoffmethod").change(function(){
			var option = $(this).find('option:selected').val();
			datepicker = $('#dropoffdate').data('Zebra_DatePicker');
			datepicker.destroy();
			if (option == 1) {
				$("#dropoff_section").removeClass('hide');
				$(document).on('focus',"#dropoffdate", function(){
					$(this).Zebra_DatePicker({
						container:$("#dropoff_container"),
						format:'D m/d/Y'
					});
				});

			} else {
				$("#dropoff_section").addClass('hide');
			}

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
					
					location.reload();
					
				} else {
					$(".pickup_date_div").find('input').attr('disabled',true).css({'background-color':'#e5e5e5'});
					$("#pickuptime").attr('disabled',true);
				}
				
			}
		);
	},
	set_time_pickup: function(b, id) {
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

					if ($("#dropofftime option:selected").val() !== '') {
						$("#pickup_submit").removeAttr('disabled');
						
					} else {
						$("#pickup_submit").attr('disabled',true);
						
					}
				}
				
			}
		);
	},

};