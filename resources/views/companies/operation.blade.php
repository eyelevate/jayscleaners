@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Store Hours & Turnaround Time <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
		<li><a href="{{ route('companies_index') }}"> Companies</a></li>
		<li class="active">Operation</li>
	</ol>
@stop
@section('content')
<!-- Add Company Form -->
{!! Form::open(['action' => 'CompaniesController@postOperation', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
<div class="box box-primary">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Add A Company</h3>
		<div class="box-tools pull-right">

		</div>
	</div><!-- /.box-header -->
	<div class="box-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<TH>Day</TH>
					<th>Status</th>
					<TH>Open</TH>
					<TH>Close</TH>
					<TH>Turnaround</TH>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Sunday</td>
					<td>

					</td>
					<td>

					</td>
					<td>

					</td>
					<td>

					</td>
				</tr>
				<tr>
					<td>Monday</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Tuesday</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Wednesday</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Thursday</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Friday</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Saturday</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Company Name <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>	
        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('phone', old('phone'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Email <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('email', old('email'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>		
        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Street <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('street', old('street'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('street'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>	
        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">City <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('city', old('city'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">State <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('state', old('state'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('state'))
                    <span class="help-block">
                        <strong>{{ $errors->first('state') }}</strong>
                    </span>
                @endif
            </div>
        </div>		
        <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Zipcode <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                {!! Form::text('zip', old('zip'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('zip'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zip') }}</strong>
                    </span>
                @endif
            </div>
        </div>
	</div><!-- /.box-body -->
	<div class="box-footer clearfix no-border ">
		<input type="submit" value="Create Company" class="btn btn-primary btn-large pull-right"/>
	</div>
</div><!-- /.box -->
{{ Form::close() }}
@stop