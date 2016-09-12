@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/update.js"></script>
<script type="text/javascript">
	disabled_dates = [];
    <?php
    if (count($calendar_disabled) > 0) {
        foreach ($calendar_disabled as $cd) {
        ?>
        var item_string = '{{ $cd }}';
        disabled_dates.push(item_string);
        <?php
        }
    }
    ?>
    $('#pickupdate').Zebra_DatePicker({
        container:$("#pickup_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: [true, false],
        show_select_today: false,
        @if ($selected_date)
        start_date :'{{ date("D m/d/Y",strtotime($selected_date)) }}',
        @endif
        onSelect: function(a, b) {
            var pickup_address_id = $("#pickup_address option:selected").val();
            var pickup_delivery_id = $("#pickuptime option:selected").val();
            request.set_time_pickup(b, pickup_address_id, pickup_delivery_id);
        }
    });
    $('#dropoffdate').Zebra_DatePicker({
        container:$("#dropoff_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: ['{{ date("D m/d/Y",strtotime($dropoff_date)) }}', false],
        show_select_today: false,
        onSelect: function(a, b) {
            var dropoff_address_id = $("#pickup_address option:selected").val();
            request.set_time_dropoff(b, dropoff_address_id);
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
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                {!! Form::open(['action' => 'DeliveriesController@postUpdate', 'class'=>'form-horizontal','role'=>"form"]) !!}
                    {!! csrf_field() !!} 
                    {!! Form::hidden('id',$update_id) !!}
                    <div class="panel-heading"><strong>Delivery Update Form</strong></div>
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
                                <input id="pickupdate" type="text" class="form-control" name="pickup_date" value="{{ (old('pickup_date')) ? old('pickup_date') : ($selected_date) ? date('D m/d/Y',strtotime($selected_date)) : '' }}" style="background-color:#ffffff;">
                                @else
                                <input id="pickupdate" type="text" class="datepicker form-control" name="pickup_date" value="{{ old('pickup_date') }}" disabled="true">
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
                                @if ($selected_delivery_id)
                                {{ Form::select('pickup_time',$time_options,$selected_delivery_id,['id'=>'pickuptime','class'=>'form-control']) }}
                                @else
                                {{ Form::select('pickup_time',[''=>'select time'],null,['id'=>'pickuptime','class'=>'form-control', 'disabled'=>"true"]) }}
                                @endif
                                
                                @if ($errors->has('pickup_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
					<div id="dropoff_body" class="panel-body">  
                        <div class="form-group{{ $errors->has('dropoff_date') ? ' has-error' : '' }} dropoff_date_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                @if ($zipcode_status) 
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="{{ (old('dropoff_date')) ? old('dropoff_date') : ($dropoff_date) ? date('D m/d/Y',strtotime($dropoff_date)) : '' }}" style="background-color:#ffffff;">
                                @else
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="{{ old('dropoff_date') }}" disabled="true">
                                @endif
                                

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
                                
								@if ($dropoff_delivery_id)
                                {{ Form::select('dropoff_time',$time_options_dropoff,$dropoff_delivery_id,['id'=>'dropofftime','class'=>'form-control']) }}
                                @else
                                {{ Form::select('dropoff_time',[''=>'select time'],null,['id'=>'dropofftime','class'=>'form-control', 'disabled'=>"true"]) }}
                                @endif

                                @if ($errors->has('dropoff_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('special_instructions') ? ' has-error' : '' }} dropoff_time_div">
                            <label class="col-md-4 control-label padding-top-none">Special Instructions</label>

                            <div class="col-md-6">
                                {{ Form::textarea('special_instructions',old('special_instructions') ? old('special_instructions') : ($special_instructions) ? $special_instructions : null), ['class'=>'form-control'] }}

                                @if ($errors->has('dropoff_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer clearfix">
                        <a href="{{ route('delivery_index') }}" class="btn btn-danger btn-lg">Cancel</a>
                        <button id="pickup_submit" type="submit" class="btn btn-lg btn-primary pull-right" >Update</button>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop