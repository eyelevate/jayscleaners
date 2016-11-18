<?php echo Form::open(['action' => 'MemosController@postAdd', 'class'=>'form-horizontal','role'=>"form"]); ?>

<?php echo csrf_field(); ?>

<div id="add" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Memo</h4>
			</div>
			<div class="modal-body">

                <div class="form-group<?php echo e($errors->has('memo') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Memo <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('memo', old('memo'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('memo')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('memo')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div> 			        
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Add Memo"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>