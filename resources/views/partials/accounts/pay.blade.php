{!! Form::open(['action' => 'AccountsController@postPay', 'class'=>'pay_form','role'=>"form"]) !!}
{{ Form::hidden('status',1) }}
{{ Form::hidden('customer_id', $customer_id) }}
<div id="account_pay" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Account Pay Confirmation</h4>
			</div>
			<div class="modal-body">
                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    <label class="control-label">Payment Type</label>

                    {{ Form::select('type',[''=>'Select Payment Type','1'=>'Credit Card','2'=>'Cash','3'=>'Check'],'',['id'=>'type','class'=>'form-control']) }}
                    @if ($errors->has('type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                    @endif

                </div>				
                <div class="form-group{{ $errors->has('last_four') ? ' has-error' : '' }}">
                    <label class="control-label">Last 4 / Check #</label>

                    {{ Form::text('Last 4 / Check #','',['id'=>'last_four','class'=>'form-control']) }}
                    @if ($errors->has('last_four'))
                        <span class="help-block">
                            <strong>{{ $errors->first('last_four') }}</strong>
                        </span>
                    @endif

                </div>
                <div class="form-group{{ $errors->has('total') ? ' has-error' : '' }}">
                    <label class="control-label">Total Due</label>

                    {{ Form::text('total','',['id'=>'total','class'=>'form-control']) }}
                    @if ($errors->has('total'))
                        <span class="help-block">
                            <strong>{{ $errors->first('total') }}</strong>
                        </span>
                    @endif

                </div>
                <div class="form-group{{ $errors->has('tendered') ? ' has-error' : '' }}">
                    <label class="control-label">Total Tendered</label>

                    {{ Form::text('tendered','',['id'=>'tendered','class'=>'form-control']) }}
                    @if ($errors->has('tendered'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tendered') }}</strong>
                        </span>
                    @endif

                </div>
                <div class="form-group{{ $errors->has('change') ? ' has-error' : '' }}">
                    <label class="control-label">Change Due</label>

                    {{ Form::text('change','',['id'=>'change','class'=>'form-control']) }}
                    @if ($errors->has('change'))
                        <span class="help-block">
                            <strong>{{ $errors->first('change') }}</strong>
                        </span>
                    @endif

                </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}