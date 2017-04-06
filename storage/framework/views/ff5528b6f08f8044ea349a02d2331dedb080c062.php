<?php echo Form::open(['action' => 'DiscountsController@postDelete','role'=>"form"]); ?>

<?php echo Form::hidden('id',$id); ?>

<div id="delete-<?php echo e($id); ?>" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Store Credit</h4>
			</div>
			<div class="modal-body clearfix">
				<p>Are you sure you want to delete this discount? This action cannot be undone.</p>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

				<button type="submit" class="btn btn-danger pull-right">Delete Discount</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>