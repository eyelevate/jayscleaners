@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Add An Admin <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li class="active">Add</li>
	</ol>
@stop
@section('content')
	<form class="form-horizontal" role="form" method="POST" action="{{ route('admins_add_post') }}">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Admin Registration Form</h3>
			</div>
			<div class="panel-body">
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Username</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="username" value="{{ old('username') }}">

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
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">

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
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">

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
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Phone</label>

                    <div class="col-md-6">
                        <input type="phone" class="form-control" name="phone" value="{{ old('phone') }}">

                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>				
			</div>
			<div class="panel-footer clearfix">
				<input type="submit" value="Add" class="btn btn-primary pull-right"/>
			</div>
		</div>
	</form>
@stop