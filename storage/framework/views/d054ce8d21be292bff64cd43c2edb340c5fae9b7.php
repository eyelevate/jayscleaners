<div id="credit" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Credit Card Payment</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group<?php echo e($errors->has('amount_due') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Amount Due <span style="color:#ff0000">*</span></label>   
                    <?php echo e(Form::text('amount_due','0.00',['class'=>'amount_due form-control','readonly'=>'true','style'=>'font-size:20px;'])); ?>


                    
                </div>	
	            <div class="form-group<?php echo e($errors->has('last_four') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Last Four <small>(optional)</small></label>  
                    <?php echo e(Form::text('last_four','',['id'=>'last_four_credit','class'=>'form-control','style'=>'font-size:20px;'])); ?>

                </div>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="finish-credit" type="button" class="btn btn-success btn-lg finish_button" >Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->