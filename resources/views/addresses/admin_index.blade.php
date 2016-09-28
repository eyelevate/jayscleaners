@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')

    <div class="panel panel-default">
    	<div class="panel-heading">
    		<strong>Address Update</strong>
    	</div>
    	<div class="panel-body">
		@if ($addresses)
			@foreach($addresses as $address)
				@if ($address->primary_address)
				<div class="thumbnail">
					<div class="caption">
						<h3><strong>{{ $address->name }}</strong> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
						<p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
						<p><strong>{{ $address->concierge_name }}</strong><br/><i>{{ $address->concierge_number }}</i></p>
						<div class="clearfix" >
							<div class="pull-left"><a href="{{ route('address_admin_delete',$address->id) }}" class="btn btn-danger" role="button">Delete</a>&nbsp;</div>
							<div class="pull-left"><a href="{{ route('address_admin_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp;</div>
						</div>
					</div>
				</div>
				@else
				<div class="thumbnail">
					<div class="caption">
						<h3><strong>{{ $address->name }}</strong></h3>
						<p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
						<p><strong>{{ $address->concierge_name }}</strong><br/><i>{{ $address->concierge_number }}</i></p>
						<div class="clearfix">
							<div class="pull-left"><a href="{{ route('address_admin_delete',$address->id) }}" class="btn btn-danger" role="button">Delete</a>&nbsp;</div>
							<div class="pull-left"><a href="{{ route('address_admin_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp;</div>
							<div class="pull-left"><a href="{{ route('address_admin_primary',$address->id) }}" class="btn btn-primary" role="button">Set Primary</a>&nbsp;</div>
						</div>
					</div>
				</div>
				@endif
			@endforeach
		@endif
    	</div>
	    <div class="panel-footer clearfix">
	    	<a href="{{ (is_array($form_previous)) ? route($form_previous[0],$form_previous[1]) : route($form_previous) }}" class="btn btn-danger btn-lg pull-left">Back</a>
			<a href="{{ route('address_admin_add',$id) }}" class="btn btn-lg btn-primary pull-right">Add Address</a>
	    </div>
    </div>

@stop