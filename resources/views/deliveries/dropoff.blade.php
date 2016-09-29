@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/dropoff.js"></script>
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
    $('#dropoffdate').Zebra_DatePicker({
        container:$("#dropoff_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: ['{{ $date_start }}', false],
        show_select_today: false,
        onSelect: function(a, b) {
            var dropoff_address_id = $("#dropoff_address").val();
            request.set_time_dropoff(b, dropoff_address_id);
        }
    });

</script>
@stop

@section('navigation')
    <header id="header" class="reveal">
    {!! View::make('partials.layouts.navigation_logged_in')
        ->render()
    !!}
    </header>
@stop


@section('content')
    <div class="row">
        <div id="bc1" class="btn-group btn-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a href="{{ route('delivery_pickup') }}" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden;">
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
            <a href="{{ route('delivery_dropoff') }}" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden;">
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
            <a href="{{ route('delivery_confirmation') }}" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12 disabled" disabled="true" style="height:160px; overflow:hidden;">
            	<h2><span class="badge">3</span> Confirm</h2>
            </a>

        </div>
	</div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                {!! Form::open(['action' => 'DeliveriesController@postDropoffForm', 'class'=>'form-horizontal','role'=>"form"]) !!}
                    {!! csrf_field() !!} 
                    {!! Form::hidden('dropoff_address',$primary_address_id,['id'=>'dropoff_address']) !!}
                    <div class="panel-heading"><strong>Dropoff Form </strong>- we deliver to you!</div>

                    <div id="dropoff_body" class="panel-body">

<!--   
                        <div class="form-group{{ $errors->has('dropoff_address') ? ' has-error' : '' }} dropoff_address_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Address</label>

                            <div class="col-md-6">
                                
                                {{ Form::select('dropoff_address',$addresses,$primary_address_id,['id'=>'dropoff_address','class'=>'form-control','readonly'=>'true']) }}
                                @if ($errors->has('dropoff_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div> -->
                    

                        <div class="form-group{{ $errors->has('dropoff_date') ? ' has-error' : '' }} dropoff_date_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="{{ old('dropoff_date') }}" style="background-color:#ffffff" readonly="true">

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
                                
                                {{ Form::select('dropoff_time',[''=>'select time'],null,['id'=>'dropofftime','class'=>'form-control','disabled'=>'true']) }}
                                @if ($errors->has('dropoff_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dropoff_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                    	<a href="{{ route('delivery_pickup') }}" class='btn btn-link btn-lg'><i class="ion-arrow-left-c"></i> Back</a>
                        <button id="dropoff_submit" type="submit" data-toggle="modal" data-target="#loading" class="btn btn-lg btn-primary pull-right" disabled="true">Next</button>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop