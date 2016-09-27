@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/deliveries/new.js"></script>

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
		@if (!$customer_id)
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
		@else
		<div class="panel-body">
			<h5>Customer Selected</h5>
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Id</th>
							<th>Username</th>
							<th>Last</th>
							<th>First</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>
						<tr class="success">
							<td>{{ $customers->id }}</td>
							<td>{{ $customers->username }}</td>
							<td>{{ $customers->last_name }}</td>
							<td>{{ $customers->first_name }}</td>
							<td>{{ \App\Job::formatPhoneString($customers->phone) }}</td>
						</tr>
					</tbody>
				</table>	
			</div>	
		</div>
	</div>
	{!! Form::open(['action' => 'DeliveriesController@postNew','role'=>"form"]) !!}
	{!! Form::hidden('customer_id',$customer_id) !!}
	<div class="panel panel-primary">
		<div class="panel-heading" style="border-radius:0px;"><h4>Card Selection</h4></div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Id</th>
							<th>Card</th>
							<th>Exp</th>
							<th>Remaining</th>
							<th>Type</th>
							<th>A</th>
							<th>-</th>
						</tr>
					</thead>

					<tbody>
					@if (count($cards))
						@foreach($cards as $card)
						<tr class="cards_tr" style="cursor:pointer;">
							<td>{{ $card['id'] }}</td>
							<td>{{ $card['card_number'] }}</td>
							<td>{{ $card['exp_month'] }}/{{ $card['exp_year'] }}</td>
							<td>{{ $card['days_remaining'] }}</td>
							<td>{{ $card['card_type'] }}</td>
							<td><input class="card_ids" type="checkbox" value="{{ $card['id'] }}"/></td>
							<td><a href="{{ route('cards_admins_edit',$card['id']) }}">update</a></td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div >
				<a href="{{ route('cards_admins_index',$customer_id) }}" class="btn btn-primary">Add Card</a>
			</div>
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Address Selection</h4></div>
		<div class="panel-body">
			<h4>Address(es) On File</h4>
			<div class="table-responsive">
				<table class="table table-condensed table-">
					<thead>
						<tr>
							<th>Name</th>
							<th>Street</th>
							<th>Suite</th>
							<th>City</th>
							<th>State</th>
							<th>Zip</th>
							<th>Primary</th>
							<th>A.</th>
							<th>-</th>
						</tr>
					</thead>
					<tbody>
					@if (count($addresses))
						@foreach($addresses as $address)
						<tr class="{{ ($address->zipcode_status ? 'address_tr' : 'danger') }}" style="cursor:pointer;">
							<td>{{ $address->name }}</td>
							<td>{{ $address->street }}</td>
							<td>{{ $address->suite }}</td>
							<td>{{ $address->city }}</td>
							<td>{{ $address->state }}</td>
							<td>{{ $address->zipcode }}</td>
							<td>{{ $address->primary ? 'Yes' : 'No' }}</td>
							<td>
								@if ($address->zipcode_status)
								<input class="address_id" type='checkbox' name="address_id" value="{{ $address->id }}" /></td>
								@else
								<input type="checkbox" disabled="true" />
								@endif
							</td>
							<td><a href="{{ route('address_admin_edit',$address->id) }}">update</a></td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<a href="{{ route('address_admin_add',$customer_id) }}" class="btn btn-primary">Add New Address</a>
		</div>		
		<div class="panel-heading" style="border-radius:0px;"><h4>Pickup Form</h4></div>
		<div class="panel-body">
			<div class="form-group {{ $errors->has('pickingup') ? ' has-error' : '' }}">
				<label class="control-label">Are you picking up?</label>
				{{ Form::select('pickingup',['1'=>'Yes','0'=>'No'],1,['id'=>'pickingup','class'=>"form-control"]) }}
                @if ($errors->has('pickingup'))
                    <span class="help-block">
                        <strong>{{ $errors->first('pickingup') }}</strong>
                    </span>
                @endif
			</div>		
			<div id="pickup_div" class="form-group"></div>	
			<div id="pickup_time_div" class="form-group"></div>
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Dropoff Form</h4></div>
		<div class="panel-body">
			<div class="form-group {{ $errors->has('droppingoff') ? ' has-error' : '' }}">
				<label class="control-label">Are you dropping off?</label>
				{{ Form::select('droppingoff',['1'=>'Yes','0'=>'No'],1,['id'=>'droppingoff','class'=>"form-control"]) }}
                @if ($errors->has('droppingoff'))
                    <span class="help-block">
                        <strong>{{ $errors->first('droppingoff') }}</strong>
                    </span>
                @endif
			</div>		
			<div id="dropoff_div" class="form-group"></div>	
			<div id="dropoff_time_div" class="form-group"></div>
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Special Instructions</h4></div>
		<div class="panel-body">
			<div class="form-group {{ $errors->has('special_instructions') ? ' has-error' : '' }}">
				<label class="control-label">Special Instructions</label>
				{{ Form::textarea('special_instructions','',['class'=>"form-control"]) }}
                @if ($errors->has('special_instructions'))
                    <span class="help-block">
                        <strong>{{ $errors->first('special_instructions') }}</strong>
                    </span>
                @endif
			</div>		
		</div>
		@endif
		<div class="panel-footer clearfix">
			<button type="submit" class="btn btn-lg btn-primary">Set Delivery</button>
		</div>
	</div>
	{!! Form::close() !!}

@stop
