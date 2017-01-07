<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
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
				<?php if(count($check) > 0): ?>
					<?php foreach($check as $schedule): ?>
					<tr>
						<td>
							<?php echo e($schedule['id']); ?>

						</td>
						<td>
							<?php echo e(ucFirst($schedule['first_name']).' '.ucFirst($schedule['last_name'])); ?>

						</td>
						<td>
							<?php echo e($schedule['street']); ?>

						</td>
						<td>
							<?php echo e($schedule['city']); ?>

						</td>
						<td>
							<?php echo e($schedule['state']); ?>

						</td>
						<td>
							<?php echo e($schedule['zipcode']); ?>

						</td>
						<td>
							<?php echo e($schedule['latitude']); ?>

						</td>
						<td>
							<?php echo e($schedule['longitude']); ?>

						</td>
						<td><a class="btn btn-sm btn-success" href="">Accept Address</a></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>



<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>