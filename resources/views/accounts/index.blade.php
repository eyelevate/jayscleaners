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
			{!! Form::open(['action' => 'DeliveriesController@postFindCustomer','role'=>"form"]) !!}
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (count($customers) > 0)
					@foreach($customers as $customer)
					<tr>
						<td>{{ $customer['id'] }}</td>
						<td>{{ $customer['username'] ? $customer['username'] : '' }}</td>
						<td>{{ $customer['last_name'] }}</td>
						<td>{{ $customer['first_name'] }}</td>
						<td>{{ \App\Job::formatPhoneString($customer['phone']) }}</td>
						<td><a href="{{ route('delivery_new',$customer['id']) }}">Select</a></td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>

		<div class="panel-footer"></div>

	</div>    

@stop
@section('modals')

@stop