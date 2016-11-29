<div id="bill" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Send Account Bill</h4>
			</div>
			<div class="modal-body">
				<div class="form-group<?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
                    <label class="control-label">Select Month</label>

                	<?php echo Form::select('billing_month', $month ,date('n'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                    <?php if($errors->has('username')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('username')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a class="btn btn-info" href="<?php echo e(route('accounts_preview')); ?>">Preview & Print Billing</a>
				<button type="submit" class="btn btn-primary">Email Billing</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->