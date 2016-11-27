{!! Form::open(['action' => 'AccountsController@postRevert', 'class'=>'pay_form','role'=>"form"]) !!}
{!! Form::hidden('id',$transaction->id) !!}
<div id="revert-{{ $transaction->id }}" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Revert Status For #{{ $transaction->id }}?</h4>
			</div>
			<div class="modal-body">
			    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    <label class="control-label">Status</label>

                    {{ Form::select('status',[''=>'Select Status','2'=>'Payment Due','3'=>'Active'],'',['id'=>'type','class'=>'form-control']) }}
                    @if ($errors->has('status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif

                </div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger">Revert</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}