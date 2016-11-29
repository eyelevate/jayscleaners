<?php echo Form::open(['action' => 'AccountsController@postRevert', 'class'=>'pay_form','role'=>"form"]); ?>

<?php echo Form::hidden('id',$transaction->id); ?>

<div id="revert-<?php echo e($transaction->id); ?>" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Revert Status For #<?php echo e($transaction->id); ?>?</h4>
			</div>
			<div class="modal-body">
			    <div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
                    <label class="control-label">Status</label>

                    <?php echo e(Form::select('status',[''=>'Select Status','2'=>'Payment Due','3'=>'Active'],'',['id'=>'type','class'=>'form-control'])); ?>

                    <?php if($errors->has('status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('status')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger">Revert</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>