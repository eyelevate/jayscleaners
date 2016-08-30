@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/form.js"></script>
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
                {!! Form::open(['action' => 'DeliveriesController@postDropoffForm', 'class'=>'form-horizontal','role'=>"form"]) !!}
                    {!! csrf_field() !!} 
                    <div class="panel-heading">Dropoff Form</div>

                    <div id="dropoff_body" class="panel-body notactive">
                        <div class="form-group{{ $errors->has('dropoff_method') ? ' has-error' : '' }} dropoff_method_div ">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Method</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_method',$dropoff_method,'',['id'=>'dropoffmethod','class'=>'form-control']) }}
                                @if ($errors->has('dropoff_method'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_method') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>    
                        <div class="form-group{{ $errors->has('dropoff_address') ? ' has-error' : '' }} dropoff_address_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Address</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_address',$addresses,$primary_address_id,['id'=>'dropoff_address','class'=>'form-control','disabled'=>'true']) }}
                                @if ($errors->has('dropoff_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('address_index') }}" class="btn btn-link">Manage your address(es)</a>
                        </div>
                    

                        <div class="form-group{{ $errors->has('dropoff_date') ? ' has-error' : '' }} dropoff_date_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" disabled="true" value="{{ old('dropoff_date') }}">

                                @if ($errors->has('dropoff_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('dropoff_time') ? ' has-error' : '' }} dropoff_time_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Time</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_time',[''=>'select time'],null,['class'=>'form-control','disabled'=>'true']) }}
                                @if ($errors->has('dropoff_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <button type="submit" class="btn btn-lg btn-primary pull-right disabled" disabled="true">Set Delivery</button>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop