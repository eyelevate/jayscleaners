@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/js/deliveries/confirmation.js"></script>
<script type="text/javascript">

</script>
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
    <div class="row">
        <div id="bc1" class="btn-group btn-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a href="{{ route('delivery_pickup') }}" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px;">
            	<h2><span class="badge">1</span> Pickup</h2>
        		<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;">{{ $breadcrumb_data['pickup_address'] }}</p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0">{{ $breadcrumb_data['pickup_date'] }}</p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0">{{ $breadcrumb_data['pickup_time'] }}</p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="{{ route('delivery_dropoff') }}" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px">
            	<h2><span class="badge">2</span> Dropoff</h2>
            	<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;">{{ $breadcrumb_data['dropoff_address'] }}</p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0">{{ $breadcrumb_data['dropoff_date'] }}</p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0">{{ $breadcrumb_data['dropoff_time'] }}</p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="{{ route('delivery_confirmation') }}" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px">
            	<h2><span class="badge">3</span> Confirm</h2>
            	<p>Not yet confirmed.</p>
            </a>

        </div>
	</div>
	<div class="row">
		
	</div>


@stop