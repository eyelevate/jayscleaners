<div id="payment" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Payment Selection</h4>
			</div>
			<div class="modal-body clearfix">
                <div class="form-group<?php echo e($errors->has('payment_id') ? ' has-error' : ''); ?>">
                    <label class="col-md-12 control-label padding-top-none">Card on file <span style="color:#ff0000">*</span></label>

                    <div class="col-md-12">
                        
                        <?php echo e(Form::select('payment_id',$cards,old('payment_id'),['class'=>'form-control'])); ?>

                        <?php if($errors->has('payment_id')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('payment_id')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('cards_index')); ?>" class="btn btn-link" style="color:#ffffff">Manage my cards on file</a>
                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="memoAdd" type="button" class="btn btn-success btn-lg" data-dismiss="modal">Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->