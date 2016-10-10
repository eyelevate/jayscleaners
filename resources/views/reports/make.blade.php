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
		<h3 class="panel-title">Dropoff Totals</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Inventory</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			@if (count($reports['dropoff_summary'] > 0))
				@foreach($reports['dropoff_summary'] as $ds)
				<tr>
					<td>{{ $ds['name'] }}</td>
					<td>{{ $ds['totals']['quantity'] }}</td>
					<td>{{ $ds['totals']['subtotal'] }}</td>
					<td>{{ $ds['totals']['tax'] }}</td>
					<td>{{ $ds['totals']['total'] }}</td>
				</tr>
				@endforeach
			@endif
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style="text-align:right">Quantity</th>
					<td>{{ $reports['dropoff_summary_totals']['quantity'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Subtotal</th>
					<td>{{ $reports['dropoff_summary_totals']['subtotal'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Tax</th>
					<td>{{ $reports['dropoff_summary_totals']['tax'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Total</th>
					<td>{{ $reports['dropoff_summary_totals']['total'] }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Pickup Totals</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Inventory</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			@if (count($reports['pickup_summary'] > 0))
				@foreach($reports['pickup_summary'] as $ps)
				<tr>
					<td>{{ $ps['name'] }}</td>
					<td>{{ $ps['totals']['quantity'] }}</td>
					<td>{{ $ps['totals']['subtotal'] }}</td>
					<td>{{ $ps['totals']['tax'] }}</td>
					<td>{{ $ps['totals']['total'] }}</td>
				</tr>
				@endforeach
			@endif
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style="text-align:right">Quantity</th>
					<td>{{ $reports['pickup_summary_totals']['quantity'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Subtotal</th>
					<td>{{ $reports['pickup_summary_totals']['subtotal'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Tax</th>
					<td>{{ $reports['pickup_summary_totals']['tax'] }}</td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Total</th>
					<td>{{ $reports['pickup_summary_totals']['total'] }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Payment Summary</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Type</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Cash</td>
					<td>{{ $reports['total_splits']['cash']['subtotal'] }}</td>
					<td>{{ $reports['total_splits']['cash']['tax'] }}</td>
					<td>{{ $reports['total_splits']['cash']['total'] }}</td>
				</tr>
				<tr>
					<td>Credit Card</td>
					<td>{{ $reports['total_splits']['credit']['subtotal'] }}</td>
					<td>{{ $reports['total_splits']['credit']['tax'] }}</td>
					<td>{{ $reports['total_splits']['credit']['total'] }}</td>
				</tr>
				<tr>
					<td>Online</td>
					<td>{{ $reports['total_splits']['online']['subtotal'] }}</td>
					<td>{{ $reports['total_splits']['online']['tax'] }}</td>
					<td>{{ $reports['total_splits']['online']['total'] }}</td>
				</tr>
				<tr>
					<td>Check</td>
					<td>{{ $reports['total_splits']['check']['subtotal'] }}</td>
					<td>{{ $reports['total_splits']['check']['tax'] }}</td>
					<td>{{ $reports['total_splits']['check']['total'] }}</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align:right">Subtotal</th>
					<td>{{ $reports['totals']['subtotal'] }}</td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Tax</th>
					<td>{{ $reports['totals']['tax'] }}</td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Discount</th>
					<td>{{ $reports['totals']['discount'] }}</td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Total</th>
					<td>{{ $reports['totals']['total'] }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
@stop