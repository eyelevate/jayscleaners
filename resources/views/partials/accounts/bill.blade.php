<div id="bill" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Send Account Bill</h4>
			</div>
			<div class="modal-body well well-sm">
				<div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                    <label class="control-label">Customer ID</label>

                	{!! Form::text('customer_id','', ['id'=>'customer_id','class'=>'form-control', 'placeholder'=>'']) !!}
                    @if ($errors->has('customer_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('customer_id') }}</strong>
                        </span>
                    @endif

                </div>
				<div class="form-group{{ $errors->has('transaction_id') ? ' has-error' : '' }}">
                    <label class="control-label">Transaction ID</label>

                	{!! Form::text('transaction_id','', ['id'=>'transaction_id','class'=>'form-control', 'placeholder'=>'']) !!}
                    @if ($errors->has('transaction_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('transaction_id') }}</strong>
                        </span>
                    @endif

                </div>
				<div class="form-group{{ $errors->has('billing_month') ? ' has-error' : '' }}">
                    <label class="control-label">Select Month</label>

                	{!! Form::select('billing_month', $month ,date('n'), ['id'=>'billing_month','class'=>'form-control', 'placeholder'=>'']) !!}
                    @if ($errors->has('billing_month'))
                        <span class="help-block">
                            <strong>{{ $errors->first('billing_month') }}</strong>
                        </span>
                    @endif

                </div>
			</div>
			<div class="table-responsive">
				<table class="table table-condensed table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Last</th>
							<th>First</th>
							<th>Period</th>
							<th>Due</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a class="btn btn-info" href="">Preview & Print Billing</a>
				<button type="submit" class="btn btn-primary">Email Billing</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->