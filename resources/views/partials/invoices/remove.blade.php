<div id="remove-{{ $invoice->id }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Delete Invoice #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</h4>
			</div>
			<div class="modal-body clearfix">
				<p>Are you sure you wish to delete this invoice? #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<a href="{{ route('invoices_delete',$invoice->id) }}" class="btn btn-danger btn-lg finish_button pull-right" >Delete</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->