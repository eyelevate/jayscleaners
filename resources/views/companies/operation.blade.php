@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
	<script type="text/javascript" src="/js/companies/operation.js"></script>
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
{{	Form::hidden('id',$company->id) }}
<div class="box box-primary">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Store Hours / Invoice Turnaround Setup Form</h3>
		<div class="box-tools pull-right">

		</div>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">

			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<TH>Day</TH>
						<th>Status</th>
						<TH>Open</TH>
						<TH>Close</TH>
						<TH>Turnaround</TH>
						<TH>Due</TH>
					</tr>
				</thead>
				<tbody>
					@if(isset($company->store_hours))
						@foreach(json_decode($company->store_hours,true) as $key => $value)
						<tr>
							<td>{{ $dow[$key] }}</td>
							<td>
						        <div class="form-group{{ $errors->has('operation[$key][status]') ? ' has-error' : '' }}">

						            <div class="col-md-12">
						                {!! Form::select('operation[$key][status]', $status, $value->status, ['class'=>'form-control operation_status']) !!}
						                @if ($errors->has('operation[$key][status]'))
						                    <span class="help-block">
						                        <strong>{{ $errors->first('operation[$key][status]') }}</strong>
						                    </span>
						                @endif
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$key][open]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$key][open_hour]', $hours, $value->open_hour, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][open_minutes]', $minutes, $value->open_minutes, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][open_ampm]', $ampm, $value->open_ampm, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$key][close]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$key][closed_hour]', $hours, $value->closed_hour, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][closed_minutes]', $minutes, $value->closed_minutes, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][closed_ampm]', $ampm, $valu->closed_ampm, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$key][turnaround]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$key][turnaround]', $turnaround, $value->turnaround, ['class'=>'form-control','disabled'=>'true']) !!}
						            </div>

						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$key][due]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$key][due_hour]', $hours, $value->due_hour, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][due_minutes]', $minutes, $value->due_minutes, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$key][due_ampm]', $ampm, $value->due_ampm, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
						</tr>
						@endforeach
					@else
						@for($i = 0; $i <= 6; $i++)
						<tr>
							<td>{{ $dow[$i] }}</td>
							<td>
						        <div class="form-group{{ $errors->has('operation[$i][status]') ? ' has-error' : '' }}">

						            <div class="col-md-12">
						                {!! Form::select('operation[$i][status]', $status, null, ['class'=>'form-control operation_status']) !!}
						                @if ($errors->has('operation[$i][status]'))
						                    <span class="help-block">
						                        <strong>{{ $errors->first('operation[$i][status]') }}</strong>
						                    </span>
						                @endif
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$i][open]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$i][open_hour]', $hours, 12, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][open_minutes]', $minutes, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][open_ampm]', $ampm, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$i][close]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$i][closed_hour]', $hours, 12, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][closed_minutes]', $minutes, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][closed_ampm]', $ampm, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$i][turnaround]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$i][turnaround]', $turnaround, null, ['class'=>'form-control','disabled'=>'true']) !!}
						            </div>

						        </div>
							</td>
							<td class="operationFormTd">
						        <div class="form-group{{ $errors->has('operation[$i][due]') ? ' has-error' : '' }} clearfix">

						            <div class="col-md-12">
						                {!! Form::select('operation[$i][due_hour]', $hours, 12, ['class'=>'form-control','disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][due_minutes]', $minutes, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						                {!! Form::select('operation[$i][due_ampm]', $ampm, null, ['class'=>'form-control', 'disabled'=>'true']) !!}
						            </div>
						        </div>
							</td>
						</tr>
						@endfor
					@endif
					
				</tbody>
			</table>
        </div>
	</div><!-- /.box-body -->
	<div class="box-footer clearfix no-border ">
		<input type="submit" value="Create Company" class="btn btn-primary btn-large pull-right"/>
	</div>
</div><!-- /.box -->
{{ Form::close() }}
@stop