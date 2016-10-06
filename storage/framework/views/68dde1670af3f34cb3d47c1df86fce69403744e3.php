<div id="invoiceRemove-<?php echo e($id); ?>" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Delete Invoice [<strong><?php echo e(str_pad($invoice_id, 6, '0', STR_PAD_LEFT)); ?></strong>]</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you wish to delete this invoice?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a class="btn btn-danger" href="<?php echo e(route('invoices_delete',$id)); ?>">
                    <div class="icon"><i class="ion-ios-compose-outline"></i> Delete</div>
                </a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->