<div id="check" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Check Payment</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group{{ $errors->has('amount_due') ? ' has-error' : '' }}">
                    <label class="control-label padding-top-none">Amount Due <span style="color:#ff0000">*</span></label>

                    {{ Form::text('amount_due','0.00',['class'=>'amount_due form-control','readonly'=>'true','style'=>'font-size:20px;']) }}

                   
                </div>	
	            <div class="form-group{{ $errors->has('last_four') ? ' has-error' : '' }}">
                    <label class="control-label padding-top-none">Check Number <small>(optional)</small></label>  
                    {{ Form::text('last_four','',['id'=>'last_four_check','class'=>'form-control','style'=>'font-size:20px;']) }}
                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="finish-check" type="button" class="btn btn-success btn-lg finish_button" >Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->