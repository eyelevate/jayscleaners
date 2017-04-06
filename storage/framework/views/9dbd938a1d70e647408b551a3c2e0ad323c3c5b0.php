<?php echo Form::open(['action' => 'ColorsController@postDelete', 'class'=>'form-horizontal','role'=>"form"]); ?>

<?php echo csrf_field(); ?>

<?php echo e(Form::hidden('id',null,['class'=>'colorEditId'])); ?>

<div id="delete" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Are you sure?</h4>
			</div>
			<div class="modal-body">
				<p>By pressing "Confirm Delete" below you will be deleting a Color. Are you sure you want to delete?.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-danger" value="Confirm Delete"/>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>