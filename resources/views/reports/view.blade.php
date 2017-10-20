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
	<h3>Report date(s): <strong>{{ date('n/d/Y g:ia',strtotime($start_date)) }}</strong> - <strong>{{ date('n/d/Y g:ia',strtotime($end_date)) }}</strong></h3>
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
					<th>Name</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Due</th>
					<th>Rack</th>
					<th>created</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			@if (isset($reports['dropoff']))
				@foreach($reports['dropoff'] as $do)
				<tr>
					<td>{{ $do->id }}</td>
					<td>{{ $do->users->id }}</td>
					<td>{{ ucFirst($do->users->last_name) }}, {{ ucFirst($do->users->first_name) }}</td>
					<td>{{ $do->quantity }}</td>
					<td>${{ $do->pretax }}</td>
					<td>{{  date('n/d/y',strtotime($do->due_date)) }}</td>
					<td>{{ $do->rack }}</td>
					<td>{{ date('n/d/y g:ia',strtotime($do->created_at)) }}</td>
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
					<th>Due</th>
					<th>Rack</th>
					<th>Finished</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			@if (isset($reports['pickup']))
				@foreach($reports['pickup'] as $po)
				<tr>
					<td>{{ $po->id }}</td>
					<td>{{ $po->users->id }}</td>
					<td>{{ ucFirst($po->users->last_name) }}, {{ ucFirst($po->users->first_name) }}</td>
					<td>{{ $po->quantity }}</td>
					<td>${{ $po->pretax }}</td>
					<td>{{ date('n/d/y',strtotime($po->due_date)) }}</td>
					<td>{{ $po->rack }}</td>
					<td>{{ date('n/d/y g:ia',strtotime($po->transactions->created_at)) }}</td>
					<td><a href="#">view customer</a></td>
					
				</tr>
				@endforeach
			@endif
			</tbody>

		</table>
	</div>
	<div class="panel-footer"></div>
</div>

@stop