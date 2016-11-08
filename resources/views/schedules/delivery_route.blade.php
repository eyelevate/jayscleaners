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
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        {!! Form::open(['action' => 'SchedulesController@postDeliveryRoute','role'=>"form",'id'=>'search_form']) !!}
            {!! csrf_field() !!} 
		<div class="panel-body">

	        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }} search_div">
	            <label class="col-md-12 col-lg-12 col-sm-12 col-xs-12 control-label padding-top-none">Delivery Date</label>

	            <div id="search_container" class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
	                <input id="search_data" type="text" class="form-control" name="search" value="{{ old('search') ? old('search') : $delivery_date }}" readonly="true" style="background-color:#ffffff">

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
			<a href="{{ route('schedules_checklist') }}" class="btn btn-lg btn-danger pull-left col-md-2 col-sm-6 col-xs-6"><i class="ion ion-chevron-left"></i>&nbsp;Back</a>
			<a href="{{ route('schedules_processing') }}" class="btn btn-lg btn-primary pull-right col-md-2 col-sm-6 col-xs-6">Process&nbsp;<i class="ion ion-chevron-right"></i></a>
		</div>
	</div>

	<div class="box box-warning">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Required &nbsp;<span class="label label-default pull-right">{{ ($schedules) ? count($schedules) : 0 }}</span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		@if ($schedules)
			<?php $idx = 0 ?>
			@foreach($schedules as $schedule)
				<?php $idx += 1 ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><label class="label label-default">Stop #{{ $idx }}</label>&nbsp;<strong>#{{ $schedule['id'] }}</strong> - [{{ $schedule['customer_id'] }}] {{ $schedule['last_name'] }}, {{ $schedule['first_name'] }}</h4>
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
					@if($schedule['status'] == 11)
					<div class="table-responsive form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="schedule_table table table-striped table-condensed table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Qty</th>
									<th>Items</th>
									<th>Subtotal</th>
									<th>A.</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$invoices = $schedule['invoices'];
							if (count($invoices) > 0) {
								foreach ($invoices as $invoice) {
								?>
								<tr class="disabled {{ ($invoice->schedule_id) ? 'warning' : '' }}" >
									<td>{{ $invoice->id }}</td>
									<td>{{ $invoice->quantity }}</td>
									<td>
										<ul style="list-style:none;">
										@if (count($invoice['item_details']))
											@foreach($invoice['item_details'] as $ids)
											<li>{{ $ids['qty'] }}-{{ $ids['item'] }}</li>
												@if(count($ids['color']) > 0)
												<li>
													<ul>
													@foreach($ids['color'] as $colors_name => $colors_count)
														<li>{{ $colors_count }}-{{ $colors_name }}</li>
													@endforeach
													</ul>
												</li>
												@endif
											@endforeach
										@endif
										</ul>
									</td>
									<td>{{ $invoice->pretax_html }}</td>
									<td>
										<input class="invoice_ids" readonly="true" disabled="true" type="checkbox" value="{{ $invoice->id }}" {{ ($invoice->schedule_id) ? 'checked="true"' : '' }} />
									</td>
								</tr>
								<?php
								}
							}
							
							?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="text-align:right">Qty&nbsp;</th>
									<td id="total_qty-{{ $schedule['id'] }}" class="disabled">{{ ($schedule['invoice_totals']) ? $schedule['invoice_totals']['quantity'] : '0' }}</td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Subtotal&nbsp;</th>
									<td id="total_subtotal-{{ $schedule['id'] }}" class="disabled">{{ ($schedule['invoice_totals']) ? $schedule['invoice_totals']['subtotal_html'] : '$0.00' }}</td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Tax&nbsp;</th>
									<td id="total_tax-{{ $schedule['id'] }}" class="disabled">{{ ($schedule['invoice_totals']) ? $schedule['invoice_totals']['tax_html'] : '$0.00' }}</td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Total&nbsp;</th>
									<td id="total_total-{{ $schedule['id'] }}" class="disabled">{{ ($schedule['invoice_totals']) ? $schedule['invoice_totals']['total_html'] : '$0.00' }}</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">					
						<label class="label label-success col-xs-12 col-sm-12 col-md-12 col-lg-12">Paid</label>
						{!! Form::open(['action' => 'SchedulesController@postRevertPayment','role'=>"form"]) !!}
						{!! Form::hidden('id',$schedule['id']) !!}
						<input type="submit" data-toggle="modal" data-target="#loading" class="btn btn-danger col-lg-12 col-md-12 col-sm-12 col-xs-12" value="Revert Payment" />
						{!! Form::close() !!}						
					</div>

					@endif
					<hr/>
					{!! Form::open(['action'=>'SchedulesController@postDelayDelivery','role'=>'form']) !!}
					{!! Form::hidden('id',$schedule['id']) !!}
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label class="control-label" style="text-align:right">Delay Delivery</label>
						<p>
						{{ Form::select('reason',$schedule['delay_list'],'',['class'=>'form-control']) }}
						</p>
						<div>
							<input type="submit" class="btn btn-danger" value="Submit Delay"/>
						</div>
					</div>						
					{!! Form::close() !!}
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="{{ route('delivery_admin_edit',$schedule['id']) }}">Edit Delivery</a>
					<a class="btn btn-warning" href="{{ $schedule['gmap_address'] }}">View Map</a>
					<?php
					switch($schedule['status']) {
						case 2:
						?>
						{!! Form::open(['action' => 'SchedulesController@postApprovePickedup','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$schedule['id']) !!}
						<input type="submit" class="btn btn-success" value="Picked Up" />
						{!! Form::close() !!}
						<?php
						break;


						case 11:
						?>
						{!! Form::open(['action' => 'SchedulesController@postApproveDroppedOff','role'=>"form",'class'=>'pull-right']) !!}
						{!! Form::hidden('id',$schedule['id']) !!}						
						<button type="submit" class="btn btn-success " href="#">Dropped Off</button>
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
	<div class="box box-danger collapsed-box">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Delayed &nbsp;<span class="label label-default pull-right">{{ count($delayed_list) }}</span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		@if (count($delayed_list) > 0)
			@foreach($delayed_list as $dl)
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#{{ $dl['id'] }}</strong> - [{{ $dl['customer_id'] }}] {{ $dl['last_name'] }}, {{ $dl['first_name'] }}</h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label">{{ $dl['status_message'] }}</label>
						<div class="progress">
							@if($dl['status'] == 1)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							@elseif($dl['status'] == 2)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 3)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 4)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 5)
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 6)
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							@elseif($dl['status'] == 7)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 8)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							@elseif($dl['status'] == 9)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 10)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 11)
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							@elseif($dl['status'] == 12)
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							@endif
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[{{ $dl['customer_id'] }}] {{ $dl['last_name'] }}, {{ $dl['first_name'] }}</p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control">{{ $dl['pickup_date'] }} ({{ $dl['pickup_time'] }})</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control">{{ $dl['dropoff_date'] }} ({{ $dl['dropoff_time'] }})</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;">{{ $dl['pickup_address_1'] }} <br/>{{ $dl['pickup_address_2'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control">{{ $dl['contact_name'] }} - {{ $dl['contact_number'] }}</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;">{{ $dl['special_instructions'] }}</p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="{{ route('delivery_admin_edit',$dl['id']) }}">Edit Delivery</a>
					<a class="btn btn-warning" data-toggle="modal" data-target="#email-{{ $dl['id'] }}">Email</a>
					{!! Form::open(['action' => 'SchedulesController@postRevertDelay','role'=>"form",'class'=>'pull-right']) !!}
					{!! Form::hidden('id',$dl['id']) !!}
					{!! Form::hidden('status',$dl['status']) !!}
					<input type="submit" class="btn btn-danger" value="Revert Back" />
					{!! Form::close() !!}
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
					<a class="btn btn-warning" data-toggle="modal" data-target="#email-{{ $al['id'] }}">Email</a>
					<?php
					switch($al['status']) {
						case 3:
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

		</div><!-- /.box-footer -->
	</div>
@stop

@section('modals')

	@if(count($delayed_list) > 0)
		@foreach($delayed_list as $dl)
		{!! View::make('partials.schedules.email')
			->with('schedule_id',$dl['id'])
			->render()
		!!}
		@endforeach
	@endif
	@if(count($approved_list) > 0)
		@foreach($approved_list as $al)
		{!! View::make('partials.schedules.email')
			->with('schedule_id',$al['id'])
			->render()
		!!}
		@endforeach
	@endif

@stop