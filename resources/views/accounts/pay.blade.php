@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
	<script type="text/javascript" src="/js/accounts/pay.js"></script>
@stop

@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop

@section('content')
    <br/>
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">Active Account Transactions</h3>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Customer ID</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Phone</th>
						<th>Account Due</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $customers->id }}</td>
						<td>{{ ucFirst($customers->last_name) }}</td>
						<td>{{ ucFirst($customers->first_name) }}</td>
						<td>{{ $customers->phone }}</td>
						<td>{{ money_format('$%i',$customers->account_total) }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>Due</th>
						<th>Paid</th>
						<th>Paid On</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (count($transactions) > 0)
					@foreach($transactions as $transaction)
					<tr id="transaction_tr-{{ $transaction->id }}" customer="{{ $customers->id }}" class="transaction_tr" style="cursor:pointer;">
						<td>{{ $transaction->id }}</td>
						<td>{{ date('m/Y',strtotime($transaction->created_at)) }}</td>
						<td>{{ money_format('$%i',$transaction->total) }}</td>
						@if (isset($transaction->account_paid))
						<td>{{ money_format('$%i', $transaction->account_paid) }}</td>
						@else
						<td>--</td>
						@endif
						@if (isset($transaction->account_paid_on))
						<td>{{ date('n/d/Y g:ia',strtotime($transaction->account_paid_on)) }}</td>
						@else
						<td>Not Paid</td>
						@endif
						
						<td>{{ $transaction->status_html }}</td>
						<td><input type="checkbox" class="transaction_id" value="{{ $transaction->id }}"/></td>
					</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"></td>
						<th>Quantity</th>
						<td id='quantity'>0</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Subtotal</th>
						<td id="subtotal">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Tax</th>
						<td id="tax">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>After Tax</th>
						<td id="aftertax">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Credits</th>
						<td id="credits">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Discount</th>
						<td id="discount">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Total Due</th>
						<td id="due">$0.00</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="box-body">
			{{ $transactions->links() }}
		</div>
		<div class="box-footer clearfix">
			<button class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#account_pay">Make Payment</button>
		</div>
	</div>


@stop
@section('modals')
	@if (count($transactions) > 0)
		@foreach($transactions as $transaction)
			@if (count($transaction->invoices) > 0)
			{!! View::make('partials.accounts.history')
				->with('transaction',$transaction)
				->with('invoices',$transaction->invoices)
				->render()
			!!}				
			@endif
		@endforeach
		{!!
			View::make('partials.accounts.pay')
			->with('status',1)
			->with('customer_id',$customers->id)
			->render()
		!!}
	@endif
@stop