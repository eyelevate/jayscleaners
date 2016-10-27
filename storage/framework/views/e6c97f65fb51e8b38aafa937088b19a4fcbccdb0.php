<?php echo Form::open(['action' => 'InvoicesController@postManage','role'=>"form"]); ?>

<?php echo Form::hidden('id','',['id'=>'invoice_item_id']); ?>

<div id="update" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Item Update</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Store <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::select('company_id',$companies,'1',['id'=>'company_id','class'=>'form-control'])); ?>

                   
                </div>		            
	            <div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Location <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::select('status',$locations,'1',['id'=>'location','class'=>'form-control'])); ?>

                   
                </div>		
                <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Item <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::text('name','',['id'=>'name','class'=>'form-control','readonly'=>'true'])); ?>

                   
                </div>	
                <div class="form-group<?php echo e($errors->has('color') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Color <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::text('color','',['id'=>'color','class'=>'form-control','readonly'=>'true'])); ?>

                   
                </div>	
                <div class="form-group<?php echo e($errors->has('memo') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Memo <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::textarea('memo','',['id'=>'memo','class'=>'form-control','readonly'=>'true'])); ?>

                   
                </div>		
	            <div class="form-group<?php echo e($errors->has('pretax') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Subtotal <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::text('pretax','0.00',['id'=>'pretax','class'=>'form-control'])); ?>

                   
                </div>	
	            <div class="form-group<?php echo e($errors->has('tax') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Tax <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::text('tax','0.00',['id'=>'tax','class'=>'form-control','readonly'=>'true'])); ?>

                  
                </div>	
	            <div class="form-group<?php echo e($errors->has('total') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Total <span style="color:#ff0000">*</span></label>

                    <?php echo e(Form::text('total','0.00',['id'=>'total','class'=>'form-control'])); ?>

                  
                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="finish-check" type="submit" class="btn btn-success btn-lg finish_button" >Update</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>