$(document).ready(function(){
	delivery.pageLoad();
	delivery.events();
});

delivery = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$(".cards_tr").click(function() {
			$(".cards_tr").removeClass('success');
			$(".card_ids").prop('checked', false);
			// toggle the selected classes and update the session data
			if ($(this).hasClass('success')) {
				// remove class and remove item from session
				$(this).removeClass('success');
				$(this).find('.card_ids').prop('checked', false);
			} else {
				// add class and add to session
				$(this).addClass('success');
				$(this).find('.card_ids').prop('checked', true);
			}

		});

		$(".card_ids").click(function() {
			// toggle the selected classes and update the session data
			if ($(this).parents('.cards_tr:first').hasClass('success')) {
				// remove class and remove item from session
				$(this).parents('.cards_tr:first').removeClass('success');
				$(this).prop('checked', false);
			} else {
				// add class and add to session
				$(this).parents('.cards_tr:first').addClass('success');
				$(this).prop('checked', true);
			}
		});


		$(".address_tr").click(function() {
			$(".address_tr").removeClass('success');
			$(".address_id").prop('checked', false);
			// toggle the selected classes and update the session data
			if ($(this).hasClass('success')) {
				// remove class and remove item from session
				$(this).removeClass('success');
				$(this).find('.address_id').prop('checked', false);
			} else {
				// add class and add to session
				$(this).addClass('success');
				$(this).find('.address_id').prop('checked', true);

				request.makeSchedule($(this).find('.address_id').val());
			}

		});

		$(".address_id").click(function() {
			// toggle the selected classes and update the session data
			if ($(this).parents('.address_tr:first').hasClass('success')) {
				// remove class and remove item from session
				$(this).parents('.address_tr:first').removeClass('success');
				$(this).prop('checked', false);
			} else {
				// add class and add to session
				$(this).parents('.address_tr:first').addClass('success');
				$(this).prop('checked', true);
				$("#pickup_div").html('');
				$("#dropoff_div").html('');
				request.makeSchedule($(this).val());
			}
		});

		$("#pickingup").change(function(){
			var option_selected = $(this).find('option:selected').val();
			if (option_selected == 1) {
				$("#pickup_div, #pickup_time_div").removeClass('hide');
			} else {
				$("#pickup_div, #pickup_time_div").addClass('hide');
			}
		});
		$("#droppingoff").change(function(){
			var option_selected = $(this).find('option:selected').val();
			if (option_selected == 1) {
				$("#dropoff_div, #dropoff_time_div").removeClass('hide');
			} else {
				$("#dropoff_div, #dropoff_time_div").addClass('hide');
			}
		});
	}
};

request = {
	makeSchedule: function(address_id){
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/make-schedule',
			{
				"_token": token,
				"address_id": address_id
			},function(result){
				if (result.status) {
					pickup = result.pickup_calendar;
					dropoff = result.dropoff_calendar;
					calendar_dates = result.dates;
					$("#pickup_div").html(pickup);
					$("#dropoff_div").html(dropoff);
					$('#pickup_div').find("#pickupdate").Zebra_DatePicker({
						format:'D m/d/Y',
						disabled_dates: calendar_dates,
						direction: [true, false],
						show_select_today: false,

						onSelect: function(a, b) {
							request.makePickupTime(address_id, b);
						}
					});
					$('#dropoff_div').find("#dropoffdate").Zebra_DatePicker({
						format:'D m/d/Y',
						disabled_dates: calendar_dates,
						direction: [true, false],
						show_select_today: false,

						onSelect: function(a, b) {
							request.makeDropoffTime(address_id, b);
						}
					});
				}
				
			}
		);
	},
	redoDropoffSchedule: function(delivery_id, d){
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/redo-dropoff-schedule',
			{
				"_token": token,
				"delivery_id": delivery_id,
				'd':d
			},function(result){
				if (result.status) {
					var address_id = $(".address_tr.success").find(".address_id").val();
					
					$('#dropoff_div').find("#dropoffdate").val(result.next_available_date);
					request.makeDropoffTime(address_id, result.next_available_date);
				}
				
			}
		);
	},
	makePickupTime: function(address_id, d){
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/make-pickup-time',
			{
				"_token": token,
				"address_id": address_id,
				'd':d
			},function(result){
				if (result.status) {
					$("#pickup_time_div").html(result.time_select);
					
					$("#pickup_time_div").find("#pickuptime").change(function(){
						var delivery_id = $(this).find('option:selected').val();
						request.redoDropoffSchedule($(this).find('option:selected').val(), d);
					});
					
					

					
				}
				
			}
		);
	},
	makeDropoffTime: function(address_id, d){
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/delivery/make-dropoff-time',
			{
				"_token": token,
				"address_id": address_id,
				'd':d
			},function(result){
				if (result.status) {
					$("#dropoff_time_div").html(result.time_select);
				}
				
			}
		);
	}

};