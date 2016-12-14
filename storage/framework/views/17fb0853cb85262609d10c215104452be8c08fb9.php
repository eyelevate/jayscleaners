<div id="discount" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Select Discount</h4>
			</div>
			<div class="modal-body clearfix">
				<div class="form-group <?php echo e($errors->has('discount_id') ? ' has-error' : ''); ?>">
					<label class="control-label">Discounts</label>
					<?php echo e(Form::select('discount_id',$discounts,'',['id'=>'discount_id','class'=>"form-control"])); ?>

	                <?php if($errors->has('discount_id')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('discount_id')); ?></strong>
	                    </span>
	                <?php endif; ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="discountRemove" type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Remove Discount</button>
				<button id="discountSelect" type="button" class="btn btn-success btn-lg" data-dismiss="modal" >Select</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->