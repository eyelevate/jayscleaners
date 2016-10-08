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
                        <h3 style="text-align:left;"><strong>{{ $address->name }} </strong> <small>{{ ($address->zipcode_status) ? '' : '- zipcode not deliverable' }}</small> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">       
                            <strong style="text-align:left;">Address</strong>
                            <p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
						</div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <strong style="text-align:left;">Contact Info</strong>
                            <p>{{ ucFirst($address->concierge_name) }} <br/>{{ $address->concierge_number }}</p>
                        </div>
                        <ul class="clearfix col-xs-12 col-md-12 col-sm-12 col-lg-12">
							<li class="pull-left"><a href="{{ route('address_admin_delete',$address->id) }}" class="btn btn-danger" role="button">Delete</a>&nbsp</li>
							<li class="pull-left"><a href="{{ route('address_admin_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp</li>

                        </ul>
					</div>
				</div>
				@else
				<div class="thumbnail">
					<div class="caption">
                        <h3 style="text-align:left;"><strong>{{ $address->name }} </strong> <small>{{ ($address->zipcode_status) ? '' : '- zipcode not deliverable' }}</small> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <strong style="text-align:left;">Address</strong>
                            <p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <strong style="text-align:left;">Contact Info</strong>
                            <p>{{ ucFirst($address->concierge_name) }} <br/>{{ $address->concierge_number }}</p>
                        </div>
						<ul class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<li class="pull-left"><a class="btn btn-danger" role="button" data-toggle="modal" data-target="#address_delete-{{ $address['id'] }}">Delete</a>&nbsp</li>
							<li class="pull-left"><a href="{{ route('address_admin_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp</li>
							@if ($address->zipcode_status)
                            <li class="pull-left"><a href="{{ route('address_admin_primary',$address->id) }}" class="btn btn-primary" role="button">Set Primary</a>&nbsp</li>
						    @endif
                        </ul>
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