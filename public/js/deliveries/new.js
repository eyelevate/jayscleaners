$(document).ready(function(){
	delivery.pageLoad();
	delivery.events();
});

delivery = {
	pageLoad: function() {

	},
	events: function() {
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
			}
		});
	}
};