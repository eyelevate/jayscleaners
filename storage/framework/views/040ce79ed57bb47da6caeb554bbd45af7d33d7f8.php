<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/chartjs/chartjs.min.js"></script>
<script type="text/javascript" src="/js/zipcode_requests/index.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>
<div class="box box-success">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Request Chart</h3>
	</div>
	<div class="box-body">
		<div class="chart">
			<!-- Sales Chart Canvas -->
			<canvas id="reportsChart" style="height: 350px;"></canvas>
		</div>
	</div>
	<div class="box-footer">
		<div class="table-responsive">
			<table class="table table-hover table-condensed table-striped">
				<thead>
					<tr>
						<th>Zipcode</th>
						<th>Count</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($requests['labels']) > 0): ?>
					<?php foreach($requests['labels'] as $key => $value): ?>
					<tr>
						<td><?php echo e($value); ?></td>
						<td><?php echo e($requests['datasets']['data'][$key]); ?></td>
						<td>
							<a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deny-<?php echo e($value); ?>">Deny</a>
							<a class="btn btn-success btn-sm" data-toggle="modal" data-target="#accept-<?php echo e($value); ?>">Accept</a>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="panel panel-info">
	<div class="panel-heading"><h3>Zipcode Requests</h3></div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Zipcode</th>
					<th>Name</th>
					<th>Comment</th>
					<th>Ip</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($zipcodes) > 0): ?>
				<?php foreach($zipcodes as $zipcode): ?>
				<tr>
					<td><?php echo e($zipcode->id); ?></td>
					<td><?php echo e($zipcode->zipcode); ?></td>
					<td><?php echo e($zipcode->name); ?></td>
					<td><?php echo e($zipcode->comment); ?></td>
					<td><?php echo e($zipcode->ip); ?></td>
					<td><?php echo e(date('D n/d/Y',strtotime($zipcode->created_at))); ?></td>

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
<?php if(count($requests['labels']) > 0): ?>
	<?php foreach($requests['labels'] as $key => $value): ?>
	<?php echo View::make('partials.zipcode_requests.deny')
	    ->with('zipcode',$value)
	    ->render(); ?>

	<?php echo View::make('partials.zipcode_requests.accept')
	    ->with('zipcode',$value)
	    ->render(); ?>

	<?php endforeach; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>