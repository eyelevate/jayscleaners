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
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Customer Search Form</h4></div>
		<div class="panel-body">
			{!! Form::open(['action' => 'AccountsController@postIndex','role'=>"form"]) !!}
			<div class="form-group {{ $errors->has('search') ? ' has-error' : '' }}">
				<label class="control-label">Search</label>
				{{ Form::text('search',old('search'),['class'=>"form-control",'placeholder'=>'Last Name / Phone / ID']) }}
                @if ($errors->has('search'))
                    <span class="help-block">
                        <strong>{{ $errors->first('search') }}</strong>
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
						<th>Id</th>
						<th>Username</th>
						<th>Last</th>
						<th>First</th>
						<th>Phone</th>
						<th>Due</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (isset($customers))
					@foreach($customers as $customer)
					<tr class="{{ ($customer->status == 3) ? 'active' : ($customer->status== 2) ? 'info' : 'active' }}">
						<td>{{ $customer->account_transaction_id }}</td>
						<td>{{ $customer->username }}</td>
						<td>{{ $customer->last_name }}</td>
						<td>{{ $customer->first_name }}</td>
						<td>{{ $customer->phone }}</td>
						<td>{{ $customer->account_total }}</td>
						<td>
						@if ($customer->account_total > 0)
							<a href="{{ route('accounts_pay',$customer->id) }}" class="btn btn-info">Pay</a>
							<a href="{{ route('accounts_history',$customer->id) }}" class="btn btn-info">Payment History</a>
						@else
							<button type="button" class="btn btn-default" disabled="true">Pay</button>
							<a href="{{ route('accounts_history',$customer->id) }}" class="btn btn-info">Payment History</a>
						@endif
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>

		<div class="panel-footer">
			<button class="btn btn-lg btn-primary" type="button" data-toggle="modal" data-target="#bill">Send Monthly Bill</button>

		</div>

	</div>  

@stop
@section('modals')

@stop