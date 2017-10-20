@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
<div class="well">
	<h3>Report date(s): <strong>{{ $start_date }}</strong> - <strong>{{ $end_date }}</strong></h3>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Dropoff Invoices</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Customer</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
{{-- 			@if (count($reports['dropoff_summary'] > 0))
				@foreach($reports['dropoff_summary'] as $ds)
				<tr>
					<td>{{ $ds['name'] }}</td>
					<td>{{ $ds['totals']['quantity'] }}</td>
					<td>{{ $ds['totals']['subtotal'] }}</td>
					<td>{{ $ds['totals']['tax'] }}</td>
					<td>{{ $ds['totals']['total'] }}</td>
				</tr>
				@endforeach
			@endif --}}
			</tbody>

		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Pickup Invoices</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Customer</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
{{-- 			@if (count($reports['pickup_summary'] > 0))
				@foreach($reports['pickup_summary'] as $ps)
				<tr>
					<td>{{ $ps['name'] }}</td>
					<td>{{ $ps['totals']['quantity'] }}</td>
					<td>{{ $ps['totals']['subtotal'] }}</td>
					<td>{{ $ps['totals']['tax'] }}</td>
					<td>{{ $ps['totals']['total'] }}</td>
				</tr>
				@endforeach
			@endif --}}
			</tbody>

		</table>
	</div>
	<div class="panel-footer"></div>
</div>

@stop