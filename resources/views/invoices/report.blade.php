@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Invoices</h4></div>
		<div ckass="panel-body">
			<div class="table-responsive">
				<table class="table table-condensed table-striped table-hover">	
					<thead>
						<tr>
							<th>Id</th>
							<th>Qty</th>
							<th>Items</th>
							<th>Drop</th>
							<th>Due</th>
							<th>Total</th>
							<th>A.</th>
						</tr>
					</thead>
					<tbody>
					@if (count($invoices) > 0)
						@foreach($invoices as $invoice)
						<tr>
							<td>{{ $invoice->invoice_id }}</td>
							<td>{{ $invoice->quantity }}</td>
							<td>
								<ul style="list-style:none;">
								@if (count($invoice['item_details']))
									@foreach($invoice['item_details'] as $ids)
									<li>{{ $ids['qty'] }}-{{ $ids['item'] }}</li>
										@if(count($ids['color']) > 0)
										<li>
											<ul>
											@foreach($ids['color'] as $colors_name => $colors_count)
												<li>{{ $colors_count }}-{{ $colors_name }}</li>
											@endforeach
											</ul>
										</li>
										@endif
									@endforeach
								@endif
								</ul>
							</td>
							<td>{{ date('D n/d/Y',strtotime($invoice->created_at)) }}</td>
							<td>{{ date('D n/d/Y', strtotime($invoice->due_date)) }}</td>
							<td>{{ $invoice->total }}</td>
							<td><a href="{{ route('invoices_edit',$invoice->invoice_id) }}">Edit</a></td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer"></div>
	</div>
@stop