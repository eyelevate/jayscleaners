@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript">
    $('#search_data').Zebra_DatePicker({
        container:$("#search_container"),
        format:'D m/d/Y',
        onSelect: function(a, b) {
        	$("#search_form").submit();
        }
    });
</script>
@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        {!! Form::open(['action' => 'SchedulesController@postChecklist','role'=>"form",'id'=>'search_form']) !!}
            {!! csrf_field() !!} 
		<div class="panel-body">

	        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }} search_div">
	            <label class="col-md-12 col-lg-12 col-sm-12 col-xs-12 control-label padding-top-none">Delivery Date</label>

	            <div id="search_container" class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
	                <input id="search_data" type="text" class="form-control" name="search" value="{{ old('search') ? old('search') : $delivery_date }}" style="background-color:#ffffff">

	                @if ($errors->has('search'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('search') }}</strong>
	                    </span>
	                @endif
	            </div>
	        </div>
		</div>

		{!! Form::close() !!}
		<div class="panel-footer clearfix">
			<a href="{{ route('delivery_overview') }}" class="btn btn-lg btn-danger pull-left"><i class="ion ion-chevron-left"></i>&nbsp; Back</a>
			<a href="{{ route('schedules_delivery_route') }}" class="btn btn-lg btn-primary pull-right">View Delivery Route &nbsp;<i class="ion ion-chevron-right"></i></a>
		</div>
	</div>

	<div class="box box-warning">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Required &nbsp;<span class="label label-default pull-right">{{ count($schedules) }}</span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		@if (count($schedules) > 0)
			@foreach($schedules as $schedule)
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#{{ $schedule['id'] }}</strong> - [{{ $schedule['customer_id'] }}] {{ $schedule['last_name'] }}, {{ $schedule['first_name'] }}</h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label">{{ $schedule['status_message'] }}</label>
						<div class="progress">
							@if($schedule['status'] == 1)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							@elseif($schedule['status'] == 2)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 3)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 4)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 5)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 6)
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							@elseif($schedule['status'] == 7)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 8)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							@elseif($schedule['status'] == 9)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 10)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 11)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							@elseif($schedule['status'] == 12)
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							@endif
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[{{ $schedule['customer_id'] }}] {{ $schedule['last_name'] }}, {{ $schedule['first_name'] }}</p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control">{{ $schedule['pickup_date'] }} ({{ $schedule['pickup_time'] }})</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control">{{ $schedule['dropoff_date'] }} ({{ $schedule['dropoff_time'] }})</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;">{{ $schedule['pickup_address_1'] }} <br/>{{ $schedule['pickup_address_2'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control">{{ $schedule['contact_name'] }} - {{ $schedule['contact_number'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;">{{ $schedule['special_instructions'] }}</p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="{{ route('delivery_admin_edit',$schedule['id']) }}">Edit Delivery</a>
					<?php
					switch($schedule['status']) {
						case 1:
						?>
						{!! Form::open(['action' => 'SchedulesController@postApprovePickup','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$schedule['id']) !!}
						<input type="submit" class="btn btn-success" value="Approve For Pickup" />
						{!! Form::close() !!}
						<?php
						break;

						case 4:
						?>
						{!! Form::open(['action' => 'SchedulesController@postApproveDropoff','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$schedule['id']) !!}
						<input type="submit" class="btn btn-success" value="Approve For Pickup" />
						{!! Form::close() !!}
						<?php
						break;
					}

					?>
				</div>
			</div>
			@endforeach
		@endif
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">

		</div><!-- /.box-footer -->
	</div>


	<div class="box box-success collapsed-box">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Approved &nbsp;<span class="label label-default pull-right">{{ count($approved_list) }}</span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		@if (count($approved_list) > 0)
			@foreach($approved_list as $al)
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#{{ $al['id'] }}</strong> - [{{ $al['customer_id'] }}] {{ $al['last_name'] }}, {{ $al['first_name'] }}</h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label">{{ $al['status_message'] }}</label>
						<div class="progress">
							@if($al['status'] == 1)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							@elseif($al['status'] == 2)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							@elseif($al['status'] == 3)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							@elseif($al['status'] == 4)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($al['status'] == 5)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($al['status'] == 6)
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							@elseif($al['status'] == 7)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($al['status'] == 8)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							@elseif($al['status'] == 9)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($al['status'] == 10)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							@elseif($al['status'] == 11)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							@elseif($al['status'] == 12)
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							@endif
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[{{ $al['customer_id'] }}] {{ $al['last_name'] }}, {{ $al['first_name'] }}</p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control">{{ $al['pickup_date'] }} ({{ $al['pickup_time'] }})</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control">{{ $al['dropoff_date'] }} ({{ $al['dropoff_time'] }})</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;">{{ $al['pickup_address_1'] }} <br/>{{ $al['pickup_address_2'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control">{{ $al['contact_name'] }} - {{ $al['contact_number'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;">{{ $al['special_instructions'] }}</p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="{{ route('delivery_admin_edit',$al['id']) }}">Edit Delivery</a>
					<?php
					switch($al['status']) {
						case 2:
						?>
						{!! Form::open(['action' => 'SchedulesController@postRevertPickup','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$al['id']) !!}
						<input type="submit" class="btn btn-danger" value="Revert Back" />
						{!! Form::close() !!}
						<?php
						break;

						case 5:
						?>
						{!! Form::open(['action' => 'SchedulesController@postRevertDropoff','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$al['id']) !!}
						<input type="submit" class="btn btn-danger" value="Revert Back" />
						{!! Form::close() !!}
						<?php
						break;
					}

					?>
				</div>
			</div>
			@endforeach
		@endif
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">
			<a href="#" class="btn btn-lg btn-primary pull-right" data-toggle="modal" data-target="#status_change">Email Status Change</a>
		</div><!-- /.box-footer -->
	</div>
@stop

@section('modals')
	{!! View::make('partials.schedules.status_change')
		->with('schedules',$approved_list)
		->with('delivery_date',$delivery_date)
		->render()
	!!}
@stop