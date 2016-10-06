<div id="cof" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Card on File</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group{{ $errors->has('amount_due') ? ' has-error' : '' }}">
                    <label class="col-md-12 control-label padding-top-none">Amount Due <span style="color:#ff0000">*</span></label>

                    <div class="col-md-12">   
                        {{ Form::text('amount_due','0.00',['class'=>'amount_due form-control','readonly'=>'true']) }}
                    </div>
                    <a href="{{ route('cards_index') }}" class="btn btn-link" style="color:#ffffff">Manage my cards on file</a>
                </div>	
                <div class="form-group{{ $errors->has('payment_id') ? ' has-error' : '' }}">
                    <label class="col-md-12 control-label padding-top-none">Card on file <span style="color:#ff0000">*</span></label>

                    <div class="col-md-12">
                        
                        {{ Form::select('payment_id',$cards,old('payment_id'),['id'=>'payment_id','class'=>'form-control']) }}
                        @if ($errors->has('payment_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('payment_id') }}</strong>
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('cards_index') }}" class="btn btn-link" style="color:#ffffff">Manage my cards on file</a>
                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="finish-cof" type="button" class="btn btn-success btn-lg finish_button" >Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->