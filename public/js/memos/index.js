$(document).ready(function(){
	memos.pageLoad();
	memos.events();
});

memos = {
	pageLoad: function() {

	},
	events: function(){
		$("#memosUl").sortable({
			update: function( event, ui ) {
				// renumber all of the form data in the list
				$("#memosUl li").each(function(e){
					$(this).find('.memosOrdered').val(e+1);
				});
				// submit the form to reorder the inventory group
				$("#memo-form").submit();
			}
		});

		$(".memoLi").click(function(){

			//make active
			if($(this).hasClass('active')) {
				$(this).removeClass('active').removeClass('alert-info').addClass('alert-default');
			} else {
				$(this).addClass('active').removeClass('alert-default').addClass('alert-info');
			}

			//send to edit
			var memo_id = $(this).find('.memosId').val();
			var memo = $(this).find('.memosMemo').val();
			$(".memoEditId").val(memo_id);
			$("#memoEditMemo").val(memo);
		});
	}

};