@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHgMXHliJZJAxB0oBNmdVYYtaR1juWyuM&callback=initMap"async defer></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="/js/schedules/prepare_route.js"></script>
<script type="text/javascript">
	google.charts.load("upcoming", {packages:["map"]});
	google.charts.setOnLoadCallback(drawChartOriginal);

	function drawChartOriginal() {
		var setup = [
			['lat','long','name']
		];
		<?php foreach ($setup as $row) { ?>
			item_row = [<?php echo $row[0];?>,<?php echo $row[1];?>,<?php echo "'".$row[2]."'";?>];

			setup.push(item_row);
		<?php } ?>
		var options = {
			mapType: 'normal',
			showLine: true,
			showTooltip: true,
			showInfoWindow: true,
			useMapTypeControl: true,
			icons: {
				default: {
					normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Azure-icon.png',
					selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Right-Azure-icon.png'
				}
			}
		};
		var data = google.visualization.arrayToDataTable(setup);

		var map = new google.visualization.Map(document.getElementById('map_div'));
		map.draw(data, options);
	}


</script>
@if (count($droutes) > 0)
	@foreach($droutes as $ordered)
	<script type="text/javascript">
		google.charts.load("upcoming", {packages:["map"]});
		google.charts.setOnLoadCallback(drawChartDriver);

		function drawChartDriver() {
			driver_id = "map_div-"+<?php echo $ordered['driver']->id ?>;
			var setup = [
				['lat','long','name']
			];
			<?php foreach($ordered['schedule'] as $schedule) { ?>
				<?php foreach ($schedule as $dr) { ?>
					item_row = [<?php echo $dr['latitude'];?>,<?php echo $dr['longitude'];?>,<?php echo "'".ucFirst($dr['first_name']).' '.ucFirst($dr['last_name']).' - '.$dr['street']."'";?>];
					setup.push(item_row);
				<?php } ?>
			<?php } ?>
			var options = {
				mapType: 'normal',
				showLine: true,
				showTooltip: true,
				showInfoWindow: true,
				useMapTypeControl: true,
				icons: {
					default: {
						normal: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Azure-icon.png',
						selected: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/48/Map-Marker-Ball-Right-Azure-icon.png'
					}
				}
			};
			var data = google.visualization.arrayToDataTable(setup);

			var map = new google.visualization.Map(document.getElementById(driver_id));
			map.draw(data, options);
		}
	</script>
	@endforeach


@endif
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<div class="panel panel-default {{ (count($setup) > 0) ? '' : 'hide' }}">
		<div class="panel-heading">
			<h3 class="panel-title">Prepare Route</h3>
		</div>
		<div class="panel-body">
			<div id="map_div"></div>
		</div>
		<div class="table-responsive">
			<table class="table table-condensed table-striped table-hover"> 
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Street</th>
						<th>City</th>
						<th>State</th>
						<th>Zipcode</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (count($check) > 0)
					@foreach($check as $schedule)
					<tr>
						<td>
							{{ $schedule['id'] }}
						</td>
						<td>
							{{ ucFirst($schedule['first_name']).' '.ucFirst($schedule['last_name']) }}
						</td>
						<td>
							{{ $schedule['street'] }}
						</td>
						<td>
							{{ $schedule['city'] }}
						</td>
						<td>
							{{ $schedule['state'] }}
						</td>
						<td>
							{{ $schedule['zipcode'] }}
						</td>
						<td>
							{{ $schedule['latitude'] }}
						</td>
						<td>
							{{ $schedule['longitude'] }}
						</td>
						<td>
							<a class="btn btn-sm btn-info" href="" data-toggle="modal" data-target="#edit-{{ $schedule['id'] }}">Setup</a>
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="panel-footer form-horizontal">
			<div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }} clearfix">
                <label class="col-md-12 control-label padding-top-none">Driver </label>

                <div class="col-md-12 clearfix">
                    
                    {{ Form::select('employee_id',$drivers,old('employee_id'),['class'=>'form-control']) }}
                    @if ($errors->has('employee_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('employee_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <button id="updateDrivers" class="btn btn-success btn-lg">Update Drivers</button>
		</div>
	</div>
	@if (count($droutes) > 0)
		
		@foreach($droutes as $ordered)
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Driver ID#{{ $ordered['driver']->id.' - '.ucFirst($ordered['driver']->first_name).' '.ucFirst($ordered['driver']->last_name).' - '.$ordered['driver']->username}}</h3>
				</div>

				<div class="panel-body">
					<div id="map_div-{{ $ordered['driver']->id }}"></div>
				</div>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th>Stop #</th>
								<th>ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>A</th>
							</tr>
						</thead>
						<tbody class="sortable">
						<?php $idx = 0;?>
						@foreach($ordered['schedule'] as $droute)
							@foreach($droute as $dr)
								<?php $idx++; ?>
								<tr class="stopTr" style="cursor:pointer;">
									<td>
										<input class="stopInput" value="{{ $idx }}"/>
									</td>
									<td>{{ $dr['id'] }} {{ Form::hidden('schedule_id',$dr['id'],['class'=>'schedule_id']) }}</td>
									<td>{{ ucFirst($dr['first_name']).' '.ucFirst($dr['last_name']) }}</td>
									<td>{{ $dr['street'].' '.$dr['city'].', '.$dr['state'].' '.$dr['zipcode'] }}</td>
									<td>
										<div class="pull-left" style="padding-right:5px;">
											{!! Form::open(['action' => 'SchedulesController@postRevertSchedule','role'=>"form"]) !!}
											{!! Form::hidden('id',$dr['id']) !!}
											<button type="submit" class="btn btn-sm btn-danger">Revert</button>
											{!! Form::close() !!}
										</div>


									</td>
								</tr>
							@endforeach
							
						@endforeach
						</tbody>
					</table>
				
				</div>
				<div class="panel-footer cleafix">
					<a href="{{ route('droutes_csv',$ordered['driver']->id)}}" class="btn btn-warning btn-lg">Create CSV</a>
					<a href="{{ route('schedules_delivery_route',$ordered['driver']->id) }}" class="btn btn-lg btn-success">PROCEED TO ROUTE</a>
				</div>
			</div>

		@endforeach
	@endif
@stop

@section('modals')
@if (count($check) > 0)
	@foreach($check as $schedule)
	{!! View::make('partials.schedules.setup_route')
		->with('schedule',$schedule)
		->with('drivers',$drivers)
		->render()
	!!}
	@endforeach
@endif

@stop