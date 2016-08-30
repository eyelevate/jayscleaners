@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/pickup.js"></script>
<script type="text/javascript">

    $('#pickupdate').Zebra_DatePicker({
        container:$("#pickup_container"),
        format:'D m/d/Y',
        disabled_dates: ['{{ $calendar_disabled }}'],
        direction: [true, false],
        onSelect: function(a, b) {
            var pickup_address_id = $("#pickup_address option:selected").val();
            request.set_time_pickup(b, pickup_address_id);
        }
    });

</script>
@stop

@section('navigation')
    <header id="header" class="reveal">
        <h1 id="logo"><a href="{{ route('pages_index') }}">Jays Cleaners</a></h1>
        <nav id="nav">
            <ul>
                <li class="submenu">
                    <a href="#"><small>Hello </small><strong>{{ $auth->username }}</strong></a>
                    <ul>
                        <li><a href="no-sidebar.html">Your Deliveries</a></li>
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
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                {!! Form::open(['action' => 'DeliveriesController@postPickupForm', 'class'=>'form-horizontal','role'=>"form"]) !!}
                    {!! csrf_field() !!} 
                    <div class="panel-heading"><strong>Pickup Form</strong></div>
                    <div id="pickup_body" class="panel-body">                   
                        <div class="form-group{{ $errors->has('pickup_address') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Pickup Address</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('pickup_address',$addresses,$primary_address_id,['class'=>'form-control','id'=>'pickup_address']) }}
                                @if ($errors->has('pickup_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_address') }}</strong>
                                    </span>
                                @endif

                                <a href="{{ route('address_index') }}" class="btn btn-link">Manage your address(es)</a>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('pickup_date') ? ' has-error' : '' }} pickup_date_div ">
                            <label class="col-md-4 control-label padding-top-none">Pickup Date</label>

                            <div id="pickup_container" class="col-md-6">
                                @if ($zipcode_status) 
                                <input id="pickupdate" type="text" class="datepicker form-control" name="pickup_date" value="{{ old('pickup_date') }}" style="background-color:#ffffff;">
                                @else
                                <input id="pickupdate" type="text" class="datepicker form-control" name="pickup_date" value="{{ old('pickup_date') }}" disabled="true" >
                                @endif
                                @if ($errors->has('pickup_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group{{ $errors->has('pickup_time') ? ' has-error' : '' }} pickup_time_div">
                            <label class="col-md-4 control-label padding-top-none">Pickup Time</label>

                            <div class="col-md-6">
                                {{ Form::select('pickup_time',[''=>'select time'],null,['id'=>'pickuptime','class'=>'form-control', 'disabled'=>"true"]) }}
                                
                                @if ($errors->has('pickup_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer clearfix">
                        <button id="pickup_submit" type="submit" class="btn btn-lg btn-primary pull-right" disabled="true">Set Pickup</button>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop