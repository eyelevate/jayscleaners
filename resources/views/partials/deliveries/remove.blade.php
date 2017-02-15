<div id="delete-{{ $id }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Delete Delivery Schedule</strong></h4>
			</div>
			<div class="modal-body clearfix">
				<p>Are you sure you want to delete this Delivery Schedule? This cannot be undone.</p>
				<div class="form-group">
					<label class="col-sm-4 control-label">Delivery Schedule #</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $id }}</p>
					</div>
				</div>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<a class="btn btn-sm btn-danger pull-right" href="{{ route('delivery_setup_delete',$id) }}">remove</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->