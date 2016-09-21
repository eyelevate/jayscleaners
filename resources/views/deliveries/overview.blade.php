@extends($layout)
@section('stylesheets')
@stop
@section('scripts')
@stop
@section('header')

@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        {!! Form::open(['action' => 'DeliveriesController@postOverview','role'=>"form"]) !!}
            {!! csrf_field() !!} 
		<div class="panel-body">

            <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                <label class="control-label padding-top-none">Search Delivery</label>
				<input id="search" type="text" class="form-control" name="search" value="{{ old('search') }}" placeholder="phone / id / last name">
				
	            @if ($errors->has('search'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('search') }}</strong>
	                </span>
	            @endif
            </div>

            <button type="submit" class="btn btn-default ">Search</button>
		</div>
		{!! Form::close() !!}
		<div class="panel-footer clearfix">
			<a href="#" class="btn btn-lg btn-info btn-flat pull-left col-lg-2 col-md-2 col-sm-6 col-xs-6">New Delivery</a>
			<a href="{{ route('schedules_checklist') }}" class="btn btn-lg btn-default btn-flat pull-right col-lg-2 col-md-2 col-sm-6 col-xs-6">Checklist</a>
		</div>
	</div>

	<div class="box box-info">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Active Delivery List &nbsp;<span class="label label-default pull-right">{{ count($schedules) }}</span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="table-responsive">
				<table class="table no-margin">
					<thead>
						<tr>
							<th>Schedule #</th>
							<th>Customer</th>
							<th>Pickup</th>
							<th>Dropoff</th>
							<th>Status</th>
							<th>Created On</th>
						</tr>
					</thead>
					<tbody>
					@if (count($schedules) > 0) 
						@foreach($schedules as $schedule)
						<tr data-toggle="modal" data-target="#detail-{{ $schedule['id'] }}">
							<td>{{ $schedule['id'] }}</td>
							<td>[{{ $schedule['customer_id'] }}] {{ $schedule['last_name'] }}, {{ $schedule['first_name'] }}</td>
							<td>{{ $schedule['pickup_date'] }}</td>
							<td>{{ $schedule['dropoff_date'] }}</td>
							<td><label class="label {{ $schedule['status_html'] }}">{{ $schedule['status_message'] }}</label></td>
							<td>{{ $schedule['created_at'] }}</td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div><!-- /.table-responsive -->
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">

		</div><!-- /.box-footer -->
	</div>
@stop

@section('modals')
	@if (count($schedules) > 0)
		@foreach($schedules as $schedule)
		{!! View::make('partials.deliveries.details')
			->with('schedule',$schedule)
			->render()
		!!}
		@endforeach
	@endif
@stop