<div id="address_delete-<?php echo e($address_id); ?>" class="modal fade clearfix" tabindex="-1" role="dialog" style="z-index:9999 !important; margin-top:10px;">
	<div class="modal-dialog clearfix">
		<div class="modal-content clearfix">
			<div class="modal-header">
				<h4 class="modal-title">Delete Confirmation?</h4>
			</div>
			<div class="modal-body">
				<p style="text-align:center;">Are you sure you want to delete this address?</p>
			</div>
		
			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<a href="<?php echo e(route('address_delete',$address_id)); ?>" class="btn btn-danger" role="button">Delete</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->