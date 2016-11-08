@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Delivery Schedule</h4></div>
		<div class="panel-body">

		</div>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Route</th>
						<th>Day</th>
						<th>Limit</th>
						<th>Start</th>
						<th>End</th>
						<th>Zipcode</th>
						<th>Blackout</th>
						<th>Created</th>
						<th>A</th>
					</tr>
				</thead>
				<tbody>
				@if (count($deliveries))
					@foreach($deliveries as $delivery)
					<tr>
						<td>{{ $delivery->id }}</td>
						<td>{{ $delivery->route_name }}</td>
						<td>{{ $delivery->day }}</td>
						<td>{{ $delivery->limit }}</td>
						<td>{{ $delivery->start_time }}</td>
						<td>{{ $delivery->end_time }}</td>
						<td>
							<ul>

							@if ($delivery->zipcode)
								@foreach($delivery->zipcode as $zipcode)
								<li>{{ $zipcode }}</li>
								@endforeach
							
							@endif
							</ul>
						</td>
						<td>
							<ul>
							@if (count($delivery->blackout) > 0)
								@foreach($delivery->blackout as $blackout)
								<li>{{ date('D n/d/Y',strtotime($blackout)) }}</li>
								@endforeach
							@else
								@if (isset($delivery->blackout))

								<li>{{ date('D n/d/Y',strtotime($delivery->blackout)) }}</li>
								@endif
							
							@endif
							</ul>
						</td>
						<td>{{ date('D n/d/Y',strtotime($delivery->created_at)) }}</td>
						<td>
							<a class="btn btn-sm btn-info" href="{{ route('delivery_setup_edit',$delivery->id) }}">edit</a>&nbsp;
							<a class="btn btn-sm btn-danger" href="{{ route('delivery_setup_delete',$delivery->id) }}">remove</a>
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>

		<div class="panel-footer clearfix">
			<a href="{{ route('admins_index') }}" class="btn btn-danger">Back</a>
			<a href="{{ route('delivery_setup_add') }}" class="btn btn-primary pull-right">New Rule</a>
		</div>

	</div>
@stop