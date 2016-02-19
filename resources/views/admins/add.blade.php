@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Add An Admin <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('admins_overview') }}"> Overview</a></li>
		<li class="active">Add</li>
	</ol>
@stop
@section('content')
	{!! Form::open(['action' => 'AdminsController@postAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
		{!! csrf_field() !!}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Admin Registration Form</h3>
			</div>
			<div class="panel-body">
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Username</label>

                    <div class="col-md-6">
                    	{!! Form::text('username', old('username'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">First Name</label>

                    <div class="col-md-6">
                        {!! Form::text('first_name', old('first_name'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Last Name</label>

                    <div class="col-md-6">
                        {!! Form::text('last_name', old('last_name'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                    	{!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
                <div class="form-group{{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Phone</label>

                    <div class="col-md-6">
                        {!! Form::text('contact_phone', old('contact_phone'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('contact_phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Location</label>

                    <div class="col-md-6">
                        {!! Form::select('company_id',$companies , '', ['class'=>'form-control']) !!}
                        @if ($errors->has('company_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                    	{!! Form::password('password', '', ['class'=>'form-control']) !!}
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
                        {!! Form::password('password_confirmation', '', ['class'=>'form-control']) !!}
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

			</div>
			<div class="panel-footer clearfix">
				<input type="submit" value="Add" class="btn btn-primary pull-right"/>
			</div>
		</div>
	{!! Form::close() !!}
@stop