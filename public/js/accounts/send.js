$(document).ready(function(){
	send.pageLoad();
	send.events();
});

send = {
	pageLoad: function(){
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$(".accountTr").click(function(){
			if ($(this).hasClass('success')) {
				$(this).removeClass('success');
				$(this).find('.transaction_ids').prop('checked', false);
			} else {
				$(this).addClass('success');
				$(this).find('.transaction_ids').prop('checked', true);
			}
			request.update_send_list();
		});

		$('#checkAll').click(function(){
			if ($(this).is(':checked')) {
				$(".accountTr").each(function(e) {
					$(this).addClass('success');
					$(this).find('.transaction_ids').prop('checked', true);
				});
			} else {
				$(".accountTr").each(function(e) {
					$(this).removeClass('success');
					$(this).find('.transaction_ids').prop('checked', false);
				});
			}
			request.update_send_list();
		});
	}
};

request = {
	update_send_list: function() {
		var token = $('meta[name=csrf-token]').attr('content');
		transaction_ids = [];
		$(".accountTr.success").each(function(){
			transaction_id = $(this).attr('id').replace('accountTr-','');
			transaction_ids.push(transaction_id);
		});
		$.post(
			'/accounts/send-list',
			{
				"_token": token,
				"transaction_ids": transaction_ids
			},function(result){

			}
		);
	},
};