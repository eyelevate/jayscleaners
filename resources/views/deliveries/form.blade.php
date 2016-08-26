@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/form.js"></script>
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
                <div class="panel-heading">Delivery Form</div>
                <div class="panel-body">
                    {!! Form::open(['action' => 'DeliveriesController@postForm', 'class'=>'form-horizontal','role'=>"form"]) !!}
                        {!! csrf_field() !!}
                        
                        <div class="form-group{{ $errors->has('pickup_address') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Pickup Address</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('pickup_address',$addresses,$primary_address_id,['class'=>'form-control']) }}
                                @if ($errors->has('pickup_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_address') }}</strong>
                                    </span>
                                @endif

                                <a href="{{ route('address_index') }}" class="btn btn-link">Manage your address(es)</a>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('pickup_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Pickup Date</label>

                            <div id="pickup_container" class="col-md-6">
                                <input type="text" class="datepicker form-control" name="pickup_date" value="{{ old('pickup_date') }}" style="background-color:#ffffff;">

                                @if ($errors->has('pickup_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('pickup_time') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Pickup Time</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('pickup_time',['1'=>'a','2'=>'b'],null,['class'=>'form-control']) }}
                                @if ($errors->has('pickup_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('dropoff_address') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Address</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_address',$addresses,$primary_address_id,['class'=>'form-control']) }}
                                @if ($errors->has('dropoff_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_address') }}</strong>
                                    </span>
                                @endif

                                <a href="{{ route('address_index') }}" class="btn btn-link">Manage your address(es)</a>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dropoff_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                <input type="text" class="datepicker form-control" name="dropoff_date" value="{{ old('dropoff_date') }}" style="background-color:#ffffff;">

                                @if ($errors->has('dropoff_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('dropoff_time') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Time</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_time',['1'=>'a','2'=>'b'],null,['class'=>'form-control']) }}
                                @if ($errors->has('dropoff_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-lg btn-primary pull-right">Set Delivery</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop