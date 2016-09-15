@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')

    <header id="header" class="reveal">
        <h1 id="logo"><a href="{{ route('pages_index') }}">Jays Cleaners</a></h1>
        <nav id="nav">
            <ul>
                <li class="submenu">
                    <a href="#"><small>Hello </small><strong>{{ $auth->username }}</strong></a>
                    <ul>
                        <li><a href="{{ route('delivery_index') }}">Your Deliveries</a></li>
                        <li><a href="left-sidebar.html">Services</a></li>
                        <li><a href="right-sidebar.html">Business Hours</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li class="submenu">
                            <a href="#">{{ $auth->username }} menu</a>
                            <ul>
                                <li><a href="#">Dolore Sed</a></li>
                                <li><a href="#">Consequat</a></li>
                                <li><a href="#">Lorem Magna</a></li>
                                <li><a href="#">Sed Magna</a></li>
                                <li><a href="#">Ipsum Nisl</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a id="logout_button" href="#" class="button special">Logout</a>
                    {!! Form::open(['action' => 'PagesController@postLogout', 'id'=>'logout_form', 'class'=>'form-horizontal','role'=>"form"]) !!}
                    {!! Form::close() !!}
                </li>
            </ul>
        </nav>
    </header>
@stop


@section('content')
    <section class="wrapper style3 container special-alt no-background-image">
        <div class="row 50%">
        	<header>
				<h2><strong>Manage your address(es) here..</strong></h2>
            </header>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <section class="row clearfix">
                	<ul class="12u">
                		@if ($addresses)
                			@foreach($addresses as $address)
                				@if ($address->primary_address)
		                		<li>
									<div class="thumbnail">
										<div class="caption">
											<h3><strong>{{ $address->name }}</strong> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
											<p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
											<ul class="clearfix">
												<li class="pull-left"><a href="{{ route('address_delete',$address->id) }}" class="btn btn-danger" role="button">Delete</a>&nbsp</li>
												<li class="pull-left"><a href="{{ route('address_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp</li>
											</ul>
										</div>
									</div>
		                		</li>
                				@else
		                		<li>
									<div class="thumbnail">
										<div class="caption">
											<h3><strong>{{ $address->name }}</strong></h3>
											<p><i>{{ $address->street }} <br/> {{ ucfirst($address->city)}} , {{ strtoupper($address->state) }} {{ $address->zipcode }}</i></p>
											<ul class="clearfix">
												<li class="pull-left"><a href="{{ route('address_delete',$address->id) }}" class="btn btn-danger" role="button">Delete</a>&nbsp</li>
												<li class="pull-left"><a href="{{ route('address_edit',$address->id) }}" class="btn btn-default" role="button">Edit</a>&nbsp</li>
												<li class="pull-left"><a href="{{ route('address_primary',$address->id) }}" class="btn btn-primary" role="button">Set Primary</a>&nbsp</li>
											</ul>
										</div>
									</div>
		                		</li>
                				@endif
                			@endforeach
                		@endif

                	</ul>
				</section>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			    <ul>
			    	<li style="margin-bottom:10px;"><a href="{{ (is_array($form_previous)) ? route($form_previous[0],$form_previous[1]) : route($form_previous) }}" class="button special-red">Back</a></li>
		            <li><a href="{{ route('address_add') }}" class="button">Add Address</a></li>
		        </ul>
            </div>
        </div>
    </section>

@stop