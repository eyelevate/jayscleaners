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
                    <a href="#"><small>Hello </small><strong>{{ Auth::user()->username }}</strong></a>
                    <ul>
                        <li><a href="no-sidebar.html">Your Deliveries</a></li>
                        <li><a href="left-sidebar.html">Services</a></li>
                        <li><a href="right-sidebar.html">Business Hours</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li class="submenu">
                            <a href="#">{{ Auth::user()->username }} menu</a>
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
				<h2><strong>Manage your stored credit cards here..</strong></h2>
            </header>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <section class="row clearfix">
                	<ul class="12u">
                	@if(count($cards_data) > 0) 
                		@foreach($cards_data as $card)
        				<li>
							<div class="thumbnail" style="background-color:{{ $card['background_color'] }}">
								<div class="caption">
									<h3><strong>{{ $card['card_type'] }} {{ $card['card_number'] }}</strong> <span class="pull-right"><img src="{{ $card['card_image'] }}" /></span></h3>
									<p style="margin-bottom:5px;">{{ $card['first_name'] }} {{ $card['last_name'] }}</p>
									<p class="clearfix"> <strong>Expiration Date:</strong> <i>{{ $card['exp_month'] }} / {{ $card['exp_year'] }} <span class="pull-right">{{ $card['days_remaining']}}</span></p>
									<ul class="clearfix">
										<li class="pull-left"><a href="{{ route('cards_delete',$card['id']) }}" class="btn btn-danger" role="button">Delete</a>&nbsp</li>
										<li class="pull-left"><a href="{{ route('cards_edit',$card['id']) }}" class="btn btn-default" role="button">Edit</a>&nbsp</li>
									</ul>
								</div>
							</div>
                		</li>
                		@endforeach
                	@endif
                	</ul>
				</section>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			    <ul>
			    	<li style="margin-bottom:10px;"><a href="{{ route($form_previous) }}" class="button special-red">Back</a></li>
		            <li><a href="{{ route('cards_add') }}" class="button">Add Card</a></li>
		        </ul>
            </div>
        </div>
    </section>

@stop