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
                        <li><a href="{{ route('delivery_index') }}">Your Deliveries</a></li>
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
    <div class="wrapper style2 special-alt no-background-image">
    	<div class="row 50%">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <header>
                    <h2>Thank You!</h2>
                </header>
                <p>Your delivery has been scheduled and an email confirmation will be sent to you shortly. </p>
                <a class="button" href="{{ route('pages_index') }}">Home</a>
            </div>

        </div>
    </div>	


@stop