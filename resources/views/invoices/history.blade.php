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
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-primary" >
				<div class="box-header"><h4>Select Invoice</h4></div>
				<div class="table-responsive">
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>ID</th>
								<th>Rack</th>
								<th>Drop</th>
								<th>Due</th>
								<th>Qty</th>
								<th>Status</th>
								<th>Subtotal</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="invoice_tbody">
						@if (count($invoices) > 0)
							@foreach($invoices as $invoice)
							<tr id="invoice_tr-{{ $invoice->id }}" class="invoice_tr" style="cursor:pointer; color:{{ $invoice->status_color }}; background-color:{{ $invoice->status_bg }};">
								<td>{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
								<td>{{ $invoice->rack }}</td>
								<td>{{ date('D n/d',strtotime($invoice->created_at)) }}</td>
								<td>{{ date('D n/d',strtotime($invoice->due_date)) }}</td>
								<td>{{ $invoice->quantity }}</td>
								<td>{{ $invoice->status_title }}</td>
								<td>{{ money_format('$%i',$invoice->pretax) }}</td>
								<td>
									<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#view-{{ $invoice->id }}">View</a>
									<a href="{{ route('invoices_edit',$invoice->id) }}" class="btn btn-sm btn-info">Edit</a>&nbsp;
									<a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#revert-{{ $invoice->id }}">Revert</a>
								</td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
				<div class="box-footer clearfix">
					<a class="btn btn-lg btn-danger" href="{{ route('customers_view',$customer_id) }}">Back</a>
					{{ $invoices->links() }}

				</div>
			</div>
		</article>
	</section>

@stop

@section('modals')
	@if (count($invoices) > 0)
		@foreach($invoices as $invoice)
		{!! View::make('partials.invoices.revert')
			->with('invoice',$invoice)
			->with('revert',$revert)
			->render()
		!!}
		{!! View::make('partials.invoices.view')
			->with('invoice',$invoice)
			->render()
		!!}
		{!! View::make('partials.invoices.remove')
			->with('invoice',$invoice)
			->render()
		!!}
		@endforeach
	@endif
@stop