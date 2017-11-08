@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
<script type="text/javascript" src="/js/accounts/send.js"></script>
@stop

@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop

@section('content')
	<br/>
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Customer Search Form</h4></div>
		<div class="panel-body well well-sm">
			{!! Form::open(['action' => 'AccountsController@postSend','role'=>"form"]) !!}
			<div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                <label class="control-label">Customer ID</label>

            	{!! Form::text('customer_id','', ['id'=>'customer_id','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('customer_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('customer_id') }}</strong>
                    </span>
                @endif

            </div>
			<div class="form-group{{ $errors->has('transaction_id') ? ' has-error' : '' }}">
                <label class="control-label">Transaction ID</label>

            	{!! Form::text('transaction_id','', ['id'=>'transaction_id','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('transaction_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('transaction_id') }}</strong>
                    </span>
                @endif

            </div>
			<div class="form-group{{ $errors->has('billing_month') ? ' has-error' : '' }}">
                <label class="control-label">Select Month</label>

            	{!! Form::select('billing_month', $month ,date('n',strtotime('first day of previous month')), ['id'=>'billing_month','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('billing_month'))
                    <span class="help-block">
                        <strong>{{ $errors->first('billing_month') }}</strong>
                    </span>
                @endif

            </div>
			<div class="form-group{{ $errors->has('billing_month') ? ' has-error' : '' }}">
                <label class="control-label">Select Year</label>

            	{!! Form::select('billing_year', $year ,date('Y'), ['id'=>'billing_year','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('billing_year'))
                    <span class="help-block">
                        <strong>{{ $errors->first('billing_year') }}</strong>
                    </span>
                @endif

            </div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
			{!! Form::close() !!}
		</div>
		 
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>Trans ID</th>
						<th>Customer</th>
						<th>Last</th>
						<th>First</th>
						<th>Email</th>
						<th>Period</th>
						<th>Due Date</th>
						<th>Bill Total</th>
						<th>Total Due</th>
						<th><input type="checkbox" id="checkAll"/></th>
					</tr>
				</thead>
				<tbody id="accountTbody">
				@if (count($transactions) > 0)
					<?php $idx = 0; ?>
					@foreach($transactions as $transaction)
					<tr id="accountTr-{{ $transaction->id }}" class="accountTr">
						<td>{{ $transaction->id }}</td>
						<td>{{ $transaction->customer_id }}</td>
						<td>{{ $transaction->last_name }}</td>
						<td>{{ $transaction->first_name }}</td>
						<td>{{ $transaction->email }}</td>
						<td>{{ $transaction->billing_period }}</td>
						<td>{{ $transaction->due }}</td>
						<td>{{ money_format('$%i',$transaction->total) }}</td>
						<td>{{ money_format('$%i',$transaction->total_due) }}</td>
						<td>
							<input class="transaction_ids" type="checkbox" value="{{ $transaction->id }}" name="transaction_ids[{{ $idx++ }}]"/>
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>

		<div class="panel-footer">
			<a class="btn btn-lg btn-info" href="{{ route('accounts_preview') }}" target="_blank">Preview & Print</a>
			<button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#send">Email Selected Bills</button>
		</div>

	</div>  

@stop
@section('modals')
{!! View::make('partials.accounts.email_send')->render() !!}	
{!! View::make('partials.frontend.modals') !!}
@stop