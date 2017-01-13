{!! Form::open(['action' => 'SchedulesController@postSetupRoute','role'=>"form",'class'=>'pull-right']) !!}
{!! Form::hidden('id',$schedule['id']) !!}	
<div id="edit-{{ $schedule['id'] }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Route Information</strong></h4>
			</div>
			<div class="modal-body clearfix">
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">First Name </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('first_name',old('first_name') ? old('first_name') : $schedule['first_name'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">Last Name </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('last_name',old('last_name') ? old('last_name') : $schedule['last_name'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">Street </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('street',old('street') ? old('street') : $schedule['street'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('street') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">City </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('city',old('city') ? old('city') : $schedule['city'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">Zipcode </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('zipcode',old('zipcode') ? old('zipcode') : $schedule['zipcode'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('zipcode') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }} clearfix">
                    <label class="col-md-12 control-label padding-top-none">Zipcode </label>

                    <div class="col-md-12 clearfix">
                        
                        {{ Form::text('zipcode',old('zipcode') ? old('zipcode') : $schedule['zipcode'],['class'=>'form-control']) }}
                        @if ($errors->has('content'))
                            <span class="help-block">
                                <strong>{{ $errors->first('zipcode') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>

				<input type="submit" class="btn btn-primary" value="Send" />
				
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	
</div><!-- /.modal -->
{!! Form::close() !!}