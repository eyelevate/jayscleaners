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
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">Account Transaction History</h3>
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
					<tr class="{{ $transaction->status_class }}">
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
						<td>
							<button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#invoices-{{ $transaction->id }}">invoices</button>
							@if (isset($transaction->account_paid_on))
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#revert-{{ $transaction->id }}">Revert Paid</button>
							@endif
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="box-body">
			{{ $transactions->links() }}
		</div>
		<div class="box-footer clearfix">
			<a class="btn btn-primary btn-lg pull-right" href="{{ route('accounts_pay',$customers->id) }}">Make Payment</a>
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
			{!! View::make('partials.accounts.revert')
				->with('transaction',$transaction)
				->render()
			!!}				
			@endif
		@endforeach
	@endif
@stop