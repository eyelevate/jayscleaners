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
			@if (count($reports['dropoff'] > 0))
				@foreach($reports['dropoff'] as $do)
				<tr>
					<td>{{ $do->id }}</td>
					<td>{{ $do->users->id }}</td>
					<td>{{ ucFirst($do->users->last_name) }}, {{ ucFirst($do->users->first_name) }}</td>
					<td>{{ $do->quantity }}</td>
					<td>{{ $do->subtotal }}</td>
					<td><a href="#">view customer</a></td>
					
				</tr>
				@endforeach
			@endif
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
					<th>Name</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
{{-- 			@if (count($reports['dropoff'] > 0))
				@foreach($reports['dropoff'] as $do)
				<tr>
					<td>{{ $do->id }}</td>
					<td>{{ $do->users->id }}</td>
					<td>{{ ucFirst($do->users->last_name) }}, {{ ucFirst($do->users->first_name) }}</td>
					<td>{{ $do->quantity }}</td>
					<td>{{ $do->subtotal }}</td>
					<td><a href="#">view customer</a></td>
					
				</tr>
				@endforeach
			@endif --}}
			</tbody>

		</table>
	</div>
	<div class="panel-footer"></div>
</div>

@stop