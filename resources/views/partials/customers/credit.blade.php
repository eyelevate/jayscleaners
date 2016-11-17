{!! Form::open(['action' => 'CreditsController@postAdd','role'=>"form"]) !!}
{!! Form::hidden('customer_id',$customer_id) !!}
<div id="credit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Store Credit</h4>
			</div>
			<div class="modal-body clearfix">
                <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                    <label class="col-md-12 control-label">Credit Amount</label>

                    <div class="col-md-12">
                        {!! Form::text('amount', old('amount'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                    </div>
                </div>
                <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                    <label class="col-md-12 control-label">Reason</label>

                    <div class="col-md-12">
                        {!! Form::select('reason', $reasons ,old('amount'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                    </div>
                </div>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

				<button type="submit" class="btn btn-success pull-right">Add Credit</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}