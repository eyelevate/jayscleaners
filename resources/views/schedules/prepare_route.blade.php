@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHgMXHliJZJAxB0oBNmdVYYtaR1juWyuM&callback=initMap"async defer></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	var setup = [
		['lat','long','name']
	];
	<?php
	foreach ($setup as $row) {
	?>
		item_row = [<?php echo $row[0];?>,<?php echo $row[1];?>,<?php echo "'".$row[2]."'";?>];

		setup.push(item_row);
	<?php
	}
	?>
	console.log(setup)
	google.charts.load("upcoming", {packages:["map"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable(setup);

		var map = new google.visualization.Map(document.getElementById('map_div'));
		map.draw(data, {
			showTooltip: true,
			showInfoWindow: true
		});
	}
</script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<div class="panel panel-default">
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
		<div class="panel-footer">
		</div>
	</div>
	@if (count($droutes) > 0)
		@foreach($droutes as $ordered)
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Driver ID#{{ $ordered['driver']->id.' - '.ucFirst($ordered['driver']->first_name).' '.ucFirst($ordered['driver']->last_name).' - '.$ordered['driver']->username}}</h3>
				</div>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>A</th>
							</tr>
						</thead>
						<tbody>
						<?php $idx = 0;?>
						@foreach($ordered['schedule'] as $droute)
							@foreach($droute as $dr)
								<?php $idx++; ?>
								<tr>
									<td>{{ $idx }}</td>
									<td>{{ $dr['id'] }}</td>
									<td>{{ ucFirst($dr['first_name']).' '.ucFirst($dr['last_name']) }}</td>
									<td>{{ $dr['street'].' '.$dr['city'].', '.$dr['state'].' '.$dr['zipcode'] }}</td>
									<td><a href="" class="btn btn-sm btn-danger">Revert</a>&nbsp<a class="btn btn-sm btn-success" href="">UP</a>&nbsp<a class="btn btn-sm btn-info" href="">Down</a></td>
								</tr>
							@endforeach
							
						@endforeach
						</tbody>
					</table>
				
				</div>
				<div class="panel-footer">
					<button class="btn btn-warning btn-lg">Create CSV</button>
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