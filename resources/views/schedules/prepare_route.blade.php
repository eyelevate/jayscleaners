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
						<td><a class="btn btn-sm btn-success" href="">Accept Address</a></td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
		</div>
	</div>
@stop

@section('modals')



@stop