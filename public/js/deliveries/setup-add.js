$(document).ready(function(){
	setup.pageLoad();
	setup.events();
});

setup = {
	pageLoad: function() {
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
		});
	},
	events: function() {
		$('#blackout').Zebra_DatePicker({
			format:'D m/d/Y',
			onSelect: function(a, b) {

				var blackout_tr = make.blackout_tr(a, b);
				if ($("#blackout_table tbody").find('.blackout_tr[date="'+b+'"]').length > 0) {
					alert('date already selected');
				} else {
					$("#blackout_table tbody").append(blackout_tr);
					make.reindex_blackout_tr();
					$("#blackout").val('');
				}
			}
		});
		$("#blackout_table tbody").on('click','.blackout_remove',function(){
			$(this).parents('tr:first').remove();
			make.reindex_blackout_tr();
		});
		$("#zipcode_select").change(function(){
			var option_selected = $(this).find('option:selected').val();
			var zipcode_tr = make.zipcode_tr(option_selected);
			if ($("#zipcode_table tbody").find('#zipcode_tr-'+option_selected).length > 0) {
				alert('Zipcode already selected');
			} else {
				$("#zipcode_table tbody").append(zipcode_tr);
				make.reindex_zipcode_tr();
			}
		});

		$("#zipcode_table tbody").on('click','.zipcode_remove',function(){
			$(this).parents('tr:first').remove();
			make.reindex_zipcode_tr();
		});
	}
};

make = {
	zipcode_tr: function(zipcode){
		current_length = $("#zipcode_table tbody").find('.zipcode_tr').length;
		next_row = current_length + 1;
		tr = '<tr id="zipcode_tr-'+zipcode+'" class="zipcode_tr">';
		tr += '<td>'+zipcode+'</td>';
		tr += '<td><button type="button" class="btn btn-sm btn-danger zipcode_remove">remove</button><input type="hidden" value="'+zipcode+'" name="zipcode['+next_row+']"/></td>';
		tr += '</tr>';

		return tr;
	},

	reindex_zipcode_tr: function() {
		$("#zipcode_table tbody").find('.zipcode_tr').each(function(e) {
			$(this).find('input').attr('name','zipcode['+e+']');
		});
	},

	blackout_tr: function(date, date_formatted) {
		current_length = $("#blackout_table tbody").find('.blackout_tr').length;
		next_row = current_length + 1;
		tr = '<tr date="'+date_formatted+'" class="blackout_tr">';
		tr += '<td>'+date+'</td>';
		tr += '<td><button type="button" class="btn btn-sm btn-danger blackout_remove">remove</button><input type="hidden" value="'+date_formatted+'" name="blackout['+next_row+']"/></td>';
		tr += '</tr>';

		return tr;
	},
	reindex_blackout_tr: function() {
		$("#blackout_table tbody").find('.blackout_tr').each(function(e) {
			$(this).find('input').attr('name','blackout['+e+']');
		});
	},
};