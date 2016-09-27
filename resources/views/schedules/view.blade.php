@extends($layout)
@section('stylesheets')
@stop
@section('scripts')
@stop
@section('header')

@stop
@section('content')
	<br/>
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
			<a class="btn btn-info" href="{{ route('customers_view',$customer_id) }}">Customer View</a>
			<a class="btn btn-info" href="{{ route('delivery_overview') }}">Delivery Overview</a>
			<a class="btn btn-primary" href="{{ route('delivery_new',$customer_id) }}">New Delivery</a>
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