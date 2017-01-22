$(document).ready(function(){
	prepare.pageLoad();
	prepare.events();
});

prepare = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$( ".sortable" ).sortable({
			update: function(event, ui) {
				data = [];
				$(this).parents('table:first').find('tbody tr').each(function(e){
					var stop = e + 1;
					var schedule_id = $(this).find('.schedule_id').val();
					$(this).find('.stopInput').val(stop);
					data[e] = schedule_id;
				});

				requests.update_sort(data);
			}
        });

	}
};

requests = {
	update_sort: function(data) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/schedules/sort',
			{
				"_token": token,
				"sort": data
			},function(result){

				
			}
		);
	}
};