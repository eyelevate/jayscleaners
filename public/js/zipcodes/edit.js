$(document).ready(function(){
	zipcodes.pageLoad();
});

zipcodes = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	requests: function() {
		$(".delete_btn").click(function(){
			zipcode_id = $(this).attr('zipcode');
			delivery_id = $(this).attr('delivery');
			status_delete = requests.delete_zipcode(zipcode_id,delivery_id);
			if (status_delete) {
				$(this).parents('tr:first').addClass('danger');
			} else {
				alert('Could not delete zipcode from delivery route. Please try again.');
			}
		});

	}
};

requests = {
	delete_zipcode: function(zipcode_id, delivery_id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/zipcodes/delete_zipcode',
			{
				"_token": token,
				"delivery_id": delivery_id,
				'zipcode_id':zipcode_id
			},function(result){
				if (result.status) {
					return true;
				} else {
					
					return false;
				}
				
			}
		);
	},

};