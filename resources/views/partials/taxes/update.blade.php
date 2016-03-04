
<div id="update" class="modal fade" tabindex="-1" role="dialog">
{!! Form::open(['action' => 'TaxesController@postUpdate', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{{ Form::hidden('id',NULL,['id'=>'itemEdit-id']) }}
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Sales Tax Form</h4>
			</div>
			<div class="modal-body">
                <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('company_id',$companies , '', ['class'=>'form-control']) !!}
                        @if ($errors->has('company_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
	
	
                <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Rate <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('rate', old('rate'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('rate'))
                            <span class="help-block">
                                <strong>{{ $errors->first('rate') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Update Tax"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
{!! Form::close() !!}
</div><!-- /.modal -->