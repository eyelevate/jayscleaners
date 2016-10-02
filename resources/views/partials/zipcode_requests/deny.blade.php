{!! Form::open(['action' => 'ZipcodeRequestsController@postDeny', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{!! Form::hidden('zipcode',$zipcode) !!}
<div id="deny-{{ $zipcode }}" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Deny Zipcode</h4>
			</div>
			<div class="modal-body">
                <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Message To Customer(s)</label>

                    <div class="col-md-6">
                        {!! Form::textarea('message', old('message'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('message'))
                            <span class="help-block">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>				        
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Accept"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}