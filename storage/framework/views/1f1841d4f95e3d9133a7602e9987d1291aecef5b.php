<?php echo Form::open(['action' => 'AccountsController@postPay', 'class'=>'pay_form','role'=>"form"]); ?>

<?php echo e(Form::hidden('status',1)); ?>

<?php echo e(Form::hidden('customer_id', $customer_id)); ?>

<div id="account_pay" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Account Pay Confirmation</h4>
			</div>
			<div class="modal-body">
                <div class="form-group<?php echo e($errors->has('type') ? ' has-error' : ''); ?>">
                    <label class="control-label">Payment Type</label>

                    <?php echo e(Form::select('type',[''=>'Select Payment Type','1'=>'Credit Card','2'=>'Cash','3'=>'Check'],'',['id'=>'type','class'=>'form-control'])); ?>

                    <?php if($errors->has('type')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('type')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>				
                <div class="form-group<?php echo e($errors->has('last_four') ? ' has-error' : ''); ?>">
                    <label class="control-label">Last 4 / Check #</label>

                    <?php echo e(Form::text('Last 4 / Check #','',['id'=>'last_four','class'=>'form-control'])); ?>

                    <?php if($errors->has('last_four')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('last_four')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>
                <div class="form-group<?php echo e($errors->has('total') ? ' has-error' : ''); ?>">
                    <label class="control-label">Total Due</label>

                    <?php echo e(Form::text('total','',['id'=>'total','class'=>'form-control'])); ?>

                    <?php if($errors->has('total')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('total')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>
                <div class="form-group<?php echo e($errors->has('tendered') ? ' has-error' : ''); ?>">
                    <label class="control-label">Total Tendered</label>

                    <?php echo e(Form::text('tendered','',['id'=>'tendered','class'=>'form-control'])); ?>

                    <?php if($errors->has('tendered')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('tendered')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>
                <div class="form-group<?php echo e($errors->has('change') ? ' has-error' : ''); ?>">
                    <label class="control-label">Change Due</label>

                    <?php echo e(Form::text('change','',['id'=>'change','class'=>'form-control'])); ?>

                    <?php if($errors->has('change')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('change')); ?></strong>
                        </span>
                    <?php endif; ?>

                </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>