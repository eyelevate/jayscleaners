$(document).ready(function(){
	rack.pageLoad();
	rack.events();
});

rack = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});

		// init bunch of sounds
		ion.sound({
		sounds: [
			{name: "beer_can_opening"},
			{name: "bell_ring"},
			{name: "branch_break"},
			{name: "button_click"},
			{name: "metal_plate"}
		],

		// main config
			path: "/packages/ion.sound-3.0.7/sounds/",
			preload: true,
			multiplay: true,
			volume: 1
		});
	},
	events: function() {
		var round = 0;
		$(document).keypress(function(e) {
			
			if(e.which == 13) {
				
				var check_invoice = $("#invoice_id").val();

				var check_rack = $("#rack_number").val();

				if (check_invoice !== '' && check_rack !== '') {
					round = 0;
					requests.updateRack(check_invoice, check_rack);
					ion.sound.play("bell_ring");
				} else {
					round++;
					if (check_invoice === ''){
						ion.sound.play("metal_plate");
						$("#invoice_id").focus();
					} else {
						if (round < 2) {

							ion.sound.play("button_click");
						} else {
							ion.sound.play("metal_plate");
						}
						$("#rack_number").focus();
					}

				}

			}
		});

		$("#rack_tbody").on('click','.remove',function(){
			var invoice_id = $(this).attr('invoice');
			requests.removeRack(invoice_id);
		});
	}

};

requests = {
	updateRack: function(invoice_id, rack_number) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/invoices/rack-update',
			{
				"_token": token,
				"invoice_id": invoice_id,
				"rack_number": rack_number
			},function(result){
				if (result.status) {
					$("#invoice_id").val('').focus();
					$("#rack_number").val('');
					var racks = result.racks;
					$("#rack_tbody").html('');
					$.each(racks, function( key, value ) {
						$("#rack_tbody").append(
							'<tr><td>'+key+'</td><td>'+value+'</td><td><a invoice_id="'+key+'" class="btn btn-sm btn-danger remove">Remove</a><input type="hidden" name="rack['+key+']" value="'+value+'"/></td></tr>'
						);
					});
				}
			}
		);
	},
	removeRack: function(invoice_id) {
		var token = $('meta[name=csrf-token]').attr('content');
		$.post(
			'/invoices/rack-remove',
			{
				"_token": token,
				"invoice_id": invoice_id
			},function(result){
				if (result.status) {
					$("#invoice_id").val('').focus();
					$("#rack_number").val('');
					var racks = result.racks;
					$("#rack_tbody").html('');
					$.each(racks, function( key, value ) {
						$("#rack_tbody").append(
							'<tr><td>'+key+'</td><td>'+value+'</td><td><a invoice="'+key+'" class="btn btn-sm btn-danger remove">Remove</a><input type="hidden" name="rack['+key+']" value="'+value+'"/></td></tr>'
						);
					});
				}
			}
		);
	}

};