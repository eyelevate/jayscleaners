@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop

@section('navigation')

    <header id="header" class="reveal">
    {!! View::make('partials.layouts.navigation_logged_in')
        ->render()
    !!}
    </header>
@stop
@section('content')
    {!! Form::open(['action' => 'UsersController@postUpdate', 'class'=>'form-horizontal','role'=>"form"]) !!}
        {!! csrf_field() !!}
        <!-- Tabs within a box -->
        <div class="panel panel-default">
            <div class="panel-heading"><label>Customer Update Form</label></div>
            <div class="panel-body">
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('phone', old('phone') ? old('phone') : $customers->phone, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Last Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('last_name', old('last_name') ? old('last_name') : $customers->last_name, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">First Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('first_name', old('first_name') ? old('first_name') : $customers->first_name, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        {!! Form::email('email', old('email') ? old('email') : $customers->email, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  

                <div class="form-group{{ $errors->has('starch') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Shirt Starch Preferrence</label>

                    <div class="col-md-6">
                        {!! Form::select('starch', $starch , old('starch') ? old('starch') : $customers->starch, ['class'=>'form-control']) !!}
                        @if ($errors->has('starch'))
                            <span class="help-block">
                                <strong>{{ $errors->first('starch') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="form-group{{ $errors->has('hanger') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Shirt Finish</label>

                    <div class="col-md-6">
                        {!! Form::select('hanger', $hanger, old('hanger') ? old('hanger') : $customers->shirt, ['class'=>'form-control']) !!}
                        @if ($errors->has('hanger'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hanger') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="thumbnail">
                	<br/>
	                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
	                    <label class="col-md-4 control-label">New Password </label>

	                    <div class="col-md-6">
	                        {!! Form::password('password', old('password'), ['class'=>'form-control', 'placeholder'=>'']) !!}
	                        @if ($errors->has('password'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('password') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
	                    <label class="col-md-4 control-label">Confirm Password</label>

	                    <div class="col-md-6">
	                        {!! Form::text('password_confirmation', old('password_confirmation'), ['class'=>'form-control', 'placeholder'=>'']) !!}
	                        @if ($errors->has('password_confirmation'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('password_confirmation') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div> 
	                <br/>
            	</div>



  

            </div>

	        <div class="panel-footer clearfix">
	            <input class="btn btn-lg btn-primary pull-right" type="submit" value="Save"/>
	        </div>
    	</div><!-- /.nav-tabs-custom -->
    {!! Form::close() !!}
@stop