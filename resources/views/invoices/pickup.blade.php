@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>

<script type="text/javascript" src="/js/invoices/pickup.js"></script>
@stop
@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-8">
			<div class="box box-primary" >
				<div class="box-header cleafix">
					<h4 class="clearfix">Select Invoice 
					@if ($customers->account)
						<span class="label label-danger pull-right">Account Customer</span>
					@endif
					</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>ID</th>
								<th>Rack</th>
								<th>Drop</th>
								<th>Due</th>
								<th>Qty</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody id="invoice_tbody">
						@if (count($invoices) > 0)
							@foreach($invoices as $invoice)
							<tr id="invoice_tr-{{ $invoice->id }}" class="invoice_tr" style="cursor:pointer">
								<td>{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
								<td>{{ $invoice->rack }}</td>
								<td>{{ date('D n/d',strtotime($invoice->created_at)) }}</td>
								<td>{{ date('D n/d',strtotime($invoice->due_date)) }}</td>
								<td>{{ $invoice->quantity }}</td>
								<td>{{ money_format('$%i',$invoice->pretax) }}</td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
				<div class="box-footer clearfix">
					<a href="{{ route('customers_view',$customer_id) }}" class="btn btn-lg btn-danger">Back</a>

				</div>
			</div>
		</article>
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
			<div class="box box-info">
				<div class="box-header">
					<h4 class="clearfix">Invoice Selected 
					@if ($customers->account)
						<span class="label label-danger pull-right">Account Customer</span>
					@endif
					</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-condensed table-striped table-hover">
						<thead>
							<th>ID</th>
							<th>Drop</th>
							<th>Due</th>
							<th>Qty</th>
							<th>Subtotal</th>
						</thead>
						<tbody id="selected_tbody">
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4" style="text-align:right;">Quantity</th>
								<td id="quantity_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Subtotal</th>
								<td id="subtotal_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Tax</th>
								<td id="tax_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Total</th>
								<td id="total_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Store Credit</th>
								<td id="credit_td" credit="{{ $credits }}">{{ money_format('$%i',$credits) }}</td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Discount</th>
								<td id="discount_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Total Due</th>
								<td id="due_td"></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="box-footer clearfix">
					@if ($customers->account)
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#account">Account Finish</button>
					@else
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#credit"><i class="ion ion-card"></i>&nbsp;Credit</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#cash"><i class="ion ion-cash"></i>&nbsp;Cash</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#cof"><i class="ion ion-folder"></i>&nbsp;CoF</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#check">Check</button>
					@endif
				</div>
			</div>
		</article>
	</section>
	<section class="hide">
		{!! Form::open(['action' => 'InvoicesController@postPickup','role'=>"form",'id'=>'invoiceForm']) !!}
		{{ Form::hidden('customer_id',$customer_id,['id'=>"customer_id"]) }}
			<div id="invoice_form" class="hide">

			</div>
		{!! Form::close() !!}
	</section>
@stop

@section('modals')
	{!! View::make('partials.invoices.account')
		->render()
	!!}
	{!! View::make('partials.invoices.credit')
		->render()
	!!}
	{!! View::make('partials.invoices.cash')
		->render()
	!!}
	{!! View::make('partials.invoices.cof')
		->with('cards',$cards)
		->render()
	!!}
	{!! View::make('partials.invoices.check')
		->render()
	!!}
@stop