<div id="card_delete-{{ $card_id }}" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999 !important; margin-top:10px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Delete Confirmation?</h4>
			</div>
			<div class="modal-body">
				<p style="text-align:center;">Are you sure you want to delete this Card?</p>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<a href="{{ route('cards_delete',$card_id) }}" class="btn btn-danger" role="button">Delete</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->